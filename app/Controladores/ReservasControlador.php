<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\Sesion;
use App\Modelos\ReservasModel;
use MercadoPago\Config\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class ReservasControlador extends ControladorBase {
    private $modelo;

    public function __construct(){
        parent::__construct();
        // Configura tu Access Token de Mercado Pago (sandbox o producción)
        \MercadoPago\MercadoPagoConfig::setAccessToken('TEST-7976166044467123-071000-22e967d2c403d13c4fc816e59ebfbe4b-1361259632');
        $this->modelo = new ReservasModel();
    }

    /** Paso 1: menú principal */
    public function index(){
        if ($this->tieneNivel([2, 3, 4, 5, 6])) return; // bloquea cocina, mesero, rrhh, inventario, finanzas
        $this->vista('Reservas/index');
    }

    /** Paso 2: formulario estándar */
    public function estandar(){
        $this->vista('Reservas/estandar');
    }

    /**
     * Guarda nueva reserva estándar **(respaldo sin pago)**.
     */
    public function guardar(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' 
            || !Sesion::verificarCsrf($_POST['csrf_token'] ?? '')
        ) {
            http_response_code(400);
            exit('Solicitud inválida');
        }

        $datos = [
            'id_cliente'                 => $_SESSION['user_id'] ?? null,
            'tipo'                       => 'estandar',
            'fecha'                      => $_POST['fecha'],
            'hora'                       => $_POST['hora'],
            'personas'                   => (int) $_POST['personas'],
            'titular'                    => trim($_POST['titular']),
            'alergias'                   => trim($_POST['alergias'] ?? ''),
            'celebracion'                => trim($_POST['celebracion'] ?? ''),
            'requerimientos_adicionales' => trim($_POST['requerimientos_adicionales'] ?? '')
        ];
        $datos['monto'] = $datos['personas'] * 50;

        $resId = $this->modelo->crearReserva($datos);
        if (!$resId) {
            $_SESSION['error'] = "No se pudo crear la reserva.";
            return $this->redirect(BASE_URL . "reservas/estandar");
        }

        $this->modelo->registrarIngreso('estandar', $resId, $datos['monto']);
        $this->modelo->bloquearSlot($datos['fecha'], $datos['hora']);

        $codigo = $this->modelo->getCodigoById($resId);
        $_SESSION['success'] = "Reserva creada. Tu código de reserva es: {$codigo}";
        return $this->redirect(BASE_URL . "reservas/index");
    }

    /** Paso 2‑b: ver reserva por código */
    public function ver(){
        $codigo  = $_GET['codigo'] ?? '';
        $reserva = $this->modelo->getPorCodigo($codigo);
        $this->vista('Reservas/ver', ['reserva' => $reserva]);
    }

    /** Actualizar datos (requiere pago) */
    public function actualizar(){
        $id = (int)$_POST['id_reserva'];
        $datos = [
            'fecha'    => $_POST['fecha'],
            'hora'     => $_POST['hora'],
            'personas' => (int)$_POST['personas'],
            'titular'  => trim($_POST['titular']),
        ];
        $this->modelo->actualizarReserva($id, $datos);
        $this->redirect(BASE_URL . "reservas/ver?codigo=" . $_POST['codigo']);
    }

    /** Eliminar reserva (requiere pago) */
    public function eliminar(){
        $this->modelo->eliminarReserva((int)$_POST['id_reserva']);
        $_SESSION['success'] = "Reserva eliminada.";
        $this->redirect(BASE_URL . "reservas/index");
    }

    /** JSON: obtener horas no disponibles en una fecha */
    public function disponibilidad() {
        header('Content-Type: application/json');
        $fecha = $_GET['fecha'] ?? '';
        if (!$fecha) {
            echo json_encode([]);
            return;
        }
        try {
            $blocked = $this->modelo->getHorasNoDisponibles($fecha);
            echo json_encode($blocked);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Endpoint AJAX: procesa el pago con Mercado Pago y, si se aprueba,
     * crea la reserva, registra ingreso y bloquea el slot.
     */
    public function pagar()
    {
        // 0) Deshabilitar warnings y limpiar buffer
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        if (ob_get_length()) ob_clean();
    
        // 1) Verificar método y Content-Type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST'
            || stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === false
        ) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'Solicitud inválida']);
            return;
        }
    
        // 2) Leer y decodificar JSON
        $raw = file_get_contents('php://input');
        $payload = json_decode($raw, true);
        if (!$payload) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'JSON inválido']);
            return;
        }
    
        // 3) Construir request para MP
        $paymentRequest = [
            'transaction_amount' => (float) ($payload['transactionAmount'] ?? 0),
            'token'              => $payload['token'] ?? '',
            'description'        => $payload['description'] ?? '',
            'installments'       => (int)   ($payload['installments'] ?? 1),
            'payment_method_id'  => $payload['payment_method_id'] ?? '',
            'payer'              => [
                'email'      => $payload['payer']['email']      ?? '',
                'first_name' => $payload['payer']['first_name'] ?? '',
                'last_name'  => $payload['payer']['last_name']  ?? '',
                'identification' => [
                    'type'   => $payload['payer']['identification']['type']   ?? '',
                    'number' => $payload['payer']['identification']['number'] ?? ''
                ]
            ],
        ];
        if (!empty($payload['issuer_id'])) {
            $paymentRequest['issuer_id'] = $payload['issuer_id'];
        }
    
        // 4) Validaciones mínimas
        if ($paymentRequest['transaction_amount'] <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'transaction_amount inválido']);
            return;
        }
        if (empty($paymentRequest['payment_method_id'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'payment_method_id inválido o vacío']);
            return;
        }
        if (empty($paymentRequest['payer']['email'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'payer.email inválido o vacío']);
            return;
        }
        if (empty($paymentRequest['payer']['identification']['type'])
        || empty($paymentRequest['payer']['identification']['number'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'detail' => 'Identificación del payer incompleta']);
            return;
        }
    
        // 5) Enviar a MP
        try {
            // DEBUG: graba el request
            file_put_contents(__DIR__ . '/debug_payment.txt', print_r($paymentRequest, true));
            $client  = new \MercadoPago\Client\Payment\PaymentClient();
            $payment = $client->create($paymentRequest);
        } catch (\MercadoPago\Exceptions\MPApiException $e) {
            $status = $e->getApiResponse()->getStatusCode();
            http_response_code($status);
            echo json_encode([
                'status' => 'error',
                'detail' => $e->getApiResponse()->getContent()
            ]);
            return;
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'detail' => $e->getMessage()
            ]);
            return;
        }
    
        header('Content-Type: application/json');
    
        // 6) Si está aprobado, crear reserva
        if (isset($payment->status) && $payment->status === 'approved') {
            $d = $payload['datosReserva'] ?? [];
            $datos = [
                'id_cliente'                 => $_SESSION['user_id'] ?? null,
                'tipo'                       => 'estandar',
                'fecha'                      => $d['fecha'] ?? date('Y-m-d'),
                'hora'                       => $d['hora'] ?? '00:00:00',
                'personas'                   => (int) ($d['personas'] ?? 1),
                'titular'                    => trim($d['titular'] ?? ''),
                'alergias'                   => trim($d['alergias'] ?? ''),
                'celebracion'                => trim($d['celebracion'] ?? ''),
                'requerimientos_adicionales' => trim($d['req_extra'] ?? ''),
                'monto'                      => $paymentRequest['transaction_amount']
            ];
    
            $resId = $this->modelo->crearReserva($datos);
            $this->modelo->registrarIngreso('estandar', $resId, $datos['monto']);
            $this->modelo->bloquearSlot($datos['fecha'], $datos['hora']);
            $codigo = $this->modelo->getCodigoById($resId);
    
            echo json_encode([
                'status' => 'approved',
                'codigo' => $codigo
            ]);
            return;
        }
    
        // 7) Si no está aprobado, devolver detalle del rechazo
        echo json_encode([
            'status' => $payment->status ?? 'unknown',
            'detail' => $payment->status_detail ?? ''
        ]);
    }
    
        
}
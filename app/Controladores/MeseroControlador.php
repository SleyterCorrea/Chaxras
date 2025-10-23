<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Modelos\MeseroModel;
use App\Core\BaseDatos;
use App\Core\Sesion;

class MeseroControlador extends ControladorBase {
    private $modelo;

    public function __construct() {
        // ✅ Aseguramos que la sesión esté iniciada
        Sesion::iniciar();
        $this->modelo = new MeseroModel();
    }

    public function index() {
        if (!$this->verificarAcceso([1,4])) return;
        $mesas = $this->modelo->obtenerMesasDisponibles();
        $platos = $this->modelo->obtenerPlatosActivos();

        // Agrupar platos por categoría
        $agrupados = [];
        foreach ($platos as $p) {
            $agrupados[$p['categoria']][] = $p;
        }

        $this->vista('mesero/pedidos', compact('mesas', 'agrupados'));
    }

    public function prepararOrden() {
        if (!$this->verificarAcceso([1,4])) return;
        // ✅ Validación de sesión
        $idMesero = $_SESSION['user']['id_usuario'] ?? null;
        if (!$idMesero) return $this->redirect(BASE_URL . "login");

        $mesa = $_POST['mesa'] ?? null;
        $platos = $_POST['plato'] ?? [];
        $ordenes = $_POST['orden_entrega'] ?? [];

        // Filtrar solo los ids con cantidad > 0
        $ids = [];
        foreach ($platos as $id => $cantidad) {
            if ($cantidad > 0) {
                $ids[] = (int)$id;
            }
        }

        if (!$mesa) {
            $_SESSION['error'] = "Por favor, selecciona una mesa.";
            return $this->redirect(BASE_URL . "mesero");
        }

        if (empty($ids)) {
            $_SESSION['error'] = "Debes seleccionar al menos un plato.";
            return $this->redirect(BASE_URL . "mesero");
        }

        $detalle = $this->modelo->obtenerDetallesParaPreparar($ids, $platos);

        if (empty($detalle)) {
            $_SESSION['error'] = "No se encontraron datos válidos para los platos seleccionados.";
            return $this->redirect(BASE_URL . "mesero");
        }

        $this->vista('mesero/preparar_orden', compact('mesa', 'detalle'));
    }

    public function mis_ordenes() {
        if (!$this->verificarAcceso([1,4])) return;
        $idMesero = $_SESSION['user']['id_usuario'] ?? null;
        if (!$idMesero) return $this->redirect(BASE_URL . "login");

        $mesas = $this->modelo->obtenerMesasConTotales();
        $ordenes_por_mesa = $this->modelo->obtenerPedidosActivos($idMesero);
        $estados_platos = $this->modelo->obtenerEstadosDetalle();

        $this->vista('mesero/mis_ordenes', compact('mesas', 'ordenes_por_mesa', 'estados_platos'));
    }


    public function verOrden($id) {
        if (!$this->verificarAcceso([1,4])) return;
        $idMesero = $_SESSION['user']['id_usuario'] ?? null;
        if (!$idMesero || !$id || !is_numeric($id)) {
            echo "<p style='color:red'>Error: No autorizado o sin ID.</p>";
            exit;
        }

        $pedido = $this->modelo->obtenerPedidoPorIdMesero($id, $idMesero);
        if (!$pedido) {
            echo "<p style='color:red'>Error: Pedido no encontrado o no autorizado.</p>";
            exit;
        }

        $detalle = $this->modelo->obtenerDetallePedido($id);
        require dirname(__DIR__) . "/Vistas/mesero/ver_orden_modal.php";
    }

    public function guardarOrdenAjax() {
        
        $this->json();

        // ✅ Validar sesión activa
        $idMesero = $_SESSION['user']['id_usuario'] ?? null;
        if (!$idMesero) return $this->jsonError("Sesión caducada. Vuelve a iniciar.");

        // ✅ Obtener datos del formulario
        $mesa = $_POST['mesa'] ?? null;
        $platos = $_POST['plato'] ?? [];
        $ordenes = $_POST['orden_entrega'] ?? [];

        // ✅ Validación básica
        if (!$mesa || empty($platos)) return $this->jsonError("Datos incompletos al guardar la orden.");

        // ✅ Guardar orden en la base de datos
        $exito = $this->modelo->guardarOrden($idMesero, $mesa, $platos, $ordenes);
        $this->jsonResponse($exito, "Orden creada exitosamente.", "No se pudo guardar la orden.");
    }

    public function confirmarOrdenAjax() {
        $this->json();

        $idPedido = $_POST['id_pedido'] ?? null;
        $cantidades = $_POST['cantidad'] ?? [];
        $ordenes = $_POST['orden_entrega'] ?? [];

        if (!$idPedido) return $this->jsonError("Pedido no válido.");

        $exito = $this->modelo->confirmarOrden($idPedido, $cantidades, $ordenes);
        $this->jsonResponse($exito, "Orden actualizada correctamente.", "Error al actualizar la orden.");
    }

    public function finalizarOrdenAjax() {
        $this->json();
        $idPedido = $_POST['id_pedido'] ?? null;

        if (!$idPedido) return $this->jsonError("Pedido no especificado.");

        try {
            $exito = $this->modelo->finalizarOrden((int)$idPedido);

            if ($exito) {
                // Calcular total y registrar ingreso
                $db = \App\Core\BaseDatos::getInstancia();
                $stmt = $db->prepare("SELECT SUM(pl.precio * dp.cantidad) AS total
                                    FROM detallepedido dp
                                    JOIN platos pl ON dp.id_plato = pl.id_plato
                                    WHERE dp.id_pedido = :id");
                $stmt->execute([':id' => $idPedido]);
                $pedido = $stmt->fetch();
                $totalPedido = $pedido['total'] ?? 0;

                $insert = $db->prepare("INSERT INTO ingresos (fuente, monto, descripcion, fecha)
                                        VALUES ('Venta', :monto, 'Ingreso por pedido confirmado', NOW())");
                $insert->execute([':monto' => $totalPedido]);

                return $this->jsonResponse(true, "✅ Pedido finalizado correctamente.", "");
            } else {
                return $this->jsonError("⚠️ No se pudo finalizar el pedido.");
            }
        } catch (\Exception $e) {
            return $this->jsonError("❌ Error inesperado: " . $e->getMessage());
        }
    }



    // Helpers para respuestas JSON
    private function json() {
        header('Content-Type: application/json');
    }

    private function jsonError($msg) {
        echo json_encode(['success' => false, 'message' => $msg]);
        exit;
    }

    private function jsonResponse($success, $msgOk, $msgFail) {
        echo json_encode([
            'success' => $success,
            'message' => $success ? $msgOk : $msgFail
        ]);
        exit;
    }
}

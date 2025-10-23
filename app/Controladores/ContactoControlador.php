<?php

namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\Sesion;
use App\Modelos\ContactoModel;

class ContactoControlador extends ControladorBase
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new ContactoModel();
    }

    public function index()
    {   if ($this->tieneNivel([2, 3, 4, 5, 6])) return; // bloquea cocina, mesero, rrhh, inventario, finanzas
        // Renderiza la vista principal de contacto
        $this->vista('contacto/index');
    }

    public function enviar()
    {
        // Valida método POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Valida token CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!Sesion::verificarCsrf($token)) {
                die('Solicitud no autorizada.');
            }

            // Sanitiza entradas
            $nombre = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_NUMBER_INT);
            $asunto = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $mensaje = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validaciones básicas
            if (!$nombre || !$email || !$telefono || !$asunto || !$mensaje) {
                die('Por favor, completa todos los campos correctamente.');
            }

            // Guarda o envía
            $resultado = $this->modelo->guardarContacto($nombre, $email, $telefono, $asunto, $mensaje);

            if ($resultado) {
                header('Location: ' . BASE_URL . 'contacto?success=1');
                exit;
            } else {
                die('Ocurrió un error al enviar tu mensaje. Intenta nuevamente.');
            }
        } else {
            die('Método no permitido.');
        }
    }
}
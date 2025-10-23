<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\Sesion;
use App\Modelos\UsuariosModel;

class AuthControlador extends ControladorBase {
    private $modelo;

    public function __construct() {
        Sesion::iniciar();
        $this->modelo = new UsuariosModel();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $contrasena = $_POST['password'] ?? '';

            if ($correo === '' || $contrasena === '') {
                $_SESSION['error'] = "Correo y contraseña requeridos.";
                return $this->redirect(BASE_URL . "login");
            }

            $usuario = $this->modelo->verificarLogin($correo, $contrasena);

            if ($usuario) {
                $_SESSION['usuario'] = $usuario;

                if ($usuario['rol'] == 2) {
                    $this->redirigirTrabajador($usuario['nivel']);
                } else {
                    $this->redirect(BASE_URL . "admin/dashboard");
                }
            } else {
                $_SESSION['error'] = "Credenciales inválidas.";
                $this->redirect(BASE_URL . "login");
            }
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect(BASE_URL . "login");
    }

    private function redirigirTrabajador($nivel) {
        $mapa = [
            1 => 'admin/dashboard',
            2 => 'rrhh/inicio',
            3 => 'cocina/ordenes',
            4 => 'mesero/pedidos',
            5 => 'inventario/insumos',
            6 => 'finanzas/movimientos'
        ];

        $ruta = $mapa[$nivel] ?? 'trabajadores';
        $this->redirect(BASE_URL . $ruta);
    }

    public function index() {
        $this->vista('auth/login');
    }
}

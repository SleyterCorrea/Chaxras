<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\Sesion;

class TrabajadoresControlador extends ControladorBase {

    public function index() {
        Sesion::iniciar();
        $usuario = $_SESSION['usuario'] ?? null;

        if (!$usuario || ($usuario['id_rol'] ?? '') != 2) {
            $_SESSION['error'] = "Acceso no autorizado.";
            return $this->redirect(BASE_URL . "login");
        }

        $nivel = $usuario['nivel'] ?? 'sin nivel';

        // Puedes cargar datos adicionales para el dashboard si deseas
        $this->vista('trabajadores/dashboard', [
            'nivel' => $nivel,
            'usuario' => $usuario
        ]);
    }
}

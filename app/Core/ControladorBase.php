<?php
namespace App\Core;

use App\Core\Sesion; 

class ControladorBase {
    public function __construct() {
        // Arrancamos la sesión y garantizamos CSRF token
        Sesion::iniciar();
    }
protected function tieneNivel(array $niveles): bool
{
    if (!isset($_SESSION['user']['nivel'])) return false;
    if (in_array($_SESSION['user']['nivel'], $niveles)) {
        // Redirige o muestra error si quieres
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
    return false;
}

    protected function vista(string $ruta, array $datos = []) {
        // Hacemos disponibles las variables
        extract($datos);
        // Incluimos la plantilla
        require_once dirname(__DIR__) . "/Vistas/$ruta.php";
    }

    protected function redirect(string $url) {
    header("Location: " . $url);
    exit;
}

protected function verificarAcceso(array $nivelesPermitidos = []) {
    if (!isset($_SESSION['user']['id_nivel'])) {
        $this->mostrarErrorAcceso(); return false;
    }

    $nivelUsuario = (int) $_SESSION['user']['id_nivel'];

    if (!in_array($nivelUsuario, $nivelesPermitidos)) {
        $this->mostrarErrorAcceso(); return false;
    }

    return true;
}

protected function mostrarErrorAcceso() {
    http_response_code(403);
    echo "<div style='
        font-family: Segoe UI, sans-serif;
        background: #fcebea;
        color: #cc1f1a;
        padding: 30px;
        text-align: center;
        border-radius: 8px;
        margin: 100px auto;
        max-width: 500px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    '>
        <h2>🚫 Acceso Denegado</h2>
        <p>No tienes permiso para acceder a esta sección.</p>
        <a href='" . BASE_URL . "login' style='
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #cc1f1a;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        '>Volver al inicio</a>
    </div>";
    exit;
}


}
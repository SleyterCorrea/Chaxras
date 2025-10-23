<?php
namespace App\Core;

class VistaBase {
    public static function escape(string $texto): string {
        return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    }
    // Si deseas cargar plantillas parciales:
    public static function renderPartial(string $path, array $datos = []) {
        extract($datos);
        require dirname(__DIR__) . "/Vistas/$path.php";
    }
}
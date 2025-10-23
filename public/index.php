<?php
// Archivo index.php (entrada frontal del sistema)

// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Configuración del sistema
require_once __DIR__ . '/../app/Config/Config.php';
require_once __DIR__ . '/../app/Config/Helpers.php';

// Definir la BASE_URL global
define('BASE_URL', \App\Config\BASE_URL);

// Usar clases principales
use App\Core\Sesion;
use App\Core\Enrutador;

// Iniciar sesión PHP
Sesion::iniciar();

// Despachar la ruta usando el Enrutador
$enrutador = new Enrutador();
$enrutador->despachar();

<?php
namespace App\Core;
use PDO, PDOException;

class BaseDatos {
    private static $instancia = null;
    private $conexion;

    private function __construct() {
        // Referencia las constantes con namespace:
        $host   = \App\Config\DB_HOST;
        $dbname = \App\Config\DB_NAME;
        $user   = \App\Config\DB_USER;
        $pass   = \App\Config\DB_PASS;
        try {
            $this->conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            // ...
        } catch (PDOException $e) {
            die("Error en conexión: " . $e->getMessage());
        }
        $this->conexion = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public static function getInstancia(): PDO {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia->conexion;
    }
}
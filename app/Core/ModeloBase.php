<?php
namespace App\Core;
use App\Core\BaseDatos;
use PDO;

abstract class ModeloBase {
    /** @var mysqli */
    protected $db;
    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }
    // Métodos auxiliares, p.ej. iniciar transacción:
    protected function beginTransaction() {
        $this->db->beginTransaction();
    }
    protected function commit() {
        $this->db->commit();
    }
    protected function rollBack() {
        $this->db->rollBack();
    }
    // Puedes añadir manejo de errores comunes, logging, etc.
}
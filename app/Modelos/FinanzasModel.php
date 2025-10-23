<?php
namespace App\Modelos;

use App\Core\BaseDatos;

class FinanzasModel {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    public function filtrarIngresos($desde, $hasta) {
        if ($desde && $hasta) {
            $stmt = $this->db->prepare("SELECT * FROM ingresos WHERE fecha BETWEEN :desde AND :hasta ORDER BY fecha DESC");
            $stmt->execute([
                ':desde' => $desde . ' 00:00:00',
                ':hasta' => $hasta . ' 23:59:59'
            ]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this->db->query("SELECT * FROM ingresos ORDER BY fecha DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function filtrarEgresos($desde, $hasta) {
        if ($desde && $hasta) {
            $stmt = $this->db->prepare("SELECT * FROM egresos WHERE fecha BETWEEN :desde AND :hasta ORDER BY fecha DESC");
            $stmt->execute([
                ':desde' => $desde . ' 00:00:00',
                ':hasta' => $hasta . ' 23:59:59'
            ]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $this->db->query("SELECT * FROM egresos ORDER BY fecha DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function crearIngresoManual($datos) {
    $sql = "INSERT INTO ingresos (fuente, monto, descripcion, fecha) VALUES (:fuente, :monto, :descripcion, :fecha)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'fuente' => $datos['fuente'],
        'monto' => $datos['monto'],
        'descripcion' => $datos['descripcion'],
        'fecha' => $datos['fecha']
    ]);
}

public function crearEgresoManual($datos) {
    $sql = "INSERT INTO egresos (motivo, monto, fecha) VALUES (:motivo, :monto, :fecha)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'motivo' => $datos['motivo'],
        'monto' => $datos['monto'],
        'fecha' => $datos['fecha']
    ]);
}

}

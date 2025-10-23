<?php
namespace App\Modelos;

use PDO;
use App\Core\BaseDatos;

class OrdenModel {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    public function getDb() {
        return $this->db;
    }

    public function obtenerOrdenesCocina(): array {
        $sql = "SELECT p.id_pedido, p.fecha, p.estado, 
                       m.nombre AS nombre_mesa, 
                       u.nombre AS mesero
                FROM pedidos p
                LEFT JOIN mesas m ON p.id_mesa = m.id_mesa
                LEFT JOIN usuarios u ON p.id_trabajador = u.id_usuario
                WHERE p.estado IN ('pendiente', 'en preparación')
                ORDER BY p.fecha DESC";

        $stmt = $this->db->query($sql);
        $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        foreach ($ordenes as &$orden) {
            $orden['detalle'] = $this->obtenerDetallesPorPedido($orden['id_pedido']);
            $orden['total'] = array_reduce($orden['detalle'], function($carry, $item) {
                return $carry + ($item['cantidad'] * $item['precio_unitario']);
            }, 0);
        }

        return $ordenes;
    }

    public function obtenerDetallesPorPedido(int $idPedido): array {
        $sql = "SELECT dp.*, 
                       p.nombre AS plato, 
                       t.nombre AS tipo
                FROM detallepedido dp
                JOIN platos p ON dp.id_plato = p.id_plato
                JOIN tipo_plato t ON p.id_tipo_plato = t.id_tipo_plato
                WHERE dp.id_pedido = :id
                ORDER BY dp.orden_entrega ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $idPedido, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPedidoPorId(int $id): ?array {
        $sql = "SELECT p.*, 
                       m.nombre AS mesa, 
                       u.nombre AS mesero
                FROM pedidos p
                LEFT JOIN mesas m ON p.id_mesa = m.id_mesa
                LEFT JOIN usuarios u ON p.id_trabajador = u.id_usuario
                WHERE p.id_pedido = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) return null;

        $pedido['detalles'] = $this->obtenerDetallesPorPedido($id);
        return $pedido;
    }

    public function actualizarEstadoPedido(int $idPedido, string $estado): bool {
        $sql = "UPDATE pedidos SET estado = :estado WHERE id_pedido = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idPedido, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function actualizarEstadoDetalle(int $idDetalle, string $estado): bool {
        $sql = "UPDATE detallepedido SET estado = :estado WHERE id_detalle = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':id', $idDetalle, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

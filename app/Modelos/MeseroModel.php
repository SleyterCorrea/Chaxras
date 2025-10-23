<?php
namespace App\Modelos;

use PDO;
use App\Core\BaseDatos;

class MeseroModel {
    private $db;

    public function __construct() {
        $this->db = BaseDatos::getInstancia();
    }

    public function getDb() {
        return $this->db;
    }

    public function obtenerMesas(): array {
        $sql = "SELECT id_mesa, nombre FROM mesas ORDER BY id_mesa";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerMesasDisponibles(): array {
        $sql = "SELECT id_mesa, nombre FROM mesas WHERE estado = 'disponible' ORDER BY nombre";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPlatosActivos(): array {
        $sql = "SELECT p.*, tp.nombre AS categoria
                FROM platos p
                JOIN tipo_plato tp ON p.id_tipo_plato = tp.id_tipo_plato
                WHERE p.estado = 'activo'
                ORDER BY tp.nombre, p.nombre";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function obtenerPedidosActivos(int $idMesero): array {
        $sql = "SELECT p.id_pedido, p.fecha, p.estado, p.id_mesa,
                       m.nombre AS nombre_mesa,
                       SUM(dp.cantidad * dp.precio_unitario) AS total
                FROM pedidos p
                JOIN mesas m ON p.id_mesa = m.id_mesa
                JOIN detallepedido dp ON p.id_pedido = dp.id_pedido
                WHERE p.estado != 'finalizado' AND p.id_trabajador = :idMesero
                GROUP BY p.id_pedido, p.id_mesa, m.nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idMesero' => $idMesero]);

        $ordenes_por_mesa = [];
        while ($orden = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ordenes_por_mesa[$orden['id_mesa']] = $orden;
        }
        return $ordenes_por_mesa;
    }

    public function obtenerEstadosDetalle(): array {
        $sql = "SELECT id_pedido, estado FROM detallepedido";
        $stmt = $this->db->query($sql);
        $estados = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estados[$row['id_pedido']][] = $row['estado'];
        }
        return $estados;
    }

    public function obtenerPedidoPorIdMesero(int $idPedido, int $idMesero): ?array {
        $sql = "SELECT p.*, m.nombre AS mesa
                FROM pedidos p
                LEFT JOIN mesas m ON p.id_mesa = m.id_mesa
                WHERE p.id_pedido = :idPedido AND p.id_trabajador = :idMesero
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':idPedido' => $idPedido,
            ':idMesero' => $idMesero
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerDetallePedido(int $idPedido): array {
        $sql = "SELECT dp.id_detalle, dp.id_plato, dp.cantidad, dp.estado, dp.orden_entrega,
                       pl.nombre, pl.precio, tp.nombre AS tipo
                FROM detallepedido dp
                JOIN platos pl ON dp.id_plato = pl.id_plato
                JOIN tipo_plato tp ON pl.id_tipo_plato = tp.id_tipo_plato
                WHERE dp.id_pedido = :idPedido
                ORDER BY dp.orden_entrega";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idPedido' => $idPedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function guardarOrden(int $idMesero, int $idMesa, array $platos, array $ordenes): bool {
        try {
            $this->db->beginTransaction();

            // Insertar pedido principal
            $stmtPedido = $this->db->prepare(
                "INSERT INTO pedidos (id_mesa, id_trabajador, fecha, estado)
                VALUES (:mesa, :mesero, NOW(), 'pendiente')"
            );
            $stmtPedido->execute([
                ':mesa' => $idMesa,
                ':mesero' => $idMesero
            ]);

            $idPedido = $this->db->lastInsertId();
            if (!$idPedido) {
                throw new \Exception("No se pudo obtener el ID del nuevo pedido.");
            }

            foreach ($platos as $idPlato => $cantidad) {
                if ($cantidad > 0) {
                    $ordenEntrega = $ordenes[$idPlato] ?? 1;

                    // Obtener precio del plato
                    $stmtPrecio = $this->db->prepare("SELECT precio FROM platos WHERE id_plato = :id");
                    $stmtPrecio->execute([':id' => $idPlato]);
                    $precioUnitario = $stmtPrecio->fetchColumn();

                    if ($precioUnitario === false) {
                        throw new \Exception("No se encontró precio para el plato ID $idPlato.");
                    }

                    // Insertar en detallepedido
                    $stmtDetalle = $this->db->prepare(
                        "INSERT INTO detallepedido
                        (id_pedido, id_plato, cantidad, precio_unitario, estado, orden_entrega)
                        VALUES (:pedido, :plato, :cantidad, :precio, 'pendiente', :orden)"
                    );
                    $stmtDetalle->execute([
                        ':pedido' => $idPedido,
                        ':plato' => $idPlato,
                        ':cantidad' => $cantidad,
                        ':precio' => $precioUnitario,
                        ':orden' => $ordenEntrega
                    ]);
                }
            }

            // Marcar mesa como ocupada
            $stmtUpdateMesa = $this->db->prepare("UPDATE mesas SET estado = 'ocupada' WHERE id_mesa = :mesa");
            $stmtUpdateMesa->execute([':mesa' => $idMesa]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // Si ocurre algún error, se revierte todo
            $this->db->rollBack();

            // Opcional: guardar en log
            error_log("Error al guardar orden: " . $e->getMessage());

            return false;
        }
    }

public function finalizarOrden(int $idPedido): bool {
    try {
        $this->db->beginTransaction();

        // 1. Verificar si el pedido existe y obtener id_mesa
        $stmt = $this->db->prepare("SELECT id_mesa FROM pedidos WHERE id_pedido = :id");
        $stmt->execute([':id' => $idPedido]);
        $idMesa = $stmt->fetchColumn();

        if (!$idMesa) {
            // Si no hay mesa asociada, se cancela la operación
            $this->db->rollBack();
            return false;
        }

        // 2. Marcar el pedido como finalizado
        $this->db->prepare(
            "UPDATE pedidos SET estado = 'finalizado' WHERE id_pedido = :pedido"
        )->execute([':pedido' => $idPedido]);

        // 3. Liberar la mesa (marcar como disponible)
        $this->db->prepare(
            "UPDATE mesas SET estado = 'disponible' WHERE id_mesa = :mesa"
        )->execute([':mesa' => $idMesa]);

        $this->db->commit();
        return true;

    } catch (\Exception $e) {
        $this->db->rollBack();
        return false;
    }
}


    public function confirmarOrden(int $idPedido, array $cantidades, array $ordenes): bool {
        try {
            $this->db->beginTransaction();
            foreach ($cantidades as $idDetalle => $cantidad) {
                $ordenEntrega = $ordenes[$idDetalle] ?? 1;
                $this->db->prepare(
                    "UPDATE detallepedido
                     SET cantidad = :cantidad, orden_entrega = :orden
                     WHERE id_detalle = :idDetalle AND id_pedido = :idPedido"
                )->execute([
                    ':cantidad' => $cantidad,
                    ':orden' => $ordenEntrega,
                    ':idDetalle' => $idDetalle,
                    ':idPedido' => $idPedido
                ]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function obtenerDetallesParaPreparar(array $ids, array $cantidades): array {
        if (empty($ids)) return [];
        $in = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT p.id_plato, p.nombre, p.precio, tp.nombre AS tipo
                FROM platos p
                JOIN tipo_plato tp ON p.id_tipo_plato = tp.id_tipo_plato
                WHERE p.id_plato IN ($in)";
        $stmt = $this->db->prepare($sql);
        foreach ($ids as $k => $id) {
            $stmt->bindValue($k + 1, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($datos as &$d) {
            $d['cantidad'] = $cantidades[$d['id_plato']] ?? 1;
            $d['orden_entrega'] = 1;
        }
        return $datos;
    }

    public function obtenerMesasConTotales(): array {
        $sql = "SELECT m.id_mesa, m.nombre,
                       COALESCE((
                           SELECT SUM(dp.cantidad * pl.precio)
                           FROM pedidos p
                           JOIN detallepedido dp ON p.id_pedido = dp.id_pedido
                           JOIN platos pl ON dp.id_plato = pl.id_plato
                           WHERE p.id_mesa = m.id_mesa
                             AND p.estado != 'finalizado'
                       ), 0) AS total
                FROM mesas m
                ORDER BY m.id_mesa";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}

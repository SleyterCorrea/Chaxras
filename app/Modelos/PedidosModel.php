<?php
namespace App\Modelos;

use App\Core\ModeloBase;

class PedidosModel extends ModeloBase {

    public function obtenerPlatos() {
        return $this->db->query("SELECT * FROM platos")->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerMesas() {
        return $this->db->query("SELECT * FROM mesas")->fetch_all(MYSQLI_ASSOC);
    }

    public function crearPedido($data) {
        // lógica simplificada
        $stmt = $this->db->prepare("INSERT INTO pedidos (id_mesa, fecha) VALUES (?, NOW())");
        $stmt->bind_param("i", $data['id_mesa']);
        $stmt->execute();
        $id_pedido = $this->db->insert_id;
        // guardar detalles
        foreach ($data['platos'] as $plato) {
            $det = $this->db->prepare("INSERT INTO detallepedido (id_pedido, id_plato, cantidad) VALUES (?, ?, ?)");
            $det->bind_param("iii", $id_pedido, $plato['id_plato'], $plato['cantidad']);
            $det->execute();
        }
    }

    public function obtenerOrdenesPorUsuario($id_usuario) {
        return $this->db->query("SELECT * FROM pedidos WHERE id_usuario = $id_usuario")
            ->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerOrdenesCocina() {
        return $this->db->query("SELECT * FROM pedidos")->fetch_all(MYSQLI_ASSOC);
    }

    public function cambiarEstadoDetalle($id, $estado) {
        $stmt = $this->db->prepare("UPDATE detallepedido SET estado = ? WHERE id_detalle = ?");
        $stmt->bind_param("si", $estado, $id);
        $stmt->execute();
    }
}

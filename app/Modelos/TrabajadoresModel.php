<?php
namespace App\Modelos;

use App\Core\ModeloBase;

class TrabajadoresModel extends ModeloBase {

    public function listar() {
        return $this->db->query("SELECT * FROM usuarios WHERE id_rol = 2")->fetch_all(MYSQLI_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function crear($data) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, correo, contrasena, id_rol, id_nivel) VALUES (?, ?, ?, 2, ?)");
        $stmt->bind_param("sssi", $data['nombre'], $data['correo'], password_hash($data['contrasena'], PASSWORD_BCRYPT), $data['id_nivel']);
        return $stmt->execute();
    }

    public function actualizar($data) {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre=?, correo=?, id_nivel=? WHERE id_usuario=?");
        $stmt->bind_param("ssii", $data['nombre'], $data['correo'], $data['id_nivel'], $data['id_usuario']);
        return $stmt->execute();
    }

    public function desactivar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

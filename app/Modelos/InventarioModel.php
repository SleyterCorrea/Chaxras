<?php
namespace App\Modelos;

use App\Core\ModeloBase;

class InventarioModel extends ModeloBase {

    /** =======================
     * Insumos
     * ======================= */
    public function listarInsumos() {
        return $this->db->query("SELECT * FROM insumos ORDER BY nombre ASC")->fetchAll();
    }

    public function crearInsumo($nombre, $unidad) {
        $stmt = $this->db->prepare("INSERT INTO insumos (nombre, unidad) VALUES (?, ?)");
        $stmt->execute([$nombre, $unidad]);
    }

    public function obtenerInsumo($id) {
        $stmt = $this->db->prepare("SELECT * FROM insumos WHERE id_insumo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function actualizarInsumo($id, $nombre, $unidad, $estado) {
        $stmt = $this->db->prepare("UPDATE insumos SET nombre = ?, unidad = ?, estado = ? WHERE id_insumo = ?");
        $stmt->execute([$nombre, $unidad, $estado, $id]);
    }

    public function eliminar_insumo($id) {
        $stmt = $this->db->prepare("DELETE FROM insumos WHERE id_insumo = ?");
        $stmt->execute([$id]);
    }

    /** =======================
     * Lotes de Insumo
     * ======================= */
    public function listarLotes() {
        return $this->db->query("
            SELECT l.*, i.nombre AS insumo
            FROM lotes_insumo l
            JOIN insumos i ON i.id_insumo = l.id_insumo
            ORDER BY l.fecha_ingreso DESC
        ")->fetchAll();
    }

    public function listarLotesPorMes($mes, $anio) {
        $sql = "
            SELECT l.*, i.nombre AS insumo
            FROM lotes_insumo l
            JOIN insumos i ON i.id_insumo = l.id_insumo
            WHERE MONTH(l.fecha_ingreso) = ? AND YEAR(l.fecha_ingreso) = ?
            ORDER BY l.fecha_ingreso DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$mes, $anio]);
        return $stmt->fetchAll();
    }

    public function registrarLote($data) {
        // 1. Insertar lote
        $this->db->prepare("INSERT INTO lotes_insumo 
            (id_insumo, cantidad, fecha_ingreso, fecha_estimado_termino, costo_total)
            VALUES (?, ?, ?, ?, ?)")
            ->execute([
                $data['id_insumo'],
                $data['cantidad'],
                $data['fecha_ingreso'],
                $data['fecha_estimado_termino'],
                $data['costo_total']
            ]);

        // 2. Obtener nombre del insumo
        $stmt = $this->db->prepare("SELECT nombre FROM insumos WHERE id_insumo = ?");
        $stmt->execute([$data['id_insumo']]);
        $insumo = $stmt->fetch();
        $nombre_insumo = $insumo ? $insumo['nombre'] : 'Desconocido';

        // 3. Insertar egreso automáticamente
        $motivo = 'Compra de insumo: ' . $nombre_insumo;
        $this->db->prepare("INSERT INTO egresos (motivo, monto, fecha) VALUES (?, ?, NOW())")
            ->execute([$motivo, $data['costo_total']]);
    }
}

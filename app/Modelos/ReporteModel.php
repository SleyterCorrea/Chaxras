<?php
namespace App\Modelos;

use App\Core\ModeloBase;

class ReporteModel extends ModeloBase {

    public function contarTrabajadores() {
        return $this->db->query("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 2")
            ->fetch()['total'] ?? 0;
    }

    public function ultimoTrabajador() {
        return $this->db->query("SELECT nombre FROM usuarios WHERE id_rol = 2 ORDER BY id_usuario DESC LIMIT 1")
            ->fetch()['nombre'] ?? 'N/A';
    }

    public function salidasInsumoHoy() {
        return $this->db->query("SELECT COUNT(*) as total FROM movimientoinsumos WHERE tipo = 'salida' AND DATE(fecha) = CURDATE()")
            ->fetch()['total'] ?? 0;
    }

    public function pedidosHoy() {
        return $this->db->query("SELECT COUNT(*) as total FROM pedidos WHERE DATE(fecha) = CURDATE()")
            ->fetch()['total'] ?? 0;
    }

    public function estadoPedidos() {
        return $this->db->query("SELECT estado, COUNT(*) as total FROM pedidos GROUP BY estado")
            ->fetchAll();
    }

    public function distribucionTrabajadores() {
        return $this->db->query("
            SELECT niveles.nombre as nivel, COUNT(*) as total 
            FROM usuarios 
            JOIN niveles ON usuarios.id_nivel = niveles.id_nivel 
            WHERE usuarios.id_rol = 2
            GROUP BY niveles.nombre
        ")->fetchAll();
    }

        public function lotesHoy()
    {
        $sql = "SELECT COUNT(*) as total FROM lotes_insumo WHERE DATE(fecha_ingreso) = CURDATE()";
        return $this->db->query($sql)->fetch()['total'];
    }


    public function ingresosVsEgresosMes() {
        return $this->db->query("
            SELECT 
                (SELECT IFNULL(SUM(monto),0) FROM ingresos WHERE MONTH(fecha) = MONTH(CURDATE())) as ingresos,
                (SELECT IFNULL(SUM(monto),0) FROM egresos WHERE MONTH(fecha) = MONTH(CURDATE())) as egresos
        ")->fetch();
    }

    public function ingresosVsEgresosMesUltimos($limite = 3) {
        $sql = "
            SELECT 'Ingreso' AS tipo, monto, fecha, descripcion AS detalle FROM ingresos
            UNION
            SELECT 'Egreso' AS tipo, monto, fecha, motivo AS detalle FROM egresos
            ORDER BY fecha DESC
            LIMIT {$limite}
        ";
        return $this->db->query($sql)->fetchAll() ?? [];
    }

    public function ingresosEgresosPorDiaMes() {
        $dias = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $datos = [];

        for ($i = 1; $i <= $dias; $i++) {
            $fecha = date("Y-m") . "-" . str_pad($i, 2, "0", STR_PAD_LEFT);

            $ingresos = $this->db->query("SELECT IFNULL(SUM(monto),0) AS total FROM ingresos WHERE DATE(fecha) = '$fecha'")
                                ->fetch()['total'] ?? 0;

            $egresos = $this->db->query("SELECT IFNULL(SUM(monto),0) AS total FROM egresos WHERE DATE(fecha) = '$fecha'")
                                ->fetch()['total'] ?? 0;

            $datos[] = [
                'dia' => str_pad($i, 2, "0", STR_PAD_LEFT),
                'ingreso' => (float)$ingresos,
                'egreso'  => (float)$egresos
            ];
        }
        return $datos;
    }
}

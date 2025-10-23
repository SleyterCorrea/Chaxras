<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Modelos\Reporte; // Usa 'Reporte' si el archivo se llama Reporte.php
use App\Core\Sesion;
use App\Modelos\ReporteModel;

class AdminControlador extends ControladorBase {
    private $reporteModel;

    public function __construct() {
        Sesion::iniciar();
        $this->reporteModel = new ReporteModel(); // Instancia del modelo correcto
    }

    public function index() {
        if (!$this->verificarAcceso([1])) return;

        $id_nivel = $_SESSION['user']['id_nivel'] ?? null;
        $nombre_usuario = $_SESSION['user']['nombre'] ?? 'Invitado';

        $datos = [
            'id_nivel'              => $id_nivel,
            'nombre_usuario'        => $nombre_usuario,
            'total_trabajadores'    => $this->reporteModel->contarTrabajadores(),
            'ultimo_ingresado'      => $this->reporteModel->ultimoTrabajador(),
            'lotesHoy' => $this->reporteModel->lotesHoy(),
            'pedidos_dia'           => $this->reporteModel->pedidosHoy(),
            'estado_pedidos'        => $this->reporteModel->estadoPedidos(),
            'distribucion_niveles'  => $this->reporteModel->distribucionTrabajadores(),
            'ingresos_egresos'      => $this->reporteModel->ingresosVsEgresosMesUltimos(3),
            'ingresos_egresos_mes'  => $this->reporteModel->ingresosEgresosPorDiaMes()
        ];

        $this->vista('admin/dashboard', $datos);
    }
}

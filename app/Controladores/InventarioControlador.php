<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\Sesion;
use App\Modelos\InventarioModel;

class InventarioControlador extends ControladorBase {
    private $modelo;

    public function __construct() {
        Sesion::iniciar();
        $this->modelo = new InventarioModel();
    }

    public function index() {
        if (!$this->verificarAcceso([1,5])) return;
        $insumos = $this->modelo->listarInsumos();
        $this->vista('inventario/insumos', compact('insumos'));
    }

    public function crear_insumo() {
        if (!$this->verificarAcceso([1,5])) return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $unidad = trim($_POST['unidad']);
            $this->modelo->crearInsumo($nombre, $unidad);
            header("Location: " . BASE_URL . "inventario");
            exit;
        }

        $this->vista('inventario/crear_insumo');
    }

    public function editar_insumo($id) {
        if (!$this->verificarAcceso([1,5])) return;

        $insumo = $this->modelo->obtenerInsumo($id);
        if (!$insumo) {
            echo "<p style='color:red;'>Insumo no encontrado.</p>";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $unidad = trim($_POST['unidad']);
            $estado = $_POST['estado'] ?? 'activo';
            $this->modelo->actualizarInsumo($id, $nombre, $unidad, $estado);
            header("Location: " . BASE_URL . "inventario");
            exit;
        }

        $this->vista('inventario/editar_insumo', compact('insumo'));
    }

    public function eliminar_insumo($id) {
        if (!$this->verificarAcceso([1,5])) return;

        $this->modelo->eliminar_insumo($id);
        header("Location: " . BASE_URL . "inventario");
        exit;
    }

    public function lotes() {
        if (!$this->verificarAcceso([1,5])) return;

        $mes = $_GET['mes'] ?? null;
        $anio = $_GET['anio'] ?? null;

        if ($mes && $anio) {
            $lotes = $this->modelo->listarLotesPorMes($mes, $anio);
        } else {
            $lotes = $this->modelo->listarLotes();
        }

        $this->vista('inventario/lotes', compact('lotes', 'mes', 'anio'));
    }

    public function crearLote() {
        if (!$this->verificarAcceso([1,5])) return;

        $insumos = $this->modelo->listarInsumos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_insumo' => $_POST['id_insumo'],
                'cantidad' => $_POST['cantidad'],
                'fecha_ingreso' => $_POST['fecha_ingreso'],
                'fecha_estimado_termino' => $_POST['fecha_estimado_termino'],
                'costo_total' => $_POST['costo_total'],
            ];


            $this->modelo->registrarLote($data);
            header("Location: " . BASE_URL . "inventario/lotes");
            exit;
        }

        $this->vista('inventario/crear_lote', compact('insumos'));
    }
}

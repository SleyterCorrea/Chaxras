<?php
namespace App\Controladores;

use App\Core\ControladorBase;
use App\Core\BaseDatos;
use App\Core\Sesion;



class RrhhControlador extends ControladorBase

{
    private $conexion;

    public function __construct()
    {
        Sesion::iniciar();
        $this->conexion = BaseDatos::getInstancia();
    }

    public function index()
    {
        if (!$this->verificarAcceso([1,2])) return;

        $query = "
            SELECT u.id_usuario, u.nombre, u.correo, r.nombre AS rol, n.nombre AS nivel, u.id_rol, u.id_nivel
            FROM Usuarios u
            JOIN Roles r ON u.id_rol = r.id_rol
            JOIN Niveles n ON u.id_nivel = n.id_nivel
            ORDER BY u.id_usuario DESC
        ";
        $trabajadores = $this->conexion->query($query)->fetchAll();

        include __DIR__ . '/../Vistas/rrhh/trabajadores.php';
    }

    public function crear()
    {        if (!$this->verificarAcceso([1,2])) return;

        $roles = $this->conexion->query("SELECT id_rol, nombre FROM Roles WHERE id_rol != 1")->fetchAll();
        $niveles = $this->conexion->query("SELECT id_nivel, nombre FROM Niveles")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $clave_raw = $_POST['contraseña'] ?? '';
            $clave = password_hash($clave_raw, PASSWORD_BCRYPT);
            $id_rol = (int)($_POST['id_rol'] ?? 2);
            $id_nivel = (int)($_POST['id_nivel'] ?? 2);

            $stmt = $this->conexion->prepare("INSERT INTO Usuarios (nombre, correo, contrasena, id_rol, id_nivel) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $correo, $clave, $id_rol, $id_nivel]);

            header("Location: " . BASE_URL . "rrhh");
            exit;
        }

        include __DIR__ . '/../Vistas/rrhh/crear.php';
    }

    public function editar($id = null)
    {
        if (!$this->verificarAcceso([1, 2])) return;

        if (!$id) {
            echo "ID no válido.";
            exit;
        }

        $stmt = $this->conexion->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
        $stmt->execute([$id]);
        $trabajador = $stmt->fetch();

        $niveles = $this->conexion->query("SELECT id_nivel, nombre FROM Niveles")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $id_nivel = (int)$_POST['id_nivel'];

            // Ya no usamos id_rol
            $stmt = $this->conexion->prepare("UPDATE Usuarios SET nombre = ?, correo = ?, id_nivel = ? WHERE id_usuario = ?");
            $stmt->execute([$nombre, $correo, $id_nivel, $id]);

            header("Location: " . BASE_URL . "rrhh");
            exit;
        }

        include __DIR__ . '/../Vistas/rrhh/editar.php';
    }


    public function eliminar($id = null)
    {

        if ($id) {
            if ($id == 1) {
                echo "<script>alert('No puedes eliminar al administrador principal.'); window.location.href='" . BASE_URL . "rrhh';</script>";
                exit;
            }

            $stmt = $this->conexion->prepare("DELETE FROM Usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
        }

        header("Location: " . BASE_URL . "rrhh");
        exit;
    }
}

<?php
use App\Core\BaseDatos;

$conexion = BaseDatos::getInstancia();

if (session_status() === PHP_SESSION_NONE) session_start();
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
if (!$id_usuario) header('Location: ' . BASE_URL . 'login');

include __DIR__ . '/../layout/sidebar.php';
include __DIR__ . '/../layout/encabezado.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $clave_raw = $_POST['contraseña'];
    $clave = password_hash($clave_raw, PASSWORD_BCRYPT);
    $id_rol = (int)$_POST['id_rol'];
    $id_nivel = (int)$_POST['id_nivel'];

    $stmt = $conexion->prepare("INSERT INTO Usuarios (nombre, correo, contrasena, id_rol, id_nivel) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $correo, $clave, $id_rol, $id_nivel]);

    header("Location: " . BASE_URL . "rrhh");
    exit;
}
?>
<div class="dashboard-wrapper">
    <div class="dashboard">
<a href="<?= BASE_URL ?>rrhh" class="btn-volver">← Volver</a>
        <h2>+ Registrar Nuevo Trabajador</h2>

        <form method="POST" class="formulario">        
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Correo:</label>
            <input type="email" name="correo" required>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required>


            <label>Nivel:</label>
            <select name="id_nivel" required>
                <option value="2">RRHH</option>
                <option value="3">Cocina</option>
                <option value="4">Mesero</option>
                <option value="5">Inventario</option>
                <option value="6">Finanzas</option>
            </select>

            <button type="submit" class="btn-guardar">Guardar</button>
        </form>
    </div>
</div>

<style>
.dashboard-wrapper { margin-left: 274px; padding: 100px 30px; font-family: 'Segoe UI', sans-serif; background: #f5f7fa; min-height: 100vh;}
.dashboard h2 { font-size: 28px; margin-bottom: 25px; color: #104031; text-align: center;}
.formulario { background: #fff; padding: 30px; max-width: 600px; margin: 0 auto; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1);}
.formulario label { display: block; margin-top: 15px; font-weight: bold; font-size: 14px;}
.formulario input, .formulario select { width: 100%; padding: 12px; margin-top: 6px; margin-bottom: 14px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px;}
.btn-guardar { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 14px; cursor: pointer; transition: background 0.3s;}
.btn-guardar:hover { background: #1e7e34;}
.btn-volver {
    display: inline-block;
    background: #6c757d;
    color: white;
    padding: 10px 20px;
    margin-bottom: 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
}
.btn-volver:hover {
    background: #5a6268;
}

</style>

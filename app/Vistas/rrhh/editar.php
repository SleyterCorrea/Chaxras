<?php
use App\Core\BaseDatos;

$conexion = BaseDatos::getInstancia();

if (session_status() === PHP_SESSION_NONE) session_start();
$id_usuario = $_SESSION['user']['id_usuario'] ?? null;
if (!$id_usuario) header('Location: ' . BASE_URL . 'login');

include __DIR__ . '/../layout/sidebar.php';
include __DIR__ . '/../layout/encabezado.php';

// Obtener el ID desde la URL
$partesUrl = explode('/', $_GET['url'] ?? '');
$id = isset($partesUrl[2]) ? (int)$partesUrl[2] : 0;
if ($id <= 0) {
    echo "<div class='mensaje-error'>🚫 ID no válido o faltante en la URL.</div>";
    exit;
}

// Consultar datos del trabajador
$stmt = $conexion->prepare("SELECT * FROM Usuarios WHERE id_usuario = ?");
$stmt->execute([$id]);
$trabajador = $stmt->fetch();

if (!$trabajador) {
    echo "<div class='mensaje-error'>🚫 Trabajador no encontrado en la base de datos.</div>";
    exit;
}

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $id_rol = isset($_POST['id_rol']) ? (int)$_POST['id_rol'] : 0;
    $id_nivel = isset($_POST['id_nivel']) ? (int)$_POST['id_nivel'] : 0;

    if ($id_rol > 0 && $id_nivel > 0) {
        $stmt = $conexion->prepare("UPDATE Usuarios SET nombre = ?, correo = ?, id_rol = ?, id_nivel = ? WHERE id_usuario = ?");
        $stmt->execute([$nombre, $correo, $id_rol, $id_nivel, $id]);
        header("Location: " . BASE_URL . "rrhh");
        exit;
    } else {
        echo "<div class='mensaje-error'>🚫 Faltan campos obligatorios.</div>";
    }
}
?>

<div class="dashboard-wrapper">
    <a href="<?= BASE_URL ?>rrhh" class="btn-volver">← Volver</a>
    <div class="dashboard centrado">
        <h2>✏️ Editar Trabajador</h2>

        <form method="POST" class="formulario-editar">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($trabajador['nombre']) ?>" required>

            <label>Correo:</label>
            <input type="email" name="correo" value="<?= htmlspecialchars($trabajador['correo']) ?>" required>

            <label>Nivel:</label>
            <select name="id_nivel" required>
                <option value="2" <?= $trabajador['id_nivel'] == 2 ? 'selected' : '' ?>>RRHH</option>
                <option value="3" <?= $trabajador['id_nivel'] == 3 ? 'selected' : '' ?>>Cocina</option>
                <option value="4" <?= $trabajador['id_nivel'] == 4 ? 'selected' : '' ?>>Mesero</option>
                <option value="5" <?= $trabajador['id_nivel'] == 5 ? 'selected' : '' ?>>Inventario</option>
                <option value="6" <?= $trabajador['id_nivel'] == 6 ? 'selected' : '' ?>>Finanzas</option>
            </select>

            <button type="submit" class="btn-guardar">Guardar Cambios</button>
        </form>
    </div>
</div>

<style>
.dashboard-wrapper {
    margin-left: 274px;
    padding: 80px 30px;
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fa;
    min-height: 100vh;
}
.dashboard.centrado {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.dashboard h2 {
    font-size: 30px;
    margin-bottom: 30px;
    color: #104031;
    text-align: center;
}
.formulario-editar {
    background: #fff;
    padding: 35px 40px;
    width: 100%;
    max-width: 550px;
    border-radius: 12px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.12);
}
.formulario-editar label {
    display: block;
    margin-top: 18px;
    font-weight: 600;
    font-size: 15px;
}
.formulario-editar input, .formulario-editar select {
    width: 100%;
    padding: 13px;
    margin-top: 8px;
    margin-bottom: 16px;
    border: 1px solid #ccc;
    border-radius: 7px;
    font-size: 15px;
    transition: border 0.3s;
}
.formulario-editar input:focus, .formulario-editar select:focus {
    border-color: #007bff;
    outline: none;
}
.btn-guardar {
    background: #007bff;
    color: white;
    padding: 14px 28px;
    border: none;
    border-radius: 7px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s;
    width: 100%;
}
.btn-guardar:hover {
    background: #0056b3;
}
.mensaje-error {
    margin-left: 274px;
    padding: 100px 40px;
    color: #c00;
    font-size: 20px;
    font-family: 'Segoe UI', sans-serif;
}
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

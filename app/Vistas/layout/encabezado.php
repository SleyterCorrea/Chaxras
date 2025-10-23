<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$usuario = $_SESSION['usuario'] ?? ['nombre' => 'Usuario', 'nivel' => '---'];

$nombre = htmlspecialchars($usuario['nombre']);
$nivel = strtoupper($usuario['nivel']);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<header class="topbar">
    <div class="topbar-left">
        <button id="menu-toggle" aria-label="Abrir menú"><i class="fas fa-bars"></i></button>
    </div>
    <div class="topbar-right">
        <div class="user-info">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($nombre) ?>&background=0D8ABC&color=fff" alt="Avatar">
            <div class="user-text">
                <strong><?= $nombre ?></strong><br>
                <small><?= $nivel ?></small>
            </div>
        </div>
        <a class="logout" href="<?= BASE_URL ?>login/logout" title="Cerrar sesión">
            <i class="fas fa-sign-out-alt"></i> Salir
        </a>
    </div>
</header>

<style>
.topbar {
    height: 64px;
    background: #fff;
    color: #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 25px;
    margin-left: 210px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    border-bottom: 1px solid #eee;
    font-family: 'Segoe UI', sans-serif;
    z-index: 1000;
    position: relative;
    z-index: 0;
}

.topbar-left button {
    font-size: 22px;
    background: none;
    border: none;
    cursor: pointer;
    color: #333;
    transition: transform 0.2s ease;
}
.topbar-left button:hover {
    transform: scale(1.1);
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-info img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid #0D8ABC;
}

.user-text {
    line-height: 1.3;
    font-size: 14px;
    color: #333;
}

.logout {
    background: #e74c3c;
    color: white;
    padding: 7px 14px;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    transition: background 0.2s ease;
}
.logout:hover {
    background: #c0392b;
}

@media (max-width: 768px) {
    .topbar {
        margin-left: 0;
        flex-wrap: wrap;
        height: auto;
        padding: 12px 20px;
    }
    .topbar-right {
        margin-top: 10px;
        width: 100%;
        justify-content: space-between;
    }
}
</style>

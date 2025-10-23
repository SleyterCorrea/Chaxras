<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$id_nivel = $_SESSION['user']['id_nivel'] ?? null;
$current = $_GET['controller'] ?? '';
?>

<div class="sidebar-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="<?= BASE_URL ?>assets/img/LOGOCHAXRAS.png" style="width: 222px;" alt="Logo">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
        </div>

        <ul class="sidebar-menu">
            <?php if ($id_nivel == 1): ?>
                <li><a href="<?= BASE_URL ?>admin" class="<?= ($current == 'admin') ? 'active' : '' ?>">🏠 Panel</a></li>
            <?php endif; ?>

            <?php if (in_array($id_nivel, [1, 2])): ?>
                <li><a href="<?= BASE_URL ?>rrhh" class="<?= ($current == 'rrhh') ? 'active' : '' ?>">👥 Trabajadores</a></li>
            <?php endif; ?>

            <?php if (in_array($id_nivel, [1, 5])): ?>
                <li><a href="<?= BASE_URL ?>inventario" class="<?= ($current == 'inventario') ? 'active' : '' ?>">📦 Inventario</a></li>
                <li><a href="<?= BASE_URL ?>inventario/lotes">📋 Lotes</a></li>
            <?php endif; ?>

            <?php if (in_array($id_nivel, [1, 4])): ?>
                <li><a href="<?= BASE_URL ?>mesero" class="<?= ($current == 'mesero') ? 'active' : '' ?>">📝 Pedidos</a></li>
                <li><a href="<?= BASE_URL ?>mesero/mis_ordenes">📋 Mis Órdenes</a></li>
            <?php endif; ?>

            <?php if (in_array($id_nivel, [1, 3])): ?>
                <li><a href="<?= BASE_URL ?>cocina" class="<?= ($current == 'cocina') ? 'active' : '' ?>">🍽 Cocina</a></li>
                <li><a href="<?= BASE_URL ?>cocina/platos">🍛 Platos</a></li>
            <?php endif; ?>

            <?php if (in_array($id_nivel, [1, 6])): ?>
                <li><a href="<?= BASE_URL ?>finanzas" class="<?= ($current == 'finanzas') ? 'active' : '' ?>">💰 Finanzas</a></li>
            <?php endif; ?>

            <li><a href="<?= BASE_URL ?>login/logout">🔒 Cerrar sesión</a></li>
        </ul>
    </div>
</div>

<style>
.sidebar-container {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #003c33, #02735E);
    color: #fff;
    padding: 20px;
    transition: transform 0.3s ease;
    overflow-y: auto;
}

.sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sidebar-header img {
    width: 160px;
}

.sidebar-toggle {
    background: transparent;
    border: none;
    font-size: 28px;
    color: #fff;
    cursor: pointer;
    display: none;
}

.sidebar-menu {
    list-style: none;
    padding: 0;
    margin-top: 30px;
}

.sidebar-menu li {
    margin-bottom: 16px;
}

.sidebar-menu a {
    display: block;
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.07);
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 16px;
    transition: background 0.3s, transform 0.3s;
    font-family: 'Poppins', sans-serif;
}

.sidebar-menu a:hover {
    background: #00bfa5;
    transform: translateX(5px);
}

.sidebar-menu a.active {
    background: #ffffff;
    color: #004d40;
    font-weight: bold;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        position: absolute;
    }

    .sidebar.show {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }
}
</style>

<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
}
</script>

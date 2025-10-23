<?php
    // app/Vistas/partials/header.php
    // Aquí asumimos que Sesion::iniciar() ya llamó a session_start() en public/index.php.
    // Por tanto, podemos usar directamente $_SESSION.

    ?>
    <header>
    <nav id="header">
        <a href="<?= BASE_URL ?>inicio">
        <img class="logo" src="<?= BASE_URL ?>assets/img/logo.png" alt="Logo de la página">
        </a>

        <input id="menu" type="checkbox">
        <label for="menu">
        <img class="menu-icono" src="<?= BASE_URL ?>assets/img/menu.png" alt="Menú">
        </label>

        <ul class="menu">
        <li class="item"><a href="<?= BASE_URL ?>inicio" title="Inicio">Inicio</a></li>
        <li class="item"><a href="<?= BASE_URL ?>nosotros" title="Conócenos">Nosotros</a></li>
        <li class="item"><a href="<?= BASE_URL ?>reservas" title="Realiza tus reservas">Reservas</a></li>
        <li class="item"><a href="<?= BASE_URL ?>contacto" title="Contáctanos">Contacto</a></li>

        <?php if (! empty($_SESSION['user'])): ?>
            <li class="item user-menu">
            <i class="bi bi-person-circle avatar" id="userAvatar"></i>
                <ul class="dropdown" id="userDropdown">
                    <li class="profile"><span><?= $_SESSION['user']['nombre'] ?></span></li>
                    <li><a href="<?= BASE_URL ?>login/logout">Cerrar sesión</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="btn"><a href="<?= BASE_URL ?>login" title="Iniciar sesión">Login</a></li>
        <?php endif; ?>
        </ul>
    </nav>
    </header>
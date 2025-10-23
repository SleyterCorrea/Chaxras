<?php

use App\Core\Sesion;

// Obtén el token CSRF (suponiendo que Sesion lo generó y está en $_SESSION['csrf_token'])
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login / Registro</title>
    <!-- Ruta al CSS, usando BASE_URL -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/login.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/general.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/footer.css?v=<?= time() ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="<?= BASE_URL ?>Assets/js/login.js" defer></script>
</head>
<body>

<?php include_once __DIR__ . '/../layout/header.php'; ?>
    <main>
        <section class="main-login">
            <div class="section-auth">
                <div class="backlogin">
                    <!-- Mensajes de sesión -->
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($_SESSION['error']); ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($_SESSION['success']); ?>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>

                    <!-- FORMULARIO DE LOGIN -->
                    <form class="form-login active" action="<?= BASE_URL ?>login/login" method="POST">
                        <h2>Inicia sesión</h2>

                        <!-- Token CSRF -->
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">


                        <div class="container-field">
                            <span class="icon-field"><i class='bx bxs-envelope'></i></span>
                            <input type="email" id="login-email" name="email" placeholder="Correo Electrónico" required>
                        </div>

                        <div class="container-field">
                            <span class="icon-field"><i class='bx bxs-lock-alt'></i></span>
                            <input type="password" id="login-password" name="password" placeholder="Contraseña" required minlength="5">
                        </div>

                        <div class="section-options">
                            <p><a href="<?= BASE_URL ?>login/recuperacion">¿Olvidaste tu contraseña?</a></p>
                        </div>

                        <button class="btn-submit" type="submit">Entrar</button>

                        <!-- Google Sign-In -->
                        <?php if (!empty($google_login_url)): ?>
                            <div class="google-login" style="text-align: center; margin-top: 15px;">
                                <a href="<?= htmlspecialchars($google_login_url); ?>">
                                    <img src="https://developers.google.com/identity/images/btn_google_signin_light_normal_web.png" alt="Iniciar sesión con Google">
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="link-register">
                            <p>No tienes una cuenta? <a href="#" class="action-register">Regístrate</a></p>
                        </div>
                    </form>

                    <!-- FORMULARIO DE REGISTRO -->
                <form class="form-register" action="<?= BASE_URL ?>login/register" method="POST">
                    <h2>Regístrate</h2>

                    <!-- Token CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">

                    <!-- Campo Nombre agregado -->
                    <div class="container-field">
                        <span class="icon-field"><i class='bx bxs-user'></i></span>
                        <input type="text" id="register-name" name="nombre" placeholder="Nombre Completo" required minlength="3" maxlength="50">
                    </div>

                    <div class="container-field">
                        <span class="icon-field"><i class='bx bxs-envelope'></i></span>
                        <input type="email" id="register-email" name="email" placeholder="Correo Electrónico" required>
                    </div>

                    <div class="container-field">
                        <span class="icon-field"><i class='bx bxs-lock-alt'></i></span>
                        <input type="password" id="register-password" name="password" placeholder="Contraseña" required minlength="8">
                    </div>

                    <div class="container-field">
                        <span class="icon-field"><i class='bx bxs-lock-alt'></i></span>
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirma tu contraseña" required minlength="8">
                    </div>

                    <button class="btn-submit" type="submit">Crear mi cuenta</button>

                    <div class="link-login">
                        <p>¿Ya tienes una cuenta? <a href="#" class="action-login">Inicia sesión</a></p>
                    </div>
                </form>

                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <img src="<?= BASE_URL ?>assets/img/logo3.png" alt="Logo Chaxras">
            <hr>
            <h4>Formas de pago</h4>
            <div class="payment-methods">
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-amex"></i>
                <i class="fab fa-paypal"></i>
                <i class="fab fa-cc-amazon-pay"></i>
            </div>
        </div>

        <div class="footer-section">
            <h4>Categorías</h4>
            <ul>
                <li>Frutas y verduras</li>
                <li>Abarrotes</li>
                <li>Granos y semillas</li>
                <li>Aves y cárnicos</li>
                <li>Lácteos</li>
                <li>Embutidos</li>
                <li>Despensas</li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Servicios</h4>
            <ul>
                <li>Selección</li>
                <li>Piqueo</li>
                <li>Lavado y desinfectado</li>
                <li>Pelado y procesado</li>
                <li>Despensa</li>
            </ul>
        </div>

        <div class="footer-section">
            <h4>Contacto</h4>
            <p><i class="fas fa-map-marker-alt"></i> 6448+F24, Chiclayo 14000</p>
            <p><i class="fas fa-phone-alt"></i> +51 987 654 321</p>
            <p><i class="fas fa-phone-alt"></i> (051) 123 456</p>
            <p><i class="fas fa-envelope"></i> info@marketprimavera.com</p>
            <p><i class="fas fa-globe"></i> www.marketprimavera.com</p>
        </div>

        <div class="footer-section">
            <h4>Legales</h4>
            <ul>
                <li>Términos y condiciones</li>
                <li>Políticas de privacidad</li>
                <li>Políticas de cambios</li>
            </ul>
            <br>
            <h4>Redes Sociales</h4>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© 2024 Market Primavera. Todos los derechos reservados.</p>
    </div>
</footer>

<!-- Script para alternar entre login y registro -->
<script>
    (function() {
        const loginForm      = document.querySelector('.form-login');
        const registerForm   = document.querySelector('.form-register');
        const loginAction    = document.querySelector('.action-login');
        const registerAction = document.querySelector('.action-register');
        
        registerAction.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.add('animate-slide-out-left');
            registerForm.classList.add('animate-slide-in-right', 'active');
            setTimeout(() => {
                loginForm.classList.remove('active', 'animate-slide-out-left');
            }, 500);
        });

        loginAction.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.add('animate-slide-out-right');
            loginForm.classList.add('animate-slide-in-left', 'active');
            setTimeout(() => {
                registerForm.classList.remove('active', 'animate-slide-out-right');
            }, 500);
        });
    })();
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const avatar    = document.getElementById('userAvatar');
    const dropdown  = document.getElementById('userDropdown');
    const logoutBtn = dropdown.querySelector('a[href$="logout"]');

    // Toggle del menú al hacer clic en el avatar
    avatar?.addEventListener('click', e => {
    e.stopPropagation();
    dropdown.classList.toggle('open');
    });

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', () => {
    dropdown.classList.remove('open');
    });

    // Tras hacer clic en Cerrar sesión, forzar recarga
    logoutBtn?.addEventListener('click', () => {
      // Este timeout da tiempo a que el servidor destruya la sesión y redirija
    setTimeout(() => {
        window.location.href = window.location.origin + window.location.pathname;
    }, 100);
    });
});
</script>
<script src="<?= BASE_URL ?>assets/js/script.js?v=<?= time() ?>"></script>
</body>
</html>


<style>/* -------------------- FOOTER -------------------- */
/*------------------------------------------------- */
.footer{
    font-family: "Montserrat", serif;
    background: #00695c;
    color: #FFFFFF;
    padding: 50px 20px;
    margin-top: 5rem;
}

.footer-container{
    display: flex; /* Distribución en línea */
    flex-wrap: wrap; /* Permite ajustar elementos en varias líneas */
    justify-content: space-between; /* Espaciado uniforme entre elementos */
    gap: 20px; /* Espacio entre elementos */
    max-width: 1200px; /* Ancho máximo del contenedor */
    margin: 0 auto; /* Centrado horizontal */
}

.footer-brand{
    flex: 1; /* Toma espacio proporcional disponible */
    text-align: center; /* Centra el contenido */
    max-width: 150px;
}

.footer-brand img{
    max-width: 70%;
}

.footer-brand hr{
    border: 1px solid #FFFFFF;
    margin-bottom: 10px;
}

.footer-brand h4{
    font-size: 1rem;
    padding: 5px 0;
    margin-bottom: 10px;
}

.payment-methods{
    display: flex; /* Elementos en línea */
    flex-wrap: wrap; /* Permite ajustar en varias líneas */
    justify-content: center; /* Centra los iconos */
    gap: 15px; /* Espacio entre iconos */
    font-size: 2.2rem; /* Tamaño de los iconos */
}

.payment-methods i{
    color: #FFFFFF;;
    display: flex; /* Habilita flexbox */
    cursor: pointer; /* Cursor de mano al pasar sobre los iconos */
}


.footer-section{
    flex: 1;
    max-width: 200px;
}

.footer-section h4{
    font-size: 1.2rem; /* Tamaño del título */
    font-weight: 600; /* Grosor de la fuente */
    margin: 15px 0; /* Separación superior e inferior */
    text-transform: uppercase; /* Convierte el texto a mayúsculas */
}

.footer-section ul{
    list-style: none; /* Elimina las viñetas de la lista */
    padding: 0; /* Elimina padding */
}

.footer-section ul li{
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.footer-section ul li:hover{
    text-decoration: underline; /* Sube una línea al pasar el mouse */
}

.footer-section p{
    font-size: 0.9rem;
    margin-bottom: 10px;
    line-height: 1.5;
}

.social-icons{
    display: flex;
    gap: 15px;
    font-size: 2rem;
}

.social-icons a{
    color: #FFFFFF;
}

.footer-bottom{
    text-align: center;
    margin-top: 20px;
    font-size: 0.8em;
    border-top: 1px solid #FFFFFF;
    padding-top: 10px;
}

.footer-bottom p{
    font-size: 1rem;
}


@media(max-width:768px){
.delivery-section {
    flex-direction: column; /* Cambia a una columna en pantallas pequeñas */
}

.delivery-content,
.delivery-image {
    text-align: center; /* Centra el contenido */
}

.delivery-image img {
    max-width: 60%; /* Reduce el tamaño de la imagen */
}

.footer{
    padding: 30px 0px;
}

.footer-container{
    flex-wrap: nowrap; /* Deshabilita el ajuste automático */
    flex-direction: column; /* Alinea los cuadros en una columna vertical */
    align-items: center; /* Centra los elementos */
    text-align: center; /* Centra el texto */
}

.footer-brand{
    max-width: none; /* Permite que ocupe todo el ancho disponible */
}

.footer-brand h4{
    font-size: 1.8rem; /* Aumenta el tamaño de fuente */
}

.payment-methods{
    font-size: 4rem; /* Incrementa el tamaño de los iconos */
}

.footer-section{
    max-width: none; /* Permite que ocupe todo el ancho disponible */
}

.social-icons{
    justify-content: center; /* Centra los iconos */
}
}

@media (max-width: 480px){
.carrusel-item {
    flex: 0 0 90%; /* Se muestra 1 cuadro visible */
}

.delivery-content h2{
    font-size: 1.4em; /* Ajusta el tamaño del texto en móviles */
}

.delivery-button{
    font-size: 14px; /* Botón más pequeño */
}

.delivery-image img{
    max-width: 80%; /* Más ajuste para móviles */}
}
</style>
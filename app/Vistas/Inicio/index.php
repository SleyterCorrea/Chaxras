<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inicio | Campestre</title>
    
    <!-- Archivos locales -->
    <link rel="stylesheet" href="<?= BASE_URL ?>Assets/css/header.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>Assets/css/inicio.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>Assets/css/footer.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>Assets/css/general.css?v=<?= time() ?>">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Font Awesome: Librería de iconos -->
        <script src="https://kit.fontawesome.com/6e6003d0c3.js" crossorigin="anonymous"></script>

    <!-- Preconexión con Google Fonts para optimizar la carga de fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <script src="codigo/inicio-carousel.js" defer></script>  <!-- El atributo defer asegura que el script se ejecute después de que el DOM esté completamente cargado -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Swiper JS: Script externo necesario para la funcionalidad de carruseles y sliders -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Seguridad: evitar indexación no deseada -->
    <meta name="robots" content="index, follow">
</head>
<body>

<?php include_once __DIR__ . '/../layout/header.php'; ?>

    <main>
        <!-- Slider -->
        <section>
            <div class="slider">
                <ul>
                    <li>
                        <img src="<?= BASE_URL ?>assets/img/comidaheader.jpg" alt="Ceviche Norteño">
                        <div class="slider-texto">Auténtico sabor campestre</div>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>assets/img/parrilla.jpg" alt="Restaurante">
                        <div class="slider-texto">Una experiencia única</div>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>assets/img/Cultivos.jpg" alt="Entorno">
                        <div class="slider-texto">Cuidamos tu comida</div>
                    </li>
                    <li>
                        <img src="<?= BASE_URL ?>assets/img/espacio4.jpg" alt="Paisaje">
                        <div class="slider-texto">Conexión con la naturaleza</div>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Nosotros -->
        <section id="nosotros">
            <div class="contenedor-nosotros contenedor">
                <div class="texto-nosotros">
                    <p class="bienvenida">¡Bienvenido a!</p>
                    <h1>Chaxras</h1>
                    <p class="nosotros">
                    Chaxras Eco - Restaurante, surge como una nueva alternativa gastronómica, donde priman la cocina peruana, las parrillas al carbón, cocinas a leña y hornos de barro; Compar- tiendo una novedosa cocina con insumos orgánicos de la zona y sabores tradicionales perua- nos, pero únicos del campo.

                    Chaxras Eco-Restaurante nace de la búsqueda del desarrollo sostenible del primer distrito Eco- lógico y Turístico del Perú, Pachacamac; y el compromiso con el medio ambiente bajo el concep- to ecológico, el reciclaje y la reutilización de todos los productos. Además de la presencia de barro, cañas de bambú, piedras y material reciclado en su construcción, hasta la concepción de nuestro Bio - huerto que abastece gran parte de las verduras y frutas en nuestra cocina y bar.
                    </p>
                </div>
                <div class="imagenes-nosotros">
                    <div class="imagen1">
                        <img src="<?= BASE_URL ?>assets/img/plato2.jpg" alt="Plato degustativo de cuy">
                    </div>
                </div>
            </div>
        </section>

        <!-- Cards -->
        <section>
            <div class="contenedor-cards">
                <!-- Historia -->
                <div class="card">
                    <div class="face front">
                        <img src="<?= BASE_URL ?>assets/img/historia.jpg" alt="Historia">
                        <h3>Historia</h3>
                    </div>
                    <div class="face back">
                        <h3>Historia</h3>
                        <p>Chaxras nació de la pasión por la rica gastronomía del Perú, ofreciendo a sus visitantes un espacio acogedor en Pachacamac-Lima donde disfrutarán de sabores tradicionales pero autenticos.</p>
                        <div class="link">
                            <a href="<?= BASE_URL ?>nosotros">Saber más</a>
                        </div>
                    </div>
                </div>

                <!-- Misión -->
                <div class="card">
                    <div class="face front">
                        <img src="<?= BASE_URL ?>assets/img/mision.jpg" alt="Misión">
                        <h3>Misión</h3>
                    </div>
                    <div class="face back">
                        <h3>Misión</h3>
                        <p>Brindar una experiencia gastronómica personalizada e inolvidable a nuestros clientes, ofreciendo platos típicos de la comida peruana elaborados con ingredientes frescos y de calidad, en un ambiente acogedor y familiar.</p>
                        <div class="link">
                            <a href="<?= BASE_URL ?>nosotros">Saber más</a>
                        </div>
                    </div>
                </div>

                <!-- Visión -->
                <div class="card">
                    <div class="face front">
                        <img src="<?= BASE_URL ?>assets/img/vision.jpg" alt="Visión">
                        <h3>Visión</h3>
                    </div>
                    <div class="face back">
                        <h3>Visión</h3>
                        <p>Ser el ECO restaurante líder a nivel nacional, reconocido por la autenticidad de su comida y la calidez de su servicio, promoviendo la cultura y tradiciones del norte del Perú.</p>
                        <div class="link">
                            <a href="<?= BASE_URL ?>nosotros">Saber más</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="delivery-section">
            <div class="delivery-content">
                <h2>Reserva ahora y accede a una experiencia inolvidable y personalizada según tus gustos, en un lugar mágico.</h2>
                <button class="delivery-button">
                    <a href="<?= BASE_URL ?>reservas" style="text-decoration: none; color: white;">
                        Haz tu reserva ahora
                    </a>
                </button>
            </div>
            <div class="delivery-image">
                <img src="<?= BASE_URL ?>Assets/img/logo.png" alt="Logo Chaxra">
            </div>
        </section>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <a href="#">
                <img src="<?= BASE_URL?>Assets/img/logo.png" alt="Logo Chaxras">
            </a>  
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
    <script src="<?= BASE_URL ?>Assets/js/script.js"<?= time() ?>></script>
</body>

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
</html>
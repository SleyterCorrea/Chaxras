<?php
// app/vistas/nosotros/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros | Chaxras</title>

    <!-- Iconos y fuentes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600&display=swap" rel="stylesheet">

    <!-- AOS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />

    <!-- Estilos propios -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/nosotros.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/footer.css?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/general.css?v=<?= time() ?>">
</head>
<body>

    <?php include_once __DIR__ . '/../layout/header.php'; ?>

    <main>

    <!-- VIDEO ENCABEZADO -->
    <section class="nosotros-video">
        <video autoplay muted loop aria-label="Video de presentación de Chaxras">
        <source src="<?= BASE_URL ?>assets/video/video_chxcras.mp4" type="video/mp4">
        Tu navegador no soporta la etiqueta de video.
        </video>
        <div class="texto-nosotros-video">
        <h1>Conócenos</h1>
        <p>Descubre nuestra historia, misión y visión como eco-restaurante comprometido con el Perú y el medio ambiente.</p>
        </div>
        <a href="#historia" class="scroll-flecha">
        <i class="bi bi-chevron-double-down"></i>
        </a>
    </section>

    <!-- CONTENIDO PRINCIPAL -->
    <section class="nosotros-seccion container">

        <!-- HISTORIA -->
        <div class="bloque" id="historia" data-aos="fade-up">
        <div class="bloque-imagen">
            <img src="<?= BASE_URL ?>assets/img/nosotros1.jpg" alt="Historia Chaxras">
        </div>
        <div class="bloque-texto">
            <h2>Historia</h2>
            <p>
            Chaxras Eco-Restaurante nació del sueño de crear un espacio donde la gastronomía peruana pudiera convivir con la naturaleza. Desde nuestros inicios en Pachacamac, apostamos por una cocina tradicional basada en el uso de productos orgánicos, técnicas ancestrales como el horno de barro, la leña y el carbón, y un profundo respeto por las raíces culturales del Perú.
            <br><br>
            Más que un restaurante, somos una experiencia vivencial. El entorno natural que nos rodea, nuestros huertos ecológicos y los materiales reciclados usados en nuestra construcción reflejan nuestro compromiso con el desarrollo sostenible. Chaxras no solo ofrece comida, sino también un encuentro con la tierra, los sabores del campo y la esencia del Perú.
            <br><br>
            A lo largo de los años, hemos fortalecido nuestra conexión con productores locales, impulsando el comercio justo y promoviendo el respeto por los ingredientes nativos. Nuestra historia sigue creciendo con cada visitante que comparte nuestra mesa.
            </p>
        </div>
        </div>

        <hr class="separador" />

        <!-- MISIÓN -->
        <div class="bloque reverse" data-aos="fade-up">
        <div class="bloque-imagen">
            <img src="<?= BASE_URL ?>assets/img/nosotros2.jpg" alt="Misión Chaxras">
        </div>
        <div class="bloque-texto">
            <h2>Misión</h2>
            <p>
            Nuestra misión es ofrecer a cada visitante una experiencia gastronómica auténtica, saludable y memorable. Buscamos rescatar los sabores tradicionales del Perú a través de platos elaborados con ingredientes frescos, cultivados en nuestra propia chacra, bajo prácticas sostenibles y amigables con el medio ambiente.
            <br><br>
            Nos enfocamos en brindar un servicio cálido y personalizado, en un ambiente donde se respire naturaleza, cultura y armonía. Cada plato que servimos es el reflejo del amor por nuestras raíces, del respeto por la tierra y de nuestro deseo por alimentar el cuerpo y el alma de quienes nos visitan.
            <br><br>
            Nuestra misión trasciende la cocina: aspiramos a educar sobre la importancia de una alimentación consciente, conectando a las personas con el origen de lo que consumen.
            </p>
        </div>
        </div>

        <hr class="separador" />

        <!-- VISIÓN -->
        <div class="bloque" data-aos="fade-up">
        <div class="bloque-imagen">
            <img src="<?= BASE_URL ?>assets/img/nosotros3.jpg" alt="Visión Chaxras">
        </div>
        <div class="bloque-texto">
            <h2>Visión</h2>
            <p>
            Aspiramos a posicionarnos como el principal referente de eco-restaurantes en el Perú, promoviendo una cultura gastronómica responsable, consciente y conectada con la tierra. Queremos ser reconocidos no solo por la calidad de nuestros platos, sino por nuestra coherencia ecológica y nuestro compromiso con la comunidad local.
            <br><br>
            Imaginamos un futuro donde más peruanos se reencuentren con la cocina de sus abuelos, con los productos de la chacra, y comprendan que comer bien también es cuidar el planeta. Nuestra visión es clara: un Perú donde lo natural y lo tradicional vayan de la mano.
            <br><br>
            Proyectamos nuestra propuesta a nivel nacional e internacional, expandiendo el mensaje de que es posible brindar excelencia gastronómica sin comprometer el equilibrio del ecosistema.
            </p>
        </div>
        </div>

    </section>


    </main>

    <footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <a href="#">
                <img src="<?= BASE_URL?>assets/img/logo3.png" alt="Logo Chaxras">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 1000,
        easing: 'ease-in-out',
        once: true,
    });
    </script>
    <script src="<?= BASE_URL ?>assets/js/script.js?v=<?= time() ?>"></script>

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

</body>
</html>
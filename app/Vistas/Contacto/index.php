<?php
use App\Core\Sesion;
$csrfToken = $_SESSION['csrf_token'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contáctanos | Chaxras</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/contacto.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/general.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css?v=<?= time() ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/footer.css?v=<?= time() ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php include_once __DIR__ . '/../layout/header.php'; ?>

<main>
  <section class="contact-section">
    <div class="contact-wrapper">
      <!-- INFORMACIÓN DE CONTACTO -->
      <div class="info-container">
        <div class="info-item">
          <i class="bi bi-shop-window"></i>
          <h3>LOCAL</h3>
          <p>Calle 8 Mz. K Lt. 66B, Urb. Casablanca, Pachacamac, Perú</p>
        </div>
        <div class="info-item">
          <i class="bi bi-envelope-arrow-down"></i>
          <h3>EMAIL</h3>
          <p>chaxraseco@gmail.com</p>
        </div>
        <div class="info-item">
          <i class="bi bi-bicycle"></i>
          <h3>DELIVERY</h3>
          <p>+51 910 717 114</p>
        </div>
      </div>

      <!-- FORMULARIO DE CONTACTO -->
      <div class="form-container">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
          <div class="alert success">Tu mensaje fue enviado exitosamente.</div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>contacto/enviar" method="POST" class="contact-form">
          <h2>Contáctanos</h2>
          <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

          <div class="form-group">
            <input type="text" name="name" placeholder="Nombre completo" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo letras y espacios">
            <input type="email" name="email" placeholder="Correo electrónico" required>
          </div>
          <div class="form-group">
            <input type="tel" name="phone" placeholder="Celular" required pattern="9\d{8}" title="Debe empezar con 9 y tener 9 dígitos">
            <input type="text" name="subject" placeholder="Asunto" required>
          </div>
          <textarea name="message" placeholder="Escribe tu mensaje..." rows="6" required></textarea>
          <button type="submit">Enviar</button>
        </form>
      </div>
    </div>
  </section>

  <!-- MAPA DE COBERTURA -->
  <section class="nosotros-cobertura">
    <h3>Mapa de Cobertura</h3>
    <h4>Ubicación de Chaxras Eco-Restaurante</h4>
    <div class="contenido-cobertura">
      <div class="imagen-cobertura">
        <img src="<?= BASE_URL ?>assets/img/lima.png" alt="Región Lima">
      </div>
      <div class="direccion-cobertura">
        <p>Calle 8 Manzana K Lot 66B Urbanización, Pachacamac 15823</p>
        <iframe 
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d975.480541184096!2d-76.86141883048016!3d-12.208294858343894!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105b9d32b6a8b9d%3A0xb5e4f4d6edb5a3ef!2sCalle%208%20Mz.%20K%20Lt.%2066B%2C%20Urb.%20Casablanca%2C%20Pachacamac%2015823!5e0!3m2!1ses!2spe!4v1712345678901"
          width="100%" height="380" style="border:0;" allowfullscreen loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <div class="boton-mapa">
          <a href="https://www.google.com/maps/search/?api=1&query=Calle+8+Manzana+K+Lot+66B+Urbanizacion,+Pachacamac+15823" target="_blank" id="mapButton">Ver en Google Maps</a>
        </div>
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
    <script src="<?= BASE_URL ?>Assets/js/script.js"<?= time() ?>></script>

<!-- Script para preguntas frecuentes -->
<script>
  const faqs = document.querySelectorAll(".faq-question");
  faqs.forEach((btn) => {
    btn.addEventListener("click", () => {
      const item = btn.parentElement;
      const answer = item.querySelector(".faq-answer");
      const isOpen = item.classList.contains("open");

      document.querySelectorAll(".faq-item").forEach(i => {
        i.classList.remove("open");
        i.querySelector(".faq-answer").style.maxHeight = null;
        i.querySelector(".icon").textContent = '▶';
      });

      if (!isOpen) {
        item.classList.add("open");
        answer.style.maxHeight = answer.scrollHeight + "px";
        item.querySelector(".icon").textContent = '▼';
      }
    });
  });
</script>

<!-- ScrollReveal Animaciones -->
<script src="https://unpkg.com/scrollreveal"></script>
<script>
  ScrollReveal({ reset: false, distance: '40px', duration: 900, delay: 100 });

  ScrollReveal().reveal('.info-item', { origin: 'left', interval: 200 });
  ScrollReveal().reveal('.contact-form', { origin: 'right' });
  ScrollReveal().reveal('.faq-item', { origin: 'bottom', interval: 150 });
  ScrollReveal().reveal('.imagen-cobertura', { origin: 'left' });
  ScrollReveal().reveal('.direccion-cobertura', { origin: 'right' });
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
</body>
</html>
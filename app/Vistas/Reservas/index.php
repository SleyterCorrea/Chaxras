<?php
    use App\Core\Sesion;
    $csrfToken = $_SESSION['csrf_token'] ?? '';
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservas | Chxcras</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css?v=<?= time() ?>">
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/reservas.css?v=<?= time() ?>">
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/footer.css?v=<?= time() ?>">
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/general.css?v=<?= time() ?>">
    </head>
    <body>

        <?php include_once __DIR__ . '/../layout/header.php'; ?>

        <main class="container">
            <h1>Reservas CHXCRAS</h1>

            <!-- Opciones principales -->
            <div id="main-options" class="step-block">
                <button class="big-btn" onclick="showRealizarReserva()">
                    <i class="fas fa-calendar-plus"></i>
                    Realizar Reserva
                    <small>Elige mesa sin iniciar sesión</small>
                </button>
            </div>

            <!-- Barra de progreso -->
            <div id="progress-bar" class="step-block" style="display:none;">
                <div class="progress-container">
                    <div class="progress-line" id="progress-line"></div>
                    <div class="progress-steps">
                        <div class="progress-step active" data-step="tipo">
                            <div class="step-circle">
                                <i class="fas fa-list"></i>
                            </div>
                            <span>Tipo</span>
                        </div>
                        <div class="progress-step" data-step="detalles">
                            <div class="step-circle">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span>Detalles</span>
                        </div>
                        <div class="progress-step" data-step="preferencias">
                            <div class="step-circle">
                                <i class="fas fa-heart"></i>
                            </div>
                            <span>Preferencias</span>
                        </div>
                        <div class="progress-step" data-step="resumen">
                            <div class="step-circle">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Resumen</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paso 1: Tipo -->
            <div id="step-tipo" class="step-block" style="display:none;">
                <button class="back-btn" onclick="goBackToMain()">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <h2>¿Qué deseas hacer?</h2>
                <button class="option-btn" onclick="window.open('https://wa.me/+51946073690','_blank')">
                    <i class="fab fa-whatsapp"></i>
                    Realizar Reserva (WhatsApp)
                    <small>Toda reserva se realiza por medio de whatsaap</small>
                </button>
            </div>

            <!-- Paso 2: Detalles -->
            <div id="step-detalles" class="step-block" style="display:none;">
                <button class="back-btn" onclick="toStep('tipo')">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <h2>Detalles de la Reserva</h2>
                
                <!-- Mensajes de error -->
                <div id="error-messages" class="error-container" style="display:none;"></div>
                
                <form id="form-reserva">
                    <input type="hidden" name="csrf_token" value="">
                    
                    <div class="form-group">
                        <label><i class="fas fa-calendar"></i> Selecciona el día:</label>
                        <div class="date-selector-container">
                            <div class="date-input" onclick="toggleCalendar()" id="date-input">
                                <i class="fas fa-calendar-alt"></i>
                                <span id="selected-date">Selecciona una fecha</span>
                                <i class="fas fa-chevron-down chevron"></i>
                            </div>
                            <div class="calendar-popup" id="calendar-popup">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav" onclick="previousMonth()">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <span class="calendar-month" id="current-month">Enero 2025</span>
                                    <button type="button" class="calendar-nav" onclick="nextMonth()">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="calendar-weekdays">
                                    <div class="calendar-weekday">Dom</div>
                                    <div class="calendar-weekday">Lun</div>
                                    <div class="calendar-weekday">Mar</div>
                                    <div class="calendar-weekday">Mié</div>
                                    <div class="calendar-weekday">Jue</div>
                                    <div class="calendar-weekday">Vie</div>
                                    <div class="calendar-weekday">Sáb</div>
                                </div>
                                <div class="calendar-grid" id="calendar-grid">
                                    <!-- Días del calendario se generan dinámicamente -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Selecciona la hora:</label>
                        <div class="time-selector" id="time-selector">
                            <!-- Horarios se generan dinámicamente -->
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-users"></i> Número de Personas:</label>
                        <div class="number-input-container">
                            <button type="button" class="number-btn" onclick="decreasePersonas()">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="personas" id="personas" min="1" max="12" value="1" required>
                            <button type="button" class="number-btn" onclick="increasePersonas()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nombre del Titular:</label>
                        <input type="text" name="titular" id="titular" required placeholder="Nombre completo">
                    </div>

                    <button type="button" class="nav-btn" onclick="validateAndContinue()">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>

            <!-- Paso 3: Preferencias -->
            <div id="step-preferencias" class="step-block" style="display:none;">
                <button class="back-btn" onclick="toStep('detalles')">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
                <h2>Preferencias (opcionales)</h2>
                
                <div class="form-group">
                    <label><i class="fas fa-exclamation-triangle"></i> Alergias:</label>
                    <input type="text" name="alergias" id="alergias" placeholder="Ej: Nueces, mariscos, gluten">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-birthday-cake"></i> Celebración:</label>
                    <input type="text" name="celebracion" id="celebracion" placeholder="Ej: Cumpleaños, aniversario">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-comment"></i> Requerimientos Adicionales:</label>
                    <textarea name="requerimientos_adicionales" id="requerimientos_adicionales" placeholder="Cualquier solicitud especial..."></textarea>
                </div>

                <button type="button" class="nav-btn" onclick="toStep('resumen')">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </button>
            </div>
<!-- PASO 4: Resumen y Pago -->
<div id="step-resumen" class="step-block" style="display:none;">
    <button class="back-btn" onclick="toStep('preferencias')">
        <i class="fas fa-arrow-left"></i> Volver
    </button>
    <h2>Resumen de tu Reserva</h2>

    <div class="resumen-card"></div>
    <div class="precio-total">
        Total estimado: S/ <span id="precio-estimado">0.00</span>
    </div>

    <div class="form-group">
        <label for="email-simulado"><i class="fas fa-envelope"></i> Correo electrónico:</label>
        <input type="email" id="email-simulado" class="form-control" required placeholder="ejemplo@correo.com">
    </div>

    <div class="form-group">
        <label for="fake-card"><i class="fas fa-credit-card"></i> Número de tarjeta:</label>
        <input type="text" id="fake-card" class="form-control" placeholder="1111 2222 3333 4444" maxlength="19" required>
    </div>

    <div class="form-group">
        <label for="fake-name"><i class="fas fa-user"></i> Nombre del titular (como en tarjeta):</label>
        <input type="text" id="fake-name" class="form-control" placeholder="Juan Pérez" required>
    </div>

    <div class="form-group d-flex" style="gap: 1rem;">
        <div class="form-group">
            <label for="fake-exp"><i class="fas fa-calendar-alt"></i> Fecha expiración (MM/YY):</label>
            <input type="text" id="fake-exp" class="form-control" placeholder="MM/YY" maxlength="5" required inputmode="numeric">
        </div>


        <div style="flex:1;">
            <label for="fake-cvv"><i class="fas fa-lock"></i> Código de seguridad (CVV):</label>
            <input type="password" id="fake-cvv" class="form-control" placeholder="123" maxlength="4" required>
        </div>
    </div>

    <button class="nav-btn" onclick="simularPago()">Confirmar y Pagar</button>
</div>

<!-- PASO 5: Confirmación -->
<div id="step-pago" class="step-block" style="display:none;">
    <h3>✅ Pago procesado correctamente</h3>
    <p>Tu reserva ha sido registrada con éxito.</p>
    <p><strong>Código de reserva:</strong> <span id="codigo-reserva"></span></p>
    <button onclick="window.location.href='<?= BASE_URL ?>reservas/index'" class="btn btn-primary">Ir a inicio</button>
</div>

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

        <script>
        window.BASE_URL = '<?= rtrim(BASE_URL, '/') ?>/';
        </script>
        <script src="<?= BASE_URL ?>assets/js/reserva.js?v=<?= time() ?>"></script>
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
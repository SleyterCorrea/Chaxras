// JavaScript para efectos de scroll y interactividad del header
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    const header = document.getElementById('header');
    const menuCheckbox = document.getElementById('menu');
    const menuItems = document.querySelectorAll('.menu a');
    
    // Variables para el scroll
    let lastScrollTop = 0;
    let scrollTimeout;
    
    
    // Función para manejar el scroll del header
    function handleScroll() {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        // Agregar/quitar clase 'scrolled' basado en la posición del scroll
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Efecto de ocultar/mostrar header al hacer scroll (opcional)
        if (currentScroll > lastScrollTop && currentScroll > 200) {
            // Scrolling hacia abajo - ocultar header
            header.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling hacia arriba - mostrar header
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        
        // Limpiar timeout anterior y establecer uno nuevo
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            // Mostrar header después de que el usuario deje de hacer scroll
            header.style.transform = 'translateY(0)';
        }, 150);
    }
    
    // Event listener para el scroll con throttling
    let ticking = false;
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(handleScroll);
            ticking = true;
            setTimeout(() => {
                ticking = false;
            }, 16); // ~60fps
        }
    }
    
    window.addEventListener('scroll', requestTick);
    
    // Cerrar menú móvil al hacer clic en un enlace
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                menuCheckbox.checked = false;
            }
        });
    });
    
    // Cerrar menú móvil al hacer clic fuera de él
    document.addEventListener('click', (e) => {
        const menu = document.querySelector('.menu');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (window.innerWidth <= 991 && 
            menuCheckbox.checked && 
            !menu.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            menuCheckbox.checked = false;
        }
    });
    
    // Smooth scroll para los enlaces del menú
    menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            const href = item.getAttribute('href');
            
            // Solo aplicar smooth scroll si es un enlace interno (#)
            if (href.startsWith('#')) {
                e.preventDefault();
                const targetId = href.substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    const headerHeight = header.offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
    
    // Efecto de parallax sutil en el slider (opcional)
    const slider = document.querySelector('.slider');
    if (slider) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            
            if (scrolled < window.innerHeight) {
                slider.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
            }
        });
    }
    
    // Intersection Observer para animaciones al entrar en vista
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observar elementos que necesiten animación
    const animateElements = document.querySelectorAll('.slider-texto');
    animateElements.forEach(el => observer.observe(el));
    
    // Preloader para las imágenes del slider
    const sliderImages = document.querySelectorAll('.slider img');
    let imagesLoaded = 0;
    
    sliderImages.forEach(img => {
        if (img.complete) {
            imagesLoaded++;
        } else {
            img.addEventListener('load', () => {
                imagesLoaded++;
                if (imagesLoaded === sliderImages.length) {
                    document.body.classList.add('images-loaded');
                }
            });
        }
    });
    
    // Si todas las imágenes ya están cargadas
    if (imagesLoaded === sliderImages.length) {
        document.body.classList.add('images-loaded');
    }
    
    // Efecto de hover mejorado para los elementos del menú
    const menuElements = document.querySelectorAll('.item a, .btn a');
    
    menuElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.setProperty('--hover-scale', '1.05');
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.setProperty('--hover-scale', '1');
        });
    });
    
    // Función para manejar el redimensionamiento de la ventana
    function handleResize() {
        // Cerrar menú móvil si se cambia a desktop
        if (window.innerWidth > 991 && menuCheckbox.checked) {
            menuCheckbox.checked = false;
        }
    }
    
    window.addEventListener('resize', handleResize);
    
    // Inicialización
    console.log('Header del restaurante inicializado correctamente');
});
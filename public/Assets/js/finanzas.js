document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll('.resumen-finanzas .card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.boxShadow = "0 4px 15px rgba(0,0,0,0.2)";
        });
        card.addEventListener('mouseleave', () => {
            card.style.boxShadow = "0 2px 8px rgba(0,0,0,0.1)";
        });
    });
});

// ✅ Ahora global
function mostrarSeccion(tipo) {
    document.getElementById('seccion-ingresos').style.display = (tipo === 'ingresos') ? 'block' : 'none';
    document.getElementById('seccion-egresos').style.display = (tipo === 'egresos') ? 'block' : 'none';
}

// Confirmación de envío en formularios de movimiento o lote
function confirmarEnvio() {
    return confirm("¿Estás seguro de guardar este movimiento?");
}

// Confirmación de eliminar con botones en inventario
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("a.btn.eliminar").forEach(btn => {
        btn.addEventListener("click", function(e) {
            if (!confirm("¿Estás seguro de eliminar este registro?")) {
                e.preventDefault();
            }
        });
    });
});

let ordenActual = 1;

function sumar(id, categoriaId) {
    const inputCantidad = document.getElementById('plato-' + id);
    const count = document.getElementById('count-' + id);
    const ordenInput = document.getElementById('orden-' + id);
    const ordenBadge = document.getElementById('orden-badge-' + id);

    let cantidad = parseInt(inputCantidad.value);
    cantidad++;
    inputCantidad.value = cantidad;
    count.innerText = cantidad;

    if (cantidad === 1) {
        ordenInput.value = ordenActual;
        ordenBadge.innerText = ordenActual++;
    }

    let totalCat = 0;
    document.querySelectorAll(`input[data-categoria="${categoriaId}"]`).forEach(inp => {
        totalCat += parseInt(inp.value);
    });
    document.getElementById('contador-' + categoriaId).innerText = totalCat;
}

function validarFormulario() {
    const mesa = document.querySelector('select[name="mesa"]').value;
    if (!mesa) {
        alert("Por favor, selecciona una mesa.");
        return false;
    }

    let total = 0;
    document.querySelectorAll('input[name^="plato["]').forEach(p => {
        total += parseInt(p.value);
    });

    if (total === 0) {
        alert("Debes seleccionar al menos un plato.");
        return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.plato-card').forEach(card => {
        card.addEventListener('mousedown', () => card.style.transform = 'scale(0.97)');
        card.addEventListener('mouseup', () => card.style.transform = 'scale(1.06)');
        card.addEventListener('mouseleave', () => card.style.transform = 'scale(1)');
    });
});

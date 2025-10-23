document.addEventListener("DOMContentLoaded", function() {
    // Estado de Pedidos
    new Chart(document.getElementById('estadoPedidosChart'), {
        type: 'doughnut',
        data: {
            labels: estadoPedidosData.map(e => e.estado),
            datasets: [{
                data: estadoPedidosData.map(e => e.total),
                backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Distribución de Trabajadores
    new Chart(document.getElementById('distribucionTrabajadoresChart'), {
        type: 'bar',
        data: {
            labels: distribucionData.map(e => e.nivel),
            datasets: [{
                label: 'Cantidad',
                data: distribucionData.map(e => e.total),
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Ingresos vs Egresos del Mes
    new Chart(document.getElementById('ingresosEgresosChart'), {
        type: 'line',
        data: {
            labels: ingresosEgresosMes.map(e => e.dia),
            datasets: [
                {
                    label: 'Ingresos',
                    data: ingresosEgresosMes.map(e => e.ingreso),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Egresos',
                    data: ingresosEgresosMes.map(e => e.egreso),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.2)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
});

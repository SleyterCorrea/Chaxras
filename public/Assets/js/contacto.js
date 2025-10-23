//MAPA DE MARKET PRIMAVERA
// Seleccionar el botón por su ID
const mapButton = document.getElementById('mapButton');

// Agregar un evento al botón
mapButton.addEventListener('click', () => {
    // Abrir Google Maps en una nueva pestaña
    window.open(
        'https://www.google.com/maps/place/Market+Primavera/@-6.7938228,-79.8873787,17z/data=!3m1!4b1!4m6!3m5!1s0x904cefa17b6684f5:0xd9aff3c0976700a6!8m2!3d-6.7938281!4d-79.8847984!16s%2Fg%2F11kj_5gx1n?entry=ttu&g_ep=EgoyMDI0MTExOS4yIKXMDSoASAFQAw%3D%3D',
        '_blank'
    );
});

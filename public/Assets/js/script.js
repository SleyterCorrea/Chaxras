// Header

var header = document.getElementById('header');

window.addEventListener('scroll', ()=>{

    var scroll = window.scrollY

    if (scroll>10){
        header.style.backgroundColor = '#dddddd'
    } else {
        header.style.backgroundColor = '#fff'
    }
})

/*Nosotros Main*/
document.addEventListener("scroll", function () {
    const elementos = document.querySelectorAll(".texto-nosotros, .imagenes-nosotros");

    elementos.forEach((elemento) => {
        const posicion = elemento.getBoundingClientRect().top;
        const alturaPantalla = window.innerHeight / 1.5;

        if (posicion < alturaPantalla) {
            elemento.style.transition = "transform 0.5s ease-out, opacity 0.5s ease-out";
            elemento.style.transform = "translateY(0)";
            elemento.style.opacity = 1;
        } else {
            elemento.style.transform = "translateY(50px)";
            elemento.style.opacity = 0;
        }
    });
});

/* Targetas giratorias*/

document.addEventListener("scroll", function () {
    const tarjetas = document.querySelectorAll(".card");

    tarjetas.forEach((tarjeta) => {
        const posicion = tarjeta.getBoundingClientRect().top;
        const alturaPantalla = window.innerHeight / 1.3;

        if (posicion < alturaPantalla) {
            tarjeta.style.transform = "translateY(0)";
            tarjeta.style.opacity = 1;
        } else {
            tarjeta.style.transform = "translateY(50px)";
            tarjeta.style.opacity = 0;
        }
    });
});

// Separador transicion
document.addEventListener("scroll", function () {
    const elementos = document.querySelectorAll(".delivery-section");

    elementos.forEach((elemento) => {
        const posicion = elemento.getBoundingClientRect().top;
        const alturaPantalla = window.innerHeight / 1.5;

        if (posicion < alturaPantalla) {
            elemento.style.transition = "transform 0.5s ease-out, opacity 0.5s ease-out";
            elemento.style.transform = "translateY(0)";
            elemento.style.opacity = 1;
        } else {
            elemento.style.transform = "translateY(50px)";
            elemento.style.opacity = 0;
        }
    });
});

/* Reservacion */

function validarFormulario() {
    const celularInput = document.getElementById("celular-reservacion");
    const personasInput = document.getElementById("personas-reservacion");

    const celular = celularInput.value;
    const personas = parseInt(personasInput.value, 10);

    // Validación de celular (solo números y exactamente 9 dígitos)
    const celularRegex = /^[0-9]{9}$/;
    if (!celularRegex.test(celular)) {
        alert("El número de celular debe contener exactamente 9 dígitos numéricos.");
        return false;
    }

    // Validación de número de personas (máximo 8)
    if (personas > 8 || personas < 1) {
        alert("El número de personas debe estar entre 1 y 8.");
        return false;
    }

    return true; // Si todo es válido, el formulario se envía.
}

/* Contacto */
function iniciarMap(){
    var coord = {lat:-6.42060 ,lng: -79.56792};
    var map = new google.maps.Map(document.getElementById('map'),{
    zoom: 18,
    center: coord
    });
    var marker = new google.maps.Marker({
    position: coord,
    map: map
    });
}



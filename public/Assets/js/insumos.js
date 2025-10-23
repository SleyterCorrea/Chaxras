document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".formulario");
    if(form) {
        form.addEventListener("submit", function() {
            if(!confirm("¿Estás seguro de guardar los cambios?")) {
                event.preventDefault();
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.getElementById("alertBox");

    if (alertBox) {
        // Buscar el botón de cierre dentro de alertBox
        const closeBtn = alertBox.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                alertBox.style.display = 'none';
            });
        }

        // Hacer que la alerta desaparezca después de 3 segundos
        setTimeout(() => {
            alertBox.classList.add("opacity-0");
            setTimeout(() => alertBox.remove(), 300); // Elimina el elemento después de la animación
        }, 2000);
    }
});

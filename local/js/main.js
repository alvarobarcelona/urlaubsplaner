document.addEventListener("DOMContentLoaded", function() {
    // Mostrar la alerta si existe
    const alertBox = document.getElementById('alertBox');
    if (alertBox) {
        alertBox.style.display = 'block';
    }

    // Función para cerrar la alerta
    const closeBtn = alertBox.querySelector('.close-btn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            alertBox.style.display = 'none';
        });
    }
});

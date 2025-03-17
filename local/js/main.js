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


    /// tenemos que hacer que al calcular los dias de vacaciones, no cuente ni los sabados ni domingos.
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const vacationDaysInput = document.getElementById("vacation_days");

    function calculateRequiredDays() {
        let startDate = new Date(startDateInput.value);
        let endDate = new Date(endDateInput.value);

        if (isNaN(startDate) || isNaN(endDate)) return; // Evita cálculos si las fechas no son válidas

        let count = 0;
        let currentDate = new Date(startDate);

        while (currentDate <= endDate) {
            let dayOfWeek = currentDate.getDay(); // 0 = Domingo, 6 = Sábado
            if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                count++; // Cuenta solo días hábiles (lunes-viernes)
            }
            currentDate.setDate(currentDate.getDate() + 1); // Avanza un día
        }

        vacationDaysInput.value = count;
    }

    startDateInput.addEventListener("change", calculateRequiredDays);
    endDateInput.addEventListener("change", calculateRequiredDays);





});

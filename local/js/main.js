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

        setTimeout(() => {
            alertBox.classList.add("opacity-0");
            setTimeout(() => alertBox.remove(), 300);
        }, 4000);
    }




    window.showConfirmBox = function (requestId, requestStatus, employeeId) {
        let alertBox = document.getElementById("alertBox");

        // Si no existe, crearlo dinámicamente
        if (!alertBox) {
            alertBox = document.createElement("div");
            alertBox.id = "alertBox";
            alertBox.className = "fixed left-1/2 top-1/3 -translate-x-1/2 z-10 transition-opacity duration-300 ease-in-out";
            document.body.appendChild(alertBox);
        }

        let message;

        if (requestStatus === 'Approved' || requestStatus === 'Rejected') {
            message = 'Sind Sie sicher, dass Sie diese Abwesenheit <strong>Löschen</strong> möchten?';
        }else if(requestStatus === 'Pending' ) {
            message = 'Sind Sie sicher, dass Sie diese Abwesenheit <strong>stornieren</strong> möchten?';
        }else if (employeeId){
            message = 'Möchten Sie den Benutzer wirklich löschen?';
        } else {
            message = "Sind Sie sicher, dass Sie fortfahren wollen?";
        }

        alertBox.innerHTML = `
        <div class="px-7 py-5 rounded shadow-lg bg-red-100">
        <p class="mb-3 text-base">${message}</p>
            <div class="flex justify-end gap-3">            
                <button class="bg-gray-300 px-4 py-2 rounded close-btn">Abbrechen</button>
                <button class="bg-red-400 text-white px-4 py-2 rounded"
                onclick="handleConfirm(${requestId} , '${requestStatus}', ${employeeId})">
                Bestätigen
                </button>
            </div>
        </div>
    `;

        alertBox.classList.remove("opacity-0");

        // Agregar evento al botón "Abbrechen"
        alertBox.querySelector(".close-btn").addEventListener("click", function () {
            alertBox.classList.add("opacity-0");
            setTimeout(() => alertBox.remove(), 3000);
        });
    };

    window.handleConfirm = function(requestId, requestStatus, employeeId) {
         console.log('Request ID:', requestId);
        // console.log('Request Status:', requestStatus);
         console.log('Employee ID:', employeeId);

        // Aquí agregas la lógica que necesites, como enviar el formulario o hacer otra acción
        if (requestId) {
            document.getElementById(`cancelForm-${requestId}`).submit();
        } else if (employeeId) {
            document.getElementById(`cancelForm-${employeeId}`).submit();
        }
    }




    /// tenemos que hacer que al calcular los dias de vacaciones, no cuente ni los sabados ni domingos.
    // no esta implantado
/*    const startDateInput = document.getElementById("start_date");
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
    endDateInput.addEventListener("change", calculateRequiredDays);*/





});

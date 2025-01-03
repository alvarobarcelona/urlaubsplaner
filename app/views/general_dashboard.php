<?php
require_once __DIR__ . '/../../core/Database.php';

/* // Almacenamos los mensajes de éxito o error si existen
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
 */
// Verificar si el usuario es administrador o empleado
$is_admin = ($_SESSION['role_id'] == 1);
$is_employee = ($_SESSION['role_id'] == 2);

$conn = Database::getInstance();  // Usar MySQLi
$user_name = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard General</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/locales-all.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

</head>

<body>
    <nav>
        <ul>
            <li><a href="<?php echo $is_admin ? '/vacation_app/app/views/admin_dashboard.php' : '/vacation_app/app/views/employee_dashboard.php'; ?>">Dashboard</a></li>
            <div class="ml-auto flex items-center space-x-4">
                <a href="/vacation_app/local/index.php?action=edit_profile" class="flex items-center">
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg> <b>Hallo,</b><?php echo htmlspecialchars(' ' . $user_name); ?>  
                </a>
                <a href="/vacation_app/local/index.php?action=logout"  class="items-center flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                    </svg>
                    <b>Ausloggen</b>
                </a>
            </div>
        </ul>
    </nav>

    <h2 style="display: flex; justify-content:center">Mein Kalender</h2>
    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = [];

            <?php foreach ($vacations as $vacation): ?>
                events.push({
                    title: '<?php echo $vacation['username'] . " (" . $vacation['type_name'] . ")"; ?>',
                    start: '<?php echo $vacation['start_date']; ?>',
                    end: '<?php echo $vacation['end_date']; ?>'
                });
            <?php endforeach; ?>

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                dayMaxEvents: true,
                navLinks: true,
                events: events, // Cargar eventos dinámicos desde PHP

                locale: 'de',
                height: 'auto',
                contentHeight: 'auto',
                headerToolbar: {
                    left: 'prev next today',
                    center: 'title',
                    right: 'dayGridMonth dayGridWeek timeGridDay listMonth'
                }
            });

            calendar.render(); // Renderizar el calendario en el div con id 'calendar'
        });
    </script>

<footer class="bg-gray-200 text-center pt-4 pb-4 mt-8 ">
    <p class="text-sm text-gray-600">&copy; <?php echo date("Y"); ?> ICON Vernetzte Kommunikation GmbH. By Alvaro Barcelona Peralta.</p>
    <nav class="space-x-4 text-sm text-gray-600">
        <a href="#" class="hover:underline">Kontakt</a>
        <a href="#" class="hover:underline">AGB</a>
        <a href="#" class="hover:underline">Datenschutz</a>
    </nav>
</footer>
</body>
</html>
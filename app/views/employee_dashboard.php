<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/VacationModel.php';

// Almacenamos los mensajes de éxito o error si existen
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Verifica si el usuario está logueado y es empleado
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 2) {
    header("Location: login_form.php");
    exit();
}


$user_name = $_SESSION['username'];
/* $conn = Database::getInstance(); */  // Usar MySQLi


$vacationModel = new VacationModel();
$employee_id = $_SESSION['user_id'];
//  Obtener el estado de vacaciones del empleado
$employee = $vacationModel->getEmployeeVacation($employee_id);

// Obtener las próximas vacaciones aprobadas
$next_vacation = $vacationModel->getNextApprovedVacation($employee_id);
$next_vacation_start = isset($next_vacation['start_date']) 
    ? DateTime::createFromFormat('Y-m-d', $next_vacation['start_date'])->format('d.m.Y') 
    : null;

$next_vacation_end = isset($next_vacation['end_date']) 
    ? DateTime::createFromFormat('Y-m-d', $next_vacation['end_date'])->format('d.m.Y') 
    : null;


// var_dump($employee);
$total_days = $employee['total_vacation_days'] ?? 0;
$used_days = $employee['used_vacation_days'] ?? 0;
$total_sick_days = $employee['sick_days'] ?? 0;
$total_special_holidays_days = $employee['special_holidays_days'] ?? 0;

//para recoger los datos que se muestran en Zusammenfassung der Anträge y Nächstes Ereignis
//array multidimensional
$approved_vacations = $vacationModel->getApprovedVacations(false, $employee_id);

//  Obtener el historial de solicitudes de vacaciones de los usuarios/ no tiene nada que ver con las dos tablas superiores
$vacation_history = $vacationModel->getVacationHistory($employee_id);

$type_vacation = 'N/A';

if (!empty($approved_vacations)) {
    foreach ($approved_vacations as $vacation) {
        if (isset($vacation['type_name'])) {
            $type_vacation = $vacation['type_name'];
            break;
        }
    }
}


/*foreach ($approved_vacations as $vacation) {

        switch ($vacation['type_name']) {
            case 'Urlaub':
                $used_days += $vacation['is_half_day'];
                break;
            case 'Krank':
                $total_sick_days += $vacation['is_half_day'];
                break;
            case 'Sonderurlaub':
                $total_special_holidays_days += $vacation['is_half_day'];
                break;
        }
    
}*/


$pending_days = $total_days - $used_days;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitarbeiter Übersicht</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="/vacation_app/local/js/main.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php if ($success_message || $error_message): ?>
        <div id="alertBox"
            class="fixed left-1/2 top-1/2 -translate-x-1/2 z-50 transition-opacity duration-300 ease-in-out ">
            <div class="px-7 py-5 rounded shadow-lg 
            <?php echo $success_message ? 'bg-green-100  text-green-700' : 'bg-red-100  text-red-700'; ?>">
                <span><?php echo $success_message ? $success_message : $error_message; ?></span>
            </div>
        </div>
    <?php endif; ?>


    <nav>
        <ul>
            <li><a href="employee_dashboard.php">Dashboard</a></li>
            <li><a href="/vacation_app/local/index.php?action=generalDashboard">Kalender</a></li>
            <li><a href="/vacation_app/local/index.php?action=requestVacation">Neue Abwesenheit eintragen</a></li>

            <div class="ml-auto flex items-center space-x-4 ">
                <a href="/vacation_app/local/index.php?action=edit_profile" class="flex items-center user-name">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user ">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg> <b>Hallo, </b><?php echo htmlspecialchars(' ' . $user_name); ?>
                </a>
                <a href="/vacation_app/local/index.php?action=logout" class="logout-link items-center flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                    </svg>
                    <b>Ausloggen</b>
                </a>
            </div>
        </ul>

    </nav>

    <div class="container mx-auto mt-8 space-y-8">
        <h2 class="text-2xl font-bold text-gray-700 text-center">Willkommen auf dem Mitarbeiter-Dashboard</h2>

        <!-- Resumen del estado de vacaciones -->
        <div class="flex flex-wrap justify-center gap-8">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full sm:w-1/2 lg:w-1/3">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Zusammenfassung der Anträge</h3>
                <div class="space-y-2">
                    <p><strong>Urlaubstage total:</strong> <span class="text-blue-600"><?php echo (float)$total_days; ?></span></p>
                    <p><strong>Benutzte Tage:</strong> <span class="text-blue-600"><?php echo (float)$used_days; ?></span></p>
                    <p><strong>Verbleibende Tage:</strong> <span class="text-blue-600"><?php echo (float)$pending_days; ?></span></p>
                    <p><strong>Krank:</strong> <span class="text-blue-600"><?php echo (float)$total_sick_days; ?></span></p>
                    <p><strong>Sonderurlaub:</strong> <span class="text-blue-600"><?php echo (float)$total_special_holidays_days; ?></span></p>
                </div>
            </div>

            <!-- Próximas vacaciones aprobadas -->
            <div class="bg-white p-6 rounded-lg shadow-lg w-full sm:w-1/2 lg:w-1/3">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Nächstes Ereignis</h3>
                <?php if ($next_vacation):?>

                    <div class="space-y-2">
                        <p><strong>Art: </strong> <span class="text-blue-600"><?php echo htmlspecialchars($type_vacation); ?></span></p>
                        <p><strong>Startseite: </strong> <span class="text-blue-600"><?php echo htmlspecialchars($next_vacation_start); ?></span></p>
                        <p><strong>Ende: </strong> <span class="text-blue-600"><?php echo htmlspecialchars($next_vacation_end); ?></span></p>
                    </div>
                <?php else: ?>
                    <p class="text-red-600">Sie haben keine kommenden genehmigten Anträge.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Historial de solicitudes de vacaciones -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Verlauf der Anträge</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">Art des Antrags</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Startdatum</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Enddatum</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Zeitraum</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Erstellt am</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($vacation_history)): ?>
                            <?php foreach ($vacation_history as $vacation):

                                $start_date = DateTime::createFromFormat('Y-m-d', $vacation['start_date'])->format('d/m/Y');
                                $end_date = DateTime::createFromFormat('Y-m-d', $vacation['end_date'])->format('d/m/Y');
                                $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $vacation['created_at'])->format('d/m/Y H:i');


                            ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($vacation['type_name']); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($start_date); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($end_date); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($vacation['half_day_period'] ?? 'Ganztag'); ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($vacation['status']); ?></td>                                   
                                    <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($created_at); ?></td>
                                    <td class="border border-gray-300 px-4 py-2 text-center">
                                        <?php if ($vacation['status'] == 'Pending' || $vacation['status'] == 'Approved'): ?>
                                            <form action="/vacation_app/local/index.php?action=cancelVacation" method="post">
                                                <input type="hidden" name="request_id" value="<?php echo $vacation['id']; ?>">
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                                    onclick="return confirm('Sind Sie sicher, dass Sie diese Abwesenheit stornieren möchten?');">
                                                    Stornieren
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">Sie haben keine ausstehenden Anträge</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>
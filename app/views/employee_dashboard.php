<?php
/* session_start(); */
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/VacationModel.php';



// Almacenamos los mensajes de éxito o error si existen
/* $success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
  */

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


//  Obtener el historial de solicitudes de vacaciones
$vacation_history = $vacationModel->getVacationHistory($employee_id);

// Obtener las próximas vacaciones aprobadas
$next_vacation = $vacationModel->getNextApprovedVacation($employee_id);
// var_dump($employee);
$total_days = $employee['total_vacation_days'] ?? 0;
var_dump($total_days);

$used_days = $employee['used_vacation_days'] ?? 0;
$total_sick_days = $employee['sick_days'] ?? 0;
$total_special_holidays_days = $employee['special_holidays_days'] ?? 0;


foreach ($vacation_history as $vacation) {
    if (isset($vacation['is_half_day']) && $vacation['is_half_day'] == 0.5) {
        switch ($vacation['type_name']) {
            case 'Urlaub': // Tipo de vacaciones
                $used_days += $vacation['is_half_day'];
                break;
            case 'Krank': // Días de enfermedad
                $total_sick_days += $vacation['is_half_day'];
                break;
            case 'Sonderurlaub': // Días especiales
                $total_special_holidays_days += $vacation['is_half_day'];
                break;
        }
    }
}

$pending_days = $total_days - $used_days;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitarbeiter Übersicht</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
</head>

<body>
    <nav>
        <ul>
            <li><a href="employee_dashboard.php">Dashboard</a></li>
            <li><a href="/vacation_app/local/index.php?action=generalDashboard">Mein Kalender</a></li>
            <li><a href="/vacation_app/local/index.php?action=requestVacation">Neue Abwesenheit</a></li>
            <div style="margin-left: auto;">
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                    </svg> <a style="color: black;" href="/vacation_app/local/index.php?action=logout"> Ausloggen</a></span>
                <a href="/vacation_app/local/index.php?action=edit_profile"><span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg> Hi,<?php echo htmlspecialchars(' ' . $user_name); ?></span></a>
            </div>
        </ul>

    </nav>

    <h2 class="dashboard-title">Willkommen beim Employee Dashboard</h2>

    <!-- Resumen del estado de vacaciones -->
    <div class="vacation-container">
        <div class="vacation-summary">
            <h3>Zusammenfassung der Anträge</h3>
            <div class="vacation-stats">
                <p><strong>Urlaubstage total:</strong> <span><?php echo $total_days; ?></span></p>
                <p><strong>Benutzte Tage:</strong> <span><?php echo $used_days; ?></span></p>
                <p><strong>Verbleibende Tage:</strong> <span><?php echo $pending_days; ?></span></p>
                <p><strong>Krank:</strong> <span><?php echo $total_sick_days; ?></span></p>
                <p><strong>Sonderurlaub:</strong> <span><?php echo $total_special_holidays_days; ?></span></p>
            </div>
        </div>

        <!-- Próximas vacaciones aprobadas -->
        <div class="next-vacation">
            <h3>Nächstes Ereignis</h3>
            <?php if ($next_vacation): ?>
                <div class="vacation-dates">
                    <?php
                    // Convertir las fechas a formato europeo (día/mes/año)
                    $next_vacation_start = DateTime::createFromFormat('Y-m-d', $vacation['start_date'])->format('d/m/Y');
                    $next_vacation_end = DateTime::createFromFormat('Y-m-d', $vacation['end_date'])->format('d/m/Y');

                    // var_dump($vacation);
                    ?>

                    <?php if (!empty($vacation) && isset($vacation['type_name'])): ?>
                        <p><strong>Art: </strong> <span><?php echo htmlspecialchars($vacation['type_name']); ?></span></p>
                    <?php else: ?>
                        <p><strong>Art: </strong> <span>N/A</span></p>
                    <?php endif; ?>
                    <p><strong>Startseite: </strong> <span><?php echo htmlspecialchars($next_vacation_start); ?></span></p>
                    <p><strong>Ende: </strong> <span><?php echo htmlspecialchars($next_vacation_end); ?></span></p>

                </div>
            <?php else: ?>
                <p class="no-vacation-msg">Sie haben keine kommenden genehmigten Anträge.</p>
            <?php endif; ?>
        </div>
    </div>



    <!-- Historial de solicitudes de vacaciones -->
    <div class="vacation-history">
        <h3 style="margin-left: 77px;">Verlauf der Anträge:</h3>
        <table>
            <thead>
                <tr>
                    <th>Startdatum</th>
                    <th>Enddatum</th>
                    <th>Zeitraum</th>
                    <th>Status</th>
                    <th>Art des Antrags</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($vacation_history)): ?>
                    <?php foreach ($vacation_history as $vacation):
                        // Convertir las fechas a formato europeo (día/mes/año)
                        $start_date = DateTime::createFromFormat('Y-m-d', $vacation['start_date'])->format('d/m/Y');
                        $end_date = DateTime::createFromFormat('Y-m-d', $vacation['end_date'])->format('d/m/Y');
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($start_date); ?></td>
                            <td><?php echo htmlspecialchars($end_date); ?></td>
                            <td><?php echo htmlspecialchars($vacation['half_day_period'] ?? 'Ganztag'); ?></td>
                            <td><?php echo htmlspecialchars($vacation['status']); ?></td>
                            <td><?php echo htmlspecialchars($vacation['type_name']); ?></td>
                            <td>
                                <?php if ($vacation['status'] == 'Pending' || $vacation['status'] == 'Approved'): ?>
                                    <form action="/vacation_app/local/index.php?action=cancelVacation" method="post" style="display:inline;">
                                        <input type="hidden" name="request_id" value="<?php echo $vacation['id']; ?>">
                                        <button type="submit" onclick="return confirm('Sind Sie sicher, dass Sie diese Bewerbung stornieren möchten?');">Stornieren</button>
                                    </form>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Sie haben keine ausstehenden Anträge</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?>
        ICON Vernetzte Kommunikation GmbH. By Alvaro Barcelona Peralta.</p>
    <nav class="footer-nav">
        <a href="#">Kontakt</a> |
        <a href="#">AGB</a> |
        <a href="#">Datenschutz</a>
    </nav>
</footer>

</html>
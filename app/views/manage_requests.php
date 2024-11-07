<?php
 /* session_start(); */
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../controllers/VacationController.php';

// Recuperar mensajes de éxito o error
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Limpiar los mensajes de la sesión después de mostrarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

$user_name = $_SESSION['username'];

// Asegúrate de que solo los administradores tengan acceso
/* if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header("Location: login_form.php");
    exit(); 
} */
?>


<!--  $conn = Database::getInstance();  // Usar MySQLi
// Obtener todas las solicitudes de vacaciones pendientes
$sql = "SELECT vacation_requests.id, users.username, vacation_requests.start_date, vacation_requests.end_date, vacation_requests.status, vacation_types.type_name, departments.department_name
        FROM vacation_requests 
        JOIN users ON vacation_requests.employee_id = users.id
        JOIN vacation_types ON vacation_requests.vacation_type_id = vacation_types.id
        JOIN departments ON users.department_id = departments.id
        WHERE vacation_requests.status = 'Pending'";
$result = $conn->query($sql); -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anträge verwalten</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css"> 
    <script src="/vacation_app/local/js/main.js"></script> 
</head>
<body>
    <nav>
        <ul>
        <li><a href="/vacation_app/app/views/admin_dashboard.php">Dashboard</a></li>
        <li><a href="/vacation_app/local/index.php?action=generalDashboard">Kalender anzeigen</a></li>
        <div style="margin-left: auto;">
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class = "icon-close-session"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg> <a style="color: black;" href="/vacation_app/local/index.php?action=logout"> Ausloggen</a></span>
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg> Wilkommen,<?php echo htmlspecialchars($user_name); ?></span>
            </div>

        </ul>
    </nav>


    <?php if ($success_message): ?>
        <div class="alert alert-success" id="alertBox">
            <span><?php echo $success_message; ?></span>
            <span class="close-btn" onclick="closeAlert()">&times;</span>
        </div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-error" id="alertBox">
            <span><?php echo $error_message; ?></span>
            <span class="close-btn" onclick="closeAlert()">&times;</span>
        </div>
    <?php endif; ?>

    <h2 style="margin-left: 70px;">Anträge, die zur Überprüfung stehen</h2>

    <table>
        <thead>
            <tr>
                <th>Mitarbeiter</th>
                <th>Abteilung</th>
                <th>Art des Antrags</th>
                <th>Startdatum</th>
                <th>Enddatum</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
    <?php 
    if (!empty($requests)) {
        foreach ($requests as $request) { ?>
            <tr>
                <td><?php echo $request['username']; ?></td>
                <td><?php echo $request['department_name']; ?></td>
                <td><?php echo $request['type_name']; ?></td>
                <td><?php echo $request['start_date']; ?></td>
                <td><?php echo $request['end_date']; ?></td>
                <td>
                    <form action="/vacation_app/local/index.php?action=approveRejectRequest" method="post">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <button type="submit" name="action" value="approve">Aprobar</button>
                        <button type="submit" name="action" value="reject">Rechazar</button>
                    </form>
                </td>
            </tr>
        <?php  } 
       } else { ?> 
        <tr>
            <td colspan="4">Keine offenen Anträge</td>
        </tr>
    <?php } ?>
</tbody>

    </table>
</body>
</html>

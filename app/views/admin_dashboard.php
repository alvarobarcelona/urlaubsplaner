<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/VacationModel.php';

// Almacenamos los mensajes de éxito o error si existen
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);




// Verifica si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header("Location: login_form.php");
    exit();
}

$user_name = $_SESSION['username'];
$conn = Database::getInstance();

// Obtener la lista de empleados
$sql = "SELECT id, username, total_vacation_days FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrationsbereich</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="/vacation_app/local/js/main.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100 min-h-screen">
    <?php if ($success_message): ?>
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
            <span><?php echo $success_message; ?></span>
        </div>
    <?php elseif ($error_message): ?>
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
            <span><?php echo $error_message; ?></span>
        </div>
    <?php endif; ?>

    <nav>
        <ul class="nav-menu">
            <li><a href='/vacation_app/app/views/admin_dashboard.php'>Dashboard</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Anträge</a>
                <ul class="dropdown-menu">
                    <li><a href="/vacation_app/local/index.php?action=manageRequests">Offene Anträge</a></li>
                    <li><a href="/vacation_app/local/index.php?action=createVacationRequestAdmin">Neue Abwesenheit als Admin</a></li>
                    <li><a href="/vacation_app/local/index.php?action=showRequestHistory">Verlauf der Anträge</a></li>
                </ul>
            </li>

            <!-- Grupo de navegación para "Mitarbeiter" -->
            <li class="dropdown">
                <a href="#" class="dropdown-toggle">Mitarbeiter</a>
                <ul class="dropdown-menu">
                    <li><a href="/vacation_app/local/index.php?action=editEmployee">Benutzerdaten bearbeiten</a></li>
                    <li><a href="/vacation_app/local/index.php?action=generalDashboard">Kalender anzeigen</a></li>
                </ul>
            </li>
            <div class="ml-auto flex items-center space-x-4">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg>
                    <b>Hallo</b>, <?php echo htmlspecialchars($user_name); ?>
                </span>
                <a href="/vacation_app/local/index.php?action=logout" class="logout-link items-center flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                    </svg>
                    <b>Ausloggen</b>
                </a>
            </div>
        </ul>
    </nav>

    <div class="container mx-auto mt-8">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Übersicht alle Mitarbeiter</h3>
        <table class="table-auto w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="px-4 py-2 text-left">Mitarbeiter</th>
                    <th class="px-4 py-2 text-left">Department</th>
                    <th class="px-4 py-2 text-left">Total Urlaub Tages</th>
                    <th class="px-4 py-2 text-left">Benutze Urlaub</th>
                    <th class="px-4 py-2 text-left">Anstehende Urlaubstage</th>
                    <th class="px-4 py-2 text-left">Krankentage</th>
                    <th class="px-4 py-2 text-left">Sonderurlaub Tage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $vacationModel = new VacationModel();
                $employee_vacation_data = $vacationModel->getEmployeesVacationData();
                ?>
                <?php foreach ($employee_vacation_data as $employee): ?>
                    <tr class="hover:bg-blue-100">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['username']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['department_name']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['total_vacation_days']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['used_vacation_days']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['total_vacation_days'] - $employee['used_vacation_days']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars((float)$employee['sick_days'] + (float)$employee['total_half_days']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($employee['special_holidays_days']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="bg-white p-6 mt-8 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Verteilung von Urlaubstagen an Mitarbeiter</h2>
            <form action="/vacation_app/local/index.php?action=updateHolidays" method="post" class="space-y-4">
                <div>
                    <label for="employee" class="block text-sm font-medium text-gray-700 mb-1">Wählen Sie einen Mitarbeiter aus:</label>
                    <select name="employee_id" id="employee" required
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
                        <option value="">-- Wählen Sie einen Mitarbeiter aus --</option>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo $row['username'] . ' - Tage pro Jahr : ' . $row['total_vacation_days']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="vacation_days" class="block text-sm font-medium text-gray-700 mb-1">Verteilen Sie die neuen Gesamturlaubstage:</label>
                    <input type="number" name="total_vacation_days" id="vacation_days" min="1" required
                        class="block w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
                </div>
                <div class= "flex justify-center">
                     <button type="submit" class="w-64 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Tage aktualisieren</button>
                </div>
               
            </form>
        </div>
    </div>

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

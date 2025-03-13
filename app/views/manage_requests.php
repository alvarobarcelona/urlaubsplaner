<?php

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anträge verwalten</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="/vacation_app/local/js/main.js"></script>
</head>

<body>
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

    <div class="container mx-auto mt-8 pb-24">
        <?php if ($success_message): ?>
            <div id="alertBox" class="alert-box inset-0 flex items-center justify-center">
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-3 rounded-lg shadow-lg max-w-lg text-center">
                    <span><?php echo $success_message; ?></span>
                </div>
            </div>
        <?php elseif ($error_message): ?>
            <div id="alertBox" class="alert-box inset-0 flex items-center justify-center">
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-3 rounded-lg shadow-lg max-w-lg text-center">
                    <span><?php echo $error_message; ?></span>
                </div>
            </div>
        <?php endif; ?>


        <h2 class="text-xl font-bold text-gray-700 mb-4">Anträge, die zur Überprüfung stehen</h2>
        <table class="table-auto w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="px-4 py-2 text-left">Mitarbeiter</th>
                    <th class="px-4 py-2 text-left">Abteilung</th>
                    <th class="px-4 py-2 text-left">Art des Antrags</th>
                    <th class="px-4 py-2 text-left">Startdatum</th>
                    <th class="px-4 py-2 text-left">Enddatum</th>
                    <th class="px-4 py-2 text-left">Erstellt am</th>
                    <th class="px-4 py-2 text-left">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($requestsPending)) {
                    foreach ($requestsPending as $request):

                        $start_date = DateTime::createFromFormat('Y-m-d', $request['start_date'])->format('d/m/Y');
                        $end_date = DateTime::createFromFormat('Y-m-d', $request['end_date'])->format('d/m/Y');
                        $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $request['created_at'])->format('d/m/Y H:i');

                ?>
                        <tr class="hover:bg-blue-100">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($request['username']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($request['department_name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($request['type_name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($start_date); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($end_date); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($created_at); ?></td>
                            <td class="px-4 py-2 text-center">
                                <form action="/vacation_app/local/index.php?action=approveRejectRequest" method="post" class="inline-block">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="approve"
                                        class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Genehmigen</button>
                                </form>
                                <form action="/vacation_app/local/index.php?action=approveRejectRequest" method="post" class="inline-block">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" name="action" value="reject"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Ablehnen</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="text-center px-4 py-2">Keine offenen Anträge</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>
<?php
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../controllers/VacationController.php';

$user_name = $_SESSION['username'];

// Almacenamos los mensajes de éxito o error si existen
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
 
// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verlauf der Anträge</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/vacation_app/local/js/main.js"></script>

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

        <h2 class="text-2xl font-bold text-gray-700 mb-6">Verlauf der Anträge</h2>

        <table class="table-auto w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <!-- <th class="px-4 py-2">ID</th> -->
                    <th class="px-4 py-2 text-left">Mitarbeiter</th>
                    <th class="px-4 py-2 text-left">Art des Antrags</th>
                    <th class="px-4 py-2 text-left">Startdatum</th>
                    <th class="px-4 py-2 text-left">Enddatum</th>
                    <th class="px-4 py-2 text-left">Erstellt am</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($allRequests)) { ?>
                    <?php foreach ($allRequests as $request):

                        $start_date = DateTime::createFromFormat('Y-m-d', $request['start_date'])->format('d/m/Y');
                        $end_date = DateTime::createFromFormat('Y-m-d', $request['end_date'])->format('d/m/Y');
                        $created_at = DateTime::createFromFormat('Y-m-d H:i:s', $request['created_at'])->format('d/m/Y H:i');

                    ?>
                        <tr class="hover:bg-blue-100">
                            <!-- <td class="px-4 py-2"><?php echo htmlspecialchars($request['id']); ?></td> -->
                            <td class="px-4 py-2"><?php echo htmlspecialchars($request['username']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($request['type_name']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($start_date); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($end_date); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($created_at); ?></td>
                            <td class="px-4 py-2">
                                <span class="<?php echo $request['status'] === 'Approved' ? 'text-green-500' : ($request['status'] === 'Rejected' ? 'text-red-500' : 'text-yellow-500'); ?>">
                                    <?php echo htmlspecialchars($request['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <form action="/vacation_app/local/index.php?action=revertRequest" method="post" class="inline-block">
                                    <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Antrag Löschen
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2">Keine Auftrag</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>


    <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>
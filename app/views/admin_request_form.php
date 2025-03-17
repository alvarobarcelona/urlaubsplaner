<?php
$user_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Abwesenheit eintragen</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <li><a href="/vacation_app/local/index.php?action=createVacationRequestAdmin">Abwesenheit eintragen (Als Admin)</a></li>
                    <li><a href="/vacation_app/local/index.php?action=showRequestHistory">Verlauf der Anträge</a></li>
                </ul>
            </li>


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

    <div class="bg-gray-100 container flex items-center justify-center mx-auto align-middle ">



        <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full  ">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Abwesenheit für Mitarbeiter eintragen</h2>

            <form action="/vacation_app/local/index.php?action=createVacationRequestAdmin" method="post" class="space-y-4">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Mitarbeiter auswählen:</label>
                    <select id="employee_id" name="employee_id" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Wählen Sie einen Mitarbeiter</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selección de tipo de ausencia -->
                <div>
                    <label for="vacation_type_id" class="block text-sm font-medium text-gray-700 mb-1">Art des Antrags:</label>
                    <select id="vacation_type_id" name="vacation_type_id" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Wählen Sie eine Antragsart</option>
                        <?php foreach ($vacation_types as $type): ?>
                            <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['type_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha de inicio y hora -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Startdatum:</label>
                    <input type="date" id="start_date" name="start_date" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <!-- Fecha de fin y hora -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Enddatum:</label>
                    <input type="date" id="end_date" name="end_date" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Startzeit (Optional):</label>
                    <select id="start_time" name="start_time" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Volltag</option>
                        <option value="08:00">Vormittag - 08:00</option>
                        <option value="12:00">Nachmittag - 12:00</option>
                    </select>
                </div>

                

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Endzeit (Optional):</label>
                    <select id="end_time" name="end_time" class="p-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Volltag</option>
                        <option value="12:00">Vormittag - 12:00</option>
                        <option value="16:00">Nachmittag - 16:00</option>
                    </select>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Abwesenheit eintragen</button>
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2" onclick="window.location.href='/vacation_app/app/views/admin_dashboard.php';">Abbrechen</button>
                </div>
            </form>
        </div>
    </div>


    <?php include __DIR__ . '/footer.php'; ?>
</body>

</html>
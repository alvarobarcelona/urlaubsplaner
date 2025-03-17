<?php
$user_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Mitarbeiter bearbeiten</title>
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

    <div class="container max-w-4xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
            Wählen Sie einen Mitarbeiter aus, um sein Profil zu bearbeiten
        </h2>

        <form action="/vacation_app/local/index.php?action=editEmployee" method="post">
            <div>
                <label for="employee" class="block text-gray-700 font-medium mb-2">
                    Wählen Sie einen Mitarbeiter aus:*
                </label>
                <select name="employee_id" id="employee" class="w-full p-2 border rounded-lg text-gray-700" required>
                    <option value="">-- Wählen Sie aus --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>">
                            <?php echo htmlspecialchars($employee['username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="username" class="block text-gray-700 font-medium mb-2">Neuer Benutzername:</label>
                <input type="text" name="username" id="username" class="w-full p-2 border rounded-lg text-gray-700" value="">
            </div>

            <div>
                <label for="department" class="block text-gray-700 font-medium mb-2">Neue Abteilung:</label>
                <select name="department_id" id="department" class="w-full p-2 border rounded-lg text-gray-700">
                    <option value="">-- Wählen Sie eine Abteilung aus --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="vacation_days" class="block text-gray-700 font-medium mb-2">Neue Gesamt-Urlaubstage:</label>
                <input type="number" name="total_vacation_days" id="vacation_days" class="w-full p-2 border rounded-lg text-gray-700" min="1">
            </div>


            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Neues Passwort:</label>
                <input type="password" name="password" id="password" class="w-full p-2 border rounded-lg text-gray-700" value="">
            </div>

            <div>
                <label for="role" class="block text-gray-700 font-medium mb-2">Neue Rolle:</label>
                <select name="role_id" id="role" class="w-full p-2 border rounded-lg text-gray-700">
                    <option value="">-- Wählen Sie eine Roll aus --</option>
                    <?php foreach ($roles as $roll): ?>
                        <option value="<?php echo $roll['id'] ?>">
                            <?php echo htmlspecialchars($roll['role_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>



            <div class="flex justify-between items-center mt-4">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Änderungen speichern
                </button>
                <button type="button" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                    onclick="window.location.href='/vacation_app/app/views/admin_dashboard.php';">
                    Abbrechen
                </button>
            </div>

            <p class="text-sm text-gray-600 mt-2">* Pflichtfeld</p>
        </form>
    </div>

    <?php include __DIR__ . '/footer.php'; ?>

</body>

</html>
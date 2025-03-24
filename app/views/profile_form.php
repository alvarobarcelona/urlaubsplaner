<?php
$user_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile bearbeiten</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex flex-col min-h-screen">

<nav>
    <ul>
        <li><a href="/vacation_app/app/views/employee_dashboard.php">Dashboard</a></li>
        <li><a href="/vacation_app/local/index.php?action=generalDashboard">Mein Kalender</a></li>
        <li><a href="/vacation_app/local/index.php?action=requestVacation">Neue Abwesenheit eintragen</a></li>
        <div class="ml-auto flex items-center space-x-4">
            <a href="/vacation_app/local/index.php?action=edit_profile" class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/>
                </svg>
                <b>Hallo,</b><?php echo htmlspecialchars(' ' . $user_name); ?>
            </a>
            <a href="/vacation_app/local/index.php?action=logout" class="items-center flex">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                    <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                </svg>
                <b>Ausloggen</b>
            </a>
        </div>
    </ul>

</nav>
<div class="h-screen flex items-center justify-center">
    <div class=" container max-w-lg mx-auto mt-8 ">
        <div class=" bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 text-center mb-4 mt-10">Benutzerprofil</h2>
            <p class="text-gray-600 text-center mb-6">Mit diesem Formular können Sie im Falle eines Fehlers einige Ihrer
                Daten aktualisieren. In anderen Fällen wenden Sie sich bitte an den Administrator.</p>

            <!-- Formulario para editar el perfil -->
            <form action="/vacation_app/local/index.php?action=updateProfile" method="post">
                <!-- Benutzername -->
                <div>
                    <label for="username" class="block text-gray-700 font-medium mb-2">Benutzername:</label>
                    <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?php echo htmlspecialchars($employee['username']); ?>"
                            class=" w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Vollständiger Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-2">Vollständiger Name:</label>
                    <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?php echo htmlspecialchars($employee['name']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- E-Mail -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-2">E-Mail:</label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($employee['email']); ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Abteilung -->
                <div>
                    <label for="department" class="block text-gray-700 font-medium mb-2">Abteilung:</label>
                    <select
                            name="department_id"
                            id="department"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="" disabled>-- Wählen Sie eine Abteilung aus --</option>
                        <?php foreach ($departments as $department): ?>
                            <option value="<?php echo $department['id']; ?>"
                                <?php echo ($employee['department_id'] == $department['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($department['department_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <button
                            type="submit"
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                        Speichern
                    </button>
                    <button
                            type="button"
                            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                            onclick="window.location.href='/vacation_app/app/views/employee_dashboard.php';">
                        Abbrechen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include __DIR__ . '/footer.php'; ?>

</body>

</html>

<?php
// Recuperar mensajes de éxito o error
$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';

// Limpiar los mensajes de la sesión después de mostrarlos
unset($_SESSION['success_message'], $_SESSION['error_message']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-3xl" style="margin: 80px">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-4">Registrierung</h2>
        <p class="text-sm text-gray-600 text-center mb-6">Bitte geben Sie Ihre persönlichen Daten ein.</p>

        <form action="/vacation_app/local/index.php?action=signup" method="post" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Benutzername:*</label>
                <input type="text" id="username" name="username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passwort:*</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vollständiger Name:*</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-Mail:*</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Abteilung:*</label>
                <select name="department_id" id="department_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                    <option value="">-- Wählen Sie eine Abteilung --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Código de administrador (opcional) -->
            <div>
                <label for="admin_code" class="block text-sm font-medium text-gray-700 mb-1">
                    Autorisierungscode, um ein Administrator zu werden (optional):
                </label>
                <input type="text" name="admin_code" id="admin_code"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Registrieren
            </button>

            <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
                <p>* Pflichtfeld</p>
                <p>
                    Haben Sie schon ein Konto?
                    <a href="/vacation_app/app/views/login_form.php" class="text-blue-500 hover:underline">Hier einloggen</a>
                </p>
            </div>
        </form>
    </div>
</body>

</html>
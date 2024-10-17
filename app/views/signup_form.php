<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
</head>
<body class="body-signup">
    <div class="signup-container">
        <h2 class="signup-title">Registrierung</h2>
        <form action="/vacation_app/local/index.php?action=signup" method="post" class="signup-form">
            <!-- Nombre completo -->
            <div class="form-group">
                <label for="name">Vollständiger Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <!-- Correo Electrónico -->
            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Nombre de Usuario -->
            <div class="form-group">
                <label for="username">Name des Benutzers:(Username)</label>
                <input type="text" id="username" name="username" required>
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Código de administrador (opcional) -->
            <div class="form-group">
                <label for="admin_code">Autorisierungscode, um ein Administrator zu werden (optional):</label>
                <input type="text" name="admin_code" id="admin_code">
            </div>

            <!-- Selección de Departamento -->
            <div class="form-group">
                <label for="department_id">Abteilung:</label>
                <select name="department_id" id="department_id" required>
                    <option value="">-- Wählen Sie eine Abteilung --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>">
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Botón de registro -->
            <button type="submit" class="signup-btn">Registrieren</button>

            <p class="login-link">Haben Sie schon ein Konto? <a href="/vacation_app/app/views/login_form.php">Hier einloggen</a></p>
        </form>
    </div>
</body>
</html>

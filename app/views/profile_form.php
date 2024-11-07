<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil bearbeiten</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
</head>

<body class="body-profile">

    <div class="profile-container">
        <h2 class="profile-title">Benutzerprofil</h2>
        <p>Mit diesem Formular können Sie im Falle eines Fehlers einige Ihrer Daten aktualisieren. In anderen Fällen wenden Sie sich bitte an den Administrator.</p>
        <!-- Formulario para editar el perfil -->
        <form action="/vacation_app/local/index.php?action=updateProfile" method="post" class="profile-details">
            <div class="form-group">
                <label for="username"><strong>Benutzername:</strong></label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>">
            </div>
            <div class="form-group">
                <label for="name"><strong>Vollständiger Name:</strong></label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>">
            </div>
            <div class="form-group">
                <label for="email"><strong>E-Mail:</strong></label>
                <input style="width: 300px;" type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>">
            </div>
            <div class="form-group">
                <label for="department"><strong>Abteilung:</strong></label>
                <select name="department_id" id="department" class="form-select">
                    <option value="" disabled>-- Wählen Sie eine Abteilung aus --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>"
                            <?php echo ($employee['department_id'] == $department['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($department['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="text-align:center">
                <button type="submit" class="edit-profile-btn">Speichern</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='/vacation_app/app/views/employee_dashboard.php';">Abbrechen </button>

            </div>


        </form>
    </div>
</body>

</html>
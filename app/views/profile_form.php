<?php 
$user_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil bearbeiten</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
</head>

<body class="body-profile">

<nav>
        <ul>
            <li><a href="employee_dashboard.php">Dashboard</a></li>
            <li><a href="/vacation_app/local/index.php?action=generalDashboard">Mein Kalender</a></li>
            <li><a href="/vacation_app/local/index.php?action=requestVacation">Neue Abwesenheit eintragen</a></li>
            <div style="margin-left: auto;">
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                    </svg> <a style="color: black;" href="/vacation_app/local/index.php?action=logout"> Ausloggen</a></span>
                <a href="/vacation_app/local/index.php?action=edit_profile"><span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                        </svg> Hi,<?php echo htmlspecialchars(' ' . $user_name); ?></span></a>
            </div>
        </ul>

    </nav>

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
                <select name="department_id" id="department" class="">
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

<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?>
        ICON Vernetzte Kommunikation GmbH. By Alvaro Barcelona Peralta.</p>
    <nav class="footer-nav">
        <a href="#">Kontakt</a> |
        <a href="#">AGB</a> |
        <a href="#">Datenschutz</a>
    </nav>
</footer>

</html>
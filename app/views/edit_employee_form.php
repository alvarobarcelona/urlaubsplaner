<?php
$user_name = $_SESSION['username'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css"> 
    <title>Mitarbeiter bearbeiten</title>
</head>

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

            <div style="margin-left: auto;">
                <a href="/vacation_app/local/index.php?action=logout"  class="logout-link">
                    <span id="logout">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="icon-close-session">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z" />
                        </svg>
                        <b>Ausloggen</b>
                    </span>
                </a>
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                    </svg> <b>Hallo</b>, <?php echo htmlspecialchars($user_name); ?></span>
            </div>
        </ul>

    </nav>
<body class="body-edit-employee">
    <div class="edit-employee-container">
        <h2 class="edit-employee-title">Wählen Sie einen Mitarbeiter aus, um sein Profil zu bearbeiten</h2>

        <form action="/vacation_app/local/index.php?action=editEmployee" method="post" class="edit-employee-form">
            <!-- Seleccionar un Empleado -->
            <div class="form-group">
                <label for="employee" class="form-label">Wählen Sie einen Mitarbeiter aus:*</label>
                <select username="employee_id" id="employee" class="form-select" required>
                    <option value="">-- Wählen Sie einen Mitarbeiter aus --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        
            <div class="form-group">
                <label for="username" class="label">Neuer Benutzername:</label>
                <input type="text" name="username" id="username" class="form-input" value=" ">
            </div>


            <div class="form-group">
                <label for="department">Neue Abteilung:</label>
                  <select name="department_id" id="department" class="form-select">
                        <option value="">-- Wählen Sie eine Abteilung aus --</option>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department['id']; ?>">
                    <?php echo htmlspecialchars($department['department_name']); ?>
                         </option>
                    <?php endforeach; ?>
                  </select>
            </div>


            <div class="form-group">
                <label for="vacation_days" >Neue Gesamt-Urlaubstage:</label>
                <input type="number" name="total_vacation_days" id="vacation_days" class="form-input" min="1">
            </div>

            <div class="form-group">
                <label for="password">Neues Passwort:</label>
                <input type="password" name="password" id="password" value="">
            </div>

            <div class="group-btn-edit">
                <button type="submit" class="edit-employee-btn">Änderungen speichern</button>
                
                <button type="button" class="cancel-btn" onclick="window.location.href='/vacation_app/app/views/admin_dashboard.php';">Abbrechen </button>
            </div>
            <p>* Pflichtfeld</p>
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

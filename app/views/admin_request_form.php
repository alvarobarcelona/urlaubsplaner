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
<body class="body-request-vacation">
    <div class="request-vacation-container">
        <h2 class="request-vacation-title">Abwesenheit für Mitarbeiter eintragen</h2>

        <form action="/vacation_app/local/index.php?action=createVacationRequestAdmin" method="post" class="request-vacation-form">
            <!-- Selección de empleado -->
            <div class="form-group">
                <label for="employee_id" class="form-label">Mitarbeiter auswählen:</label>
                <select id="employee_id" name="employee_id" class="form-input" required>
                    <option value="">Wählen Sie einen Mitarbeiter</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Selección de tipo de ausencia -->
            <div class="form-group">
                <label for="vacation_type_id" class="form-label">Art des Antrags:</label>
                <select id="vacation_type_id" name="vacation_type_id" class="form-input" required>
                    <option value="">Wählen Sie eine Antragsart</option>
                    <?php foreach ($vacation_types as $type): ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['type_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Fecha de inicio y hora -->
            <div class="form-group">
                <label for="start_date" class="form-label">Startdatum:</label>
                <input type="date" id="start_date" name="start_date" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="start_time" class="form-label">Startzeit:(Optional)</label>
                <select id="start_time" name="start_time" class="form-input">
                    <option value="">Full Day</option>
                    <option value="08:00">Vormittag - 08:00</option>
                    <option value="12:00">Nachmittag - 12:00</option>
                </select>
            </div>

            <!-- Fecha de fin y hora -->
            <div class="form-group">
                <label for="end_date" class="form-label">Enddatum:</label>
                <input type="date" id="end_date" name="end_date" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="end_time" class="form-label">Endzeit:(Optional)</label>
                <select id="end_time" name="end_time" class="form-input">
                    <option value="">Full Day</option>
                    <option value="12:00">Vormittag - 12:00</option>
                    <option value="16:00">Nachmittag - 16:00</option>
                </select>
            </div>

            <!-- Botones de acción -->
            <div style="margin-top: 50px;">
                <button type="submit" class="form-btn">Antrag abschicken</button>
                <button type="button" class="form-btn" onclick="window.location.href='/vacation_app/app/views/admin_dashboard.php';">Abbrechen</button>
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

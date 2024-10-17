<?php

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../models/VacationModel.php';

// Almacenamos los mensajes de éxito o error si existen
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Eliminamos los mensajes de la sesión después de cargarlos
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);




// Verifica si el usuario es administrador
 if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header("Location: login_form.php");
    exit();
} 

$user_name = $_SESSION['username'];
$conn = Database::getInstance();  // Usar MySQLi

// Obtener la lista de empleados
$sql = "SELECT id, username, total_vacation_days FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrationsbereich</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css"> 
    <script src="/vacation_app/local/js/main.js"></script> 

</head>
<body>
<?php if ($success_message): ?>
    <div class="alert alert-success" id="alertBox">
        <span><?php echo $success_message; ?></span>
        <span class="close-btn" onclick="closeAlert()">&times;</span>
    </div>
<?php elseif ($error_message): ?>
    <div class="alert alert-error" id="alertBox">
        <span><?php echo $error_message; ?></span>
        <span class="close-btn" onclick="closeAlert()">&times;</span>
    </div>
<?php endif; ?>

    <nav>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="general_dashboard.php">Kalender anzeigen</a></li>
            <li><a href="/vacation_app/local/index.php?action=manageRequests">Ansicht Offene Anträge</a></li>
            <li><a href="/vacation_app/local/index.php?action=editEmployee">Benutzerdaten bearbeiten</a></li>

             <div style="margin-left: auto;">
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class = "icon-close-session"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg> <a style="color: black;" href="/vacation_app/local/index.php?action=logout"> Ausloggen</a></span>
                <span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="icon-user"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg> Wilkommen,<?php echo htmlspecialchars($user_name); ?></span>
            </div>
        </ul>

    </nav>

    <h3 style="margin-left: 15px;">Übersicht alle Mitarbeiter</h3>

    <table>
    <thead>
        <tr>
            <th>Mitarbeiter</th>
            <th>Department</th>           
            <th>Total Urlaub Tages</th>
            <th>Benutze Urlaub</th>
            <th>Urlaubs pending</th>
            <th>Krank</th>
            <th>Sonder Urlaub</th>
            

        </tr>
    </thead>
    <tbody>
        <?php  
        $vacationModel = new VacationModel();
          $employee_vacation_data = $vacationModel->getEmployeesVacationData();
         ?>
           <?php foreach ($employee_vacation_data as $employee): ?>
            <tr>
                <td><?php echo htmlspecialchars($employee['username']); ?></td>
                <td><?php echo htmlspecialchars($employee['department_name']); ?></td>
                <td><?php echo htmlspecialchars($employee['total_vacation_days']); ?></td>
                <td><?php echo htmlspecialchars($employee['used_vacation_days']); ?></td>
                <td><?php echo htmlspecialchars($employee['total_vacation_days'] - $employee['used_vacation_days']); ?></td>
                <td><?php echo htmlspecialchars($employee['sick_days']); ?></td>
                <td><?php echo htmlspecialchars($employee['special_holidays_days']); ?></td>
                
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- // Obtener la lista de empleados -->
<?php
$sql = "SELECT id, username, total_vacation_days FROM users";
$result = $conn->query($sql);
?>
   <div class="assign-vacations-container">
    <h2 class="assign-vacations-title">Verteilung von Urlaubstagen an Mitarbeiter</h2>
    <form action="/vacation_app/local/index.php?action=updateHolidays" method="post" class="assign-vacations-form">
        
        <div class="form-group">
            <label style="font-size:large;" for="employee" class="form-label">Wählen Sie einen Mitarbeiter aus:</label>
            <select name="employee_id" id="employee" class="form-select" required>
                <option  value="">-- Wählen Sie einen Mitarbeiter aus: --</option>
            
                <?php while ($row = $result->fetch_assoc()): ?>
                <option value="<?php echo $row['id']; ?>">
                    <?php echo $row['username'] . ' - Tage pro Jahr : ' . $row['total_vacation_days']; ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label style="font-size:large;" for="vacation_days" class="form-label">Verteilen Sie die neuen Gesamturlaubstage:</label>
            <input type="number" name="total_vacation_days" id="vacation_days" class="form-input" min="1" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="assign-btn">Tage aktualisieren</button>
        </div>   

    </form>
</div>


</body>
</html>

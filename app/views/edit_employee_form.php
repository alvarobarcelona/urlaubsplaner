<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css"> 
    <title>Mitarbeiter bearbeiten</title>
</head>
<body class="body-edit-employee">

    <div class="edit-employee-container">
        <h2 class="edit-employee-title">Wählen Sie einen Mitarbeiter aus, um sein Profil zu bearbeiten</h2>

        <form action="/vacation_app/local/index.php?action=editEmployee" method="post" class="edit-employee-form">
            <!-- Seleccionar un Empleado -->
            <div class="form-group">
                <label for="employee" class="form-label">Wählen Sie einen Mitarbeiter aus:</label>
                <select username="employee_id" id="employee" class="form-select" required>
                    <option value="">-- Wählen Sie einen Mitarbeiter aus --</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo htmlspecialchars($employee['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        
            <div class="form-group">
                <label for="username" class="label">Neuer Benutzername (optional):</label>
                <input type="text" name="username" id="username" class="form-input" value=" ">
            </div>


            <div class="form-group">
                <label for="department">Neue Abteilung (optional):</label>
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
                <label for="vacation_days" >Neue Gesamt-Urlaubstage (optional):</label>
                <input type="number" name="total_vacation_days" id="vacation_days" class="form-input" min="1">
            </div>

            <div class="form-group">
                <label for="password">Neues Passwort (optional):</label>
                <input type="password" name="password" id="password" value="">
            </div>

            <div class="">
                <button type="submit" class="edit-employee-btn">Änderungen speichern</button>
                
                <button type="button" class="cancel-btn" onclick="window.location.href='/vacation_app/app/views/admin_dashboard.php';">Abbrechen </button>


            </div>
        </form>
    </div>

</body>
</html>

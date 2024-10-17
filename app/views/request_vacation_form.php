<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abwesenheit eintragen</title>
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css"> 
</head>
<body class="body-request-vacation">
    <div class="request-vacation-container">
        <h2 class="request-vacation-title">Abwesenheit eintragen</h2>

        <form action="/vacation_app/local/index.php?action=requestVacation" method="post" class="request-vacation-form">
            <div class="form-group">
                <label for="vacation_type_id" class="form-label">Art des Antrags:</label>
                <select id="vacation_type_id" name="vacation_type_id" class="form-input" required>
                    <option value="">Wählen Sie eine Antragsart</option>
                    <?php foreach ($vacation_types as $type): ?>
                        <option value="<?php echo $type['id']; ?>"><?php echo $type['type_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date" class="form-label">Startdatum:</label>
                <input type="date" id="start_date" name="start_date" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="end_date" class="form-label">Enddatum:</label>
                <input type="date" id="end_date" name="end_date" class="form-input" required>
            </div>

            <div style="margin-top: 50px;">
                <button type="submit" class="form-btn">Antrag abschicken</button>
                <button type="button" class="form-btn" onclick="window.location.href='/vacation_app/app/views/employee_dashboard.php';">Abbrechen</button>
            </div>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/vacation_app/local/css/styles.css">
    <title>Anmeldung</title>
</head>
<body class="body-login">
    <div class="login-container">
        <h2 class="login-title">Anmeldung</h2>
        <form action="/vacation_app/local/index.php?action=login" method="post" class="login-form">
            <div class="form-group">
                <label for="username">Benutzer Name:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password" class="label-with-icon" title="Si no recuerdas la contraseña, contacta con el administrador." ;>Contraseña:
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" class="icon-password" >
                        <path strokeLinecap="round" strokeLinejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="login-btn">Anmelden</button>
            <p class="signup-link">Sie haben kein Konto?
            <a href="/vacation_app/local/index.php?action=signup" class="form-btn">Neu Registrieren</a>

            </p>

        </form>
    </div>
</body>
</html>

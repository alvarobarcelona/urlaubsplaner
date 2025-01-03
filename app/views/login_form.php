<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anmeldung</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Anmeldung</h2>
        <form action="/vacation_app/local/index.php?action=login" method="post" class="space-y-4">
            <!-- Benutzer Name -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Benutzer Name:</label>
                <input type="text" id="username" name="username" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            
            <!-- Contraseña -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                    Contraseña: 
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                </label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-300">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                Anmelden
            </button>

            <!-- Signup Link -->
            <p class="text-sm text-gray-600 text-center">
                Sie haben kein Konto? 
                <a href="/vacation_app/local/index.php?action=signup" class="text-blue-500 hover:underline">
                    Neu Registrieren
                </a>
            </p>
        </form>
    </div>
</body>
</html>

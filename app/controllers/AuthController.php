<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../core/Database.php';



class AuthController
{

    public function login()
    {

        if (!isset($_SESSION)) {
            session_start();
        }

        /* if (session_status() == PHP_SESSION_NONE) {
            session_start();
        } */

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';


            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            // Verificar si el usuario existe y si la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id']; //muy importante , ya que en cada pagina compruebo que el rol sea el correcto para evitar que un trabajador pueda ver cosas de un admin.



                // Redirigir según el rol del usuario
                if ($user['role_id'] == 1) {
                    header("Location: /vacation_app/app/views/admin_dashboard.php");
                    exit();
                } elseif ($user['role_id'] == 2) {
                    header("Location: /vacation_app/app/views/employee_dashboard.php");
                    exit();
                } else {
                    echo "Unbekante Rol.<br>";
                }
            } else {
                $_SESSION['error_message'] = "Falsches Login oder Passwort.";
                echo "<script> alert('Falsches Benutzername oder Passwort.') </script>";
                require_once __DIR__ . '/../views/login_form.php';
            }
        } else {
            // Si no es POST, mostrar el formulario de inicio de sesión
            require_once __DIR__ . '/../views/login_form.php';
        }
    }





    public function signup()
    {

        $userModel = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $department_id = $_POST['department_id'];
            $admin_code = $_POST['admin_code'];

            $valid_admin_code = 'admin123';
            // Este código de administrador debería estar en un lugar más seguro, como una variable de entorno

            // Determinar el rol del usuario
            if ($admin_code === $valid_admin_code) {
                $role_id = 1; //admin
            } else {
                $role_id = 2; //employee
            }


            $existingUser = $userModel->getUsers($username);

            // Verificar si el nombre de usuario ya existe
            if ($existingUser) {
                echo "Dieser Benutzername ist bereits in Betrieb.";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '/vacation_app/local/auth/signup';
                        }, 3000);
                    </script>";
                exit();
            } else {
                // Cifrar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $success = $userModel->createUser($username, $hashed_password, $name, $email, $role_id, $department_id);

                if ($success) {
                    $_SESSION['success_message']= "Benutzer erfolgreich registriert.";
                    
                    require_once __DIR__ . '/../views/login_form.php';
                    exit();
                } else {
                    $_SESSION['error_message'] ="Fehler bei der Registrierung des Benutzers.";
                    
                }
            }
        } else {

            $employees = $userModel->getAllEmployees();  // Obtener todos los empleados desde el modelo 
            $departments = $userModel->getAllDepartments();
               // Obtener todos los departamentos para el <select>
            require_once __DIR__ . '/../views/signup_form.php'; // Mostrar el formulario de registro
        }
    }

    // NO SOLO CREAR EL REQUEST EN LA TABLA QUE SERA SIEMPRE APROBADO PORQUE ES ADMIN, SINO ACTUALIZAR LOS DIAS EN LA TABLA USERS
    public function createVacationRequestAdmin()
    {

        $userModel = new UserModel();
        $vacationModel = new VacationModel();

        // Verificar que el usuario es administrador
        if ($_SESSION['role_id'] != 1) {
            header("Location: /vacation_app/local/index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employee_id = $_POST['employee_id'];
            $vacation_type_id = $_POST['vacation_type_id'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $start_time = $_POST['start_time'];
            $end_time = $_POST['end_time'];




            $start_datetime = $start_date . ' ' . $start_time;
            $end_datetime = $end_date . ' ' . $end_time;

            // Determinar si es medio día
            if (($start_time === '08:00' && $end_time === '12:00') ||
                ($start_time === '12:00' && $end_time === '16:00')
            ) {
                $is_half_day = 0.5;
                $half_day_period = ($start_time === '08:00') ? 'Vormittag' : 'Nachmittag';
            } else {
                // Día completo
                $is_half_day = 0;
                $half_day_period = null;
            }
            $success = $userModel->createVacationRequest($employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day, $half_day_period);




            // Llamar al modelo para crear la solicitud sin necesidad de aprobación
            if ($success) {
                $vacationModel->updateVacationDays($employee_id, $start_date, $end_date, $vacation_type_id, $is_half_day);
                $_SESSION['success_message'] = "Die Daten wurden erfolgreich hinzugefügt";
                require_once __DIR__ . '/../views/admin_dashboard.php';
            } else {
                $_SESSION['error_message'] = "Einfügungsfehler.";
                require_once __DIR__ . '/../views/admin_request_form.php';
            }
            exit();
        } else {

            $vacation_types = $vacationModel->getVacationTypes();
            $employees = $userModel->getAllEmployees();
            $departments = $userModel->getAllDepartments();

            require_once __DIR__ . '/../views/admin_request_form.php';
        }
    }





    public function logout()
    {

        session_destroy();
        header("Location: /vacation_app/app/views/login_form.php");
        exit();
    }
}

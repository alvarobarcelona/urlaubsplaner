<?php

require_once __DIR__ . '/../models/EmployeeModel.php';

class EmployeeController
{

    
    public function editEmployee()
    {
        $employeeModel = new EmployeeModel();

        // Si es una solicitud POST (el formulario fue enviado)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $employee_id = $_POST['employee_id'];
            $username = !empty($_POST['username']) ? $_POST['username'] : null;
            $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
            $total_vacation_days = !empty($_POST['total_vacation_days']) ? $_POST['total_vacation_days'] : null;
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            $role_id = !empty($_POST['role_id']) ? $_POST['role_id'] : null;

            $success = $employeeModel->updateEmployee($employee_id, $username, $department_id, $total_vacation_days, $password, $role_id);

            if ($success) {
                $_SESSION['success_message'] = "Mitarbeiter richtig aktualisiert.";
                header("Location: /vacation_app/app/views/admin_dashboard.php");
                exit();
            }

            echo "Fehler bei der Aktualisierung des Mitarbeiters.";
        } else {
            // Si es una solicitud GET, mostrar el formulario con los empleados
            $employees = $employeeModel->getAllEmployees();
            $departments = $employeeModel->getAllDepartments();
            $roles = $employeeModel->getAllRoles();
            require_once __DIR__ . '/../views/edit_employee_form.php';
        }
    }



    //NO TOCAR
    public function updateHolidays()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee_id = $_POST['employee_id'];
            $total_vacation_days = $_POST['total_vacation_days'];

            $employeeModel = new EmployeeModel();

            $success = $employeeModel->updateVacationDays($employee_id, $total_vacation_days);

            if ($success) {

                $_SESSION['success_message'] = "Die Urlaubstage wurden korrekt aktualisiert.";

                header("Location: /vacation_app/app/views/admin_dashboard.php");

                exit();
            }

            $_SESSION['error_message'] = "Fehler bei der Zuweisung von Urlaubstagen.";
        } else {
            // Si no es una solicitud POST, mostrar el formulario.
            require_once __DIR__ . '/vacation_app/app/views/admin_dashboard.php';
        }
    }


    public function edit_profile()
    {

        $userModel = new UserModel();
        $employeeModel = new EmployeeModel();

        $userId = $_SESSION['user_id'];

        $employee = $employeeModel->getEmployeeById($userId);
        $departments = $userModel->getAllDepartments();


        require_once __DIR__ . '/../views/profile_form.php';
    }




    public function updateProfile()
    {

        // Obtener los datos del formulario enviados mediante POST
        $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null; // Asegurarse de que sea un número entero válido
        $username = !empty($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : null; // Limpiar y sanitizar el nombre de usuario
        $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : null; // Limpiar el nombre completo
        $email = !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
        $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : '-';

        $employeeModel = new EmployeeModel();

        $success = $employeeModel->updateProfile($user_id, $username, $name, $email, $department_id);

        if ($success) {
            echo "<script>
                        alert('Ihre Änderungen wurden erfolgreich gespeichert.');               
                </script>";
            require_once __DIR__ . '/../views/employee_dashboard.php';
        } else {
            echo "<script>
                        alert('Fehler bei den Änderungen. Prüfen Sie alle Felder.');               
                </script>";
            require_once __DIR__ . '/../views/profile_form.php';
        }
        exit();
    }

    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
    
            
            if ($_SESSION['role_id'] !== 1) {
                $_SESSION['error_message'] = "Sie haben keine Berechtigung, einen Benutzer zu löschen.";
                header("Location: /vacation_app/app/views/admin_dashboard.php");
                exit();
            }
    
            $userModel = new UserModel();
            $success = $userModel->deleteUserById($user_id);
    
            if ($success) {
                $_SESSION['success_message'] = "Benutzer erfolgreich gelöscht.";
            } else {
                $_SESSION['error_message'] = "Fehler beim Löschen des Benutzers.";
            }
    
            header("Location: /vacation_app/app/views/admin_dashboard.php");
            exit();
        }
    }
    



}

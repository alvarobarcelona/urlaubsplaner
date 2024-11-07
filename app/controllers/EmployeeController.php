<?php

require_once __DIR__ . '/../models/EmployeeModel.php';

class EmployeeController
{



    public function editEmployee()
    {
        $employeeModel = new EmployeeModel();

        // Si es una solicitud POST (el formulario fue enviado)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employee_id = $_POST['employee_id'];

            // Inicializar variables vacías o con los valores enviados
            $username = !empty($_POST['username']) ? $_POST['username'] : null;
            $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : null;
            $total_vacation_days = !empty($_POST['total_vacation_days']) ? $_POST['total_vacation_days'] : null;
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            // Llamar al método de actualización con los valores ingresados
            $success = $employeeModel->updateEmployee($employee_id, $username, $department_id, $total_vacation_days, $password);

            if ($success) {
                // Establecer un mensaje de éxito y redirigir al dashboard del administrador
                $_SESSION['success_message'] = "Empleado actualizado correctamente.";
                header("Location: /vacation_app/app/views/admin_dashboard.php");
                exit();
            } else {
                echo "Error al actualizar el empleado.";
            }
        } else {
            // Si es una solicitud GET, mostrar el formulario con los empleados
            $employees = $employeeModel->getAllEmployees();  // Obtener todos los empleados desde el modelo
            $departments = $employeeModel->getAllDepartments();  // Obtener todos los departamentos para el <select>
            require_once __DIR__ . '/../views/edit_employee_form.php';
        }
    }



    //NO TOCAR
    public function updateHolidays()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employee_id = $_POST['employee_id'];
            $total_vacation_days = $_POST['total_vacation_days'];

            // Crear una instancia del modelo EmployeeModel
            $employeeModel = new EmployeeModel();

            // Usar el modelo para actualizar los días de vacaciones
            $success = $employeeModel->updateVacationDays($employee_id, $total_vacation_days);

            if ($success) {

                $_SESSION['success_message'] = "Die Urlaubstage wurden korrekt aktualisiert.";

                header("Location: /vacation_app/app/views/admin_dashboard.php");

                exit();
            } else {
                $_SESSION['error_message'] = "Fehler bei der Zuweisung von Urlaubstagen.";
                echo "Fehler bei der Zuweisung von Urlaubstagen.";
            }
        } else {
            // Si no es una solicitud POST, mostrar el formulario (puedes cargar una vista aquí si lo necesitas)
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
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null; // Asegurarse de que sea un número entero válido
        $username = !empty($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : null; // Limpiar y sanitizar el nombre de usuario
        $name = !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : null; // Limpiar y sanitizar el nombre completo
        $email = !empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : null;
        $department_id = !empty($_POST['department_id']) ? $_POST['department_id'] : '-';

        $employeeModel = new EmployeeModel();

        // Llamar a la función del modelo para actualizar el perfil
        $success = $employeeModel->updateProfile($user_id, $username, $name, $email, $department_id);

        // Redirigir de acuerdo al resultado de la actualización
        if ($success) {
            echo "<script>
                        alert('Ihre Änderungen wurden erfolgreich gespeichert.');               
                </script>";
            require_once __DIR__ . '/../views/employee_dashboard.php';
        } else {
            "<script>
                        alert('Fehler bei den Änderungen. Prüfen Sie alle Felder.');               
                </script>";
            require_once __DIR__ . '/../views/profile_form.php'; // Redirigir de vuelta a la página de perfil
        }
        exit();
    }
}

<?php

require_once __DIR__ . '/../models/EmployeeModel.php';

class EmployeeController {
/*     public function addEmployee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $department = $_POST['department'];
            $total_vacation_days = $_POST['total_vacation_days'];

            // Llamar al modelo para agregar al empleado
            $employeeModel = new EmployeeModel();
            $success = $employeeModel->addEmployee($name, $department, $total_vacation_days);

            if ($success) {
                // Redirigir al dashboard del administrador
                header("Location: /views/admin_dashboard.php");
                exit();
            } else {
                echo "Error al agregar el empleado.";
            }
        }
    }
 */


    public function editEmployee() {
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
    public function updateHolidays() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $employee_id = $_POST['employee_id'];
            $total_vacation_days = $_POST['total_vacation_days'];

            // Crear una instancia del modelo EmployeeModel
            $employeeModel = new EmployeeModel();

            // Usar el modelo para actualizar los días de vacaciones
            $success = $employeeModel->updateVacationDays($employee_id, $total_vacation_days);

            if ($success) {

                $_SESSION['success_message'] = "Los días de vacaciones fueron actualizados correctamente.";
              
                 header("Location: /vacation_app/app/views/admin_dashboard.php");

                exit();
            } else {
                $_SESSION['error_message'] = "Error al asignar los días de vacaciones.";
                echo "Error al asignar los días de vacaciones.";
            }
        } else {
            // Si no es una solicitud POST, mostrar el formulario (puedes cargar una vista aquí si lo necesitas)
            require_once __DIR__ . '/vacation_app/app/views/admin_dashboard.php';
        }
    }




}

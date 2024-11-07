<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../core/Database.php';


class EmployeeModel
{


    public function addEmployee($name, $department, $total_vacation_days)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO users (name, department, total_vacation_days, used_vacation_days) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("ssi", $name, $department, $total_vacation_days);

        return $stmt->execute();
    }

    // Obtener un empleado por ID
    public function getEmployeeById($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function getAllEmployees()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);  // Devuelve todas las filas como un array asociativo
    }


    // Obtener todos los departamentos
    public function getAllDepartments()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, department_name FROM departments");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }



    public function updateEmployee($employee_id, $username = null, $department_id = null, $total_vacation_days = null, $password = null)
    {
        $db = Database::getInstance();

        // Actualizar los campos en la tabla users
        $fields = [];
        $params = [];
        $types = '';

        if (!empty($username)) {
            $fields[] = "username = ?";
            $params[] = $username;
            $types .= 's';
        }

        if (!empty($department_id)) {
            $fields[] = "department_id = ?";
            $params[] = $department_id;
            $types .= 'i';  // department_id es un entero (clave foránea)
        }

        if (!empty($total_vacation_days)) {
            $fields[] = "total_vacation_days = ?";
            $params[] = $total_vacation_days;
            $types .= 'i';
        }

        // Si no se envió ningún campo para actualizar en users
        if (!empty($fields)) {
            $params[] = $employee_id;
            $types .= 'i';

            $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if (!$stmt->execute()) {
                return false;  // Si falla la actualización
            }
        }

        // Si se proporciona una nueva contraseña, actualizar en la tabla users
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Asumiendo que el `employee_id` está vinculado con el `user_id`
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $hashed_password, $employee_id);

            if (!$stmt->execute()) {
                return false;  // Si falla la actualización de la contraseña
            }
        }

        return true;  // Si todo ha sido actualizado correctamente
    }





    //NO TOCAR
    // Método para actualizar los días de vacaciones de un empleado
    public function updateVacationDays($employee_id, $total_vacation_days)
    {
        $db = Database::getInstance();
        $sql = "UPDATE users SET total_vacation_days = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ii", $total_vacation_days, $employee_id);
        return $stmt->execute();
    }

    public function updateProfile($user_id, $username, $name, $email, $department_id)
    {
        // Preparar la consulta SQL para actualizar el perfil del usuario
        $db = Database::getInstance();
        $sql = "UPDATE users SET username = ?, name = ?, email = ?, department_id = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssii", $username, $name, $email, $department_id, $user_id);

        // Ejecutar la consulta y verificar si fue exitosa
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

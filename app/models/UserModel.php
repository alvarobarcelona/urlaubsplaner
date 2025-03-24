
<?php
require_once __DIR__ . '/../models/EmployeeModel.php';
require_once __DIR__ . '/../../core/Database.php';


class UserModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    // Verificar si el usuario existe y obtener sus datos
    public function getUsers($username)
    {
        $db = Database::getInstance();
        $stmt = $this->conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function getUserByUsername($username)
    {
        $db = Database::getInstance();

        // Consulta preparada para obtener el usuario por nombre de usuario
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);  // Solo se necesita un parámetro para el nombre de usuario

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  //array asociativo
        } else {
            return null; 
        }
    }


    // Insertar un nuevo usuario en la base de datos
    public function createUser($username, $hashed_password, $name, $email, $role_id, $department_id)
    {

        $stmt = $this->conn->prepare("INSERT INTO users (username, password, name, email, role_id, department_id) 
                                      VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $username, $hashed_password, $name, $email, $role_id, $department_id);
        return $stmt->execute();
    }

    public function getAllEmployees()
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, username, name, email FROM users");
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

    // Obtener los departamentos a través de la tabla users
    public function getAllDepartmentsFromUsers()
    {
        $db = Database::getInstance();

        // Consulta que une users con departments usando department_id
        $stmt = $db->prepare("
        SELECT DISTINCT d.id, d.name 
        FROM users u
        JOIN departments d ON u.department_id = d.id
    ");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createVacationRequest($employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day = false, $half_day_period = null)
    {

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO vacation_requests (employee_id, vacation_type_id, start_date, end_date, is_half_day, half_day_period, status) 
                                VALUES (?, ?, ?, ?, ?, ?, 'Approved')");

        // Ajuste en el tipo de `bind_param`: "iissis" es el correcto en este caso
        $stmt->bind_param("iissds", $employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day, $half_day_period);

        // Ejecutar y retornar el resultado
        $success = $stmt->execute();  // Retorna true si la ejecución fue exitosa

        return $success;
    }

    public function deleteUserById($user_id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }

    public function hasVacationRequests($user_id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COUNT(*) FROM vacation_requests WHERE employee_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;
    }





}

?>

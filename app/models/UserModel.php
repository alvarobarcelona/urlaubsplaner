
<?php
require_once __DIR__ . '/../models/EmployeeModel.php';
require_once __DIR__ . '/../../core/Database.php';


class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    // Verificar si el usuario existe y obtener sus datos
    public function getUsers($username, $password, $name, $mail, $role_id) {
        $db = Database::getInstance();

        $stmt = $this->conn->prepare("SELECT id, username, password, name, mail, role_id FROM users WHERE username = ?");
        $stmt->bind_param("ssssi", $username, $password, $name, $mail, $role_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserByUsername($username) {
        $db = Database::getInstance();
        
        // Consulta preparada para obtener el usuario por nombre de usuario
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);  // Solo se necesita un parámetro para el nombre de usuario
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Devolver el array asociativo del usuario
        } else {
            return null;  // Si no existe el usuario, devolver null
        }
    }
    

     // Insertar un nuevo usuario en la base de datos
     public function createUser($username, $hashed_password, $name, $mail, $role_id) {
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, name, mail, role_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $username, $hashed_password, $name, $mail, $role_id);
        return $stmt->execute();
    }

    public function getAllEmployees() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, username, name, email FROM users");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);  // Devuelve todas las filas como un array asociativo
    }
    
    // Obtener todos los departamentos
    public function getAllDepartments() {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT id, department_name FROM departments");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener los departamentos a través de la tabla users
    public function getAllDepartmentsFromUsers() {
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


    

}
?>



<?php
require_once __DIR__ . '/../../core/Database.php'; 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/* $employee_id = $_SESSION['user_id']; */

class VacationModel {
    private $db;

    

    public function __construct() {
        $this->db = Database::getInstance();
    }

// Obtener todas las solicitudes de vacaciones pendientes
    public function getPendingRequests() {
        // Consulta SQL para obtener las solicitudes pendientes junto con tipo de vacaciones y departamento
        $sql = "SELECT vacation_requests.id, users.username, vacation_requests.start_date, vacation_requests.end_date, 
                       vacation_requests.status, vacation_types.type_name, departments.department_name
                FROM vacation_requests
                JOIN users ON vacation_requests.employee_id = users.id
                JOIN vacation_types ON vacation_requests.vacation_type_id = vacation_types.id
                JOIN departments ON users.department_id = departments.id
                WHERE vacation_requests.status = 'Pending'";
        
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);  // Devolver el resultado como un array asociativo
    }

    // Solicitar vacaciones
    //Funciona correctamente
    public function requestVacation($employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day = false , $half_day_period = null) {

       
        // Insertar la nueva solicitud de vacaciones con estado 'Pending'
        $stmt = $this->db->prepare("INSERT INTO vacation_requests (employee_id, vacation_type_id, start_date, end_date, is_half_day, half_day_period, status) 
                                    VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("iissds", $employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day, $half_day_period );
        $success = $stmt->execute();  // Retorna true si la ejecución fue exitosa
        
        return $success;
    }
    
    


    public function getEmployeesVacationData() {
        $sql = $this->db->prepare("
            SELECT 
                u.username, 
                u.total_vacation_days, 
                u.used_vacation_days, 
                d.department_name,
                u.sick_days, 
                u.special_holidays_days
            FROM 
                users u
            LEFT JOIN 
                departments d ON u.department_id = d.id
            LEFT JOIN 
                vacation_requests vr ON u.id = vr.employee_id
            GROUP BY 
                u.id, u.username, u.total_vacation_days, u.used_vacation_days, d.department_name, u.sick_days, u.special_holidays_days
        ");
        
        $sql->execute();
        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);  // Para obtener todos los registros
    }
    
    
    public function reverseVacationDays($employee_id, $start_date, $end_date, $vacation_type_id) {
        // Calcular la diferencia en días
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $days_requested = $end->diff($start)->days + 1;  // Incluye el primer día
    
        // Determinar la columna a actualizar dependiendo del tipo de vacaciones
        if ($vacation_type_id == 2) {  // Sonder Urlaub (vacaciones especiales)
            $column_to_update = 'special_holidays_days';
        } elseif ($vacation_type_id == 3) {  // Krank (días de enfermedad)
            $column_to_update = 'sick_days';
        } else {
            $column_to_update = 'used_vacation_days';  // Cualquier otro caso sería vacaciones normales
        }
    
        // Restar los días correspondientes en la columna seleccionada
        $sql = "UPDATE users SET $column_to_update = $column_to_update - ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $days_requested, $employee_id);
        return $stmt->execute();
    }
    
    
    
// Cancelar una solicitud aprobada y revertir los días de vacaciones utilizados
public function cancelApprovedVacation($request_id) {
    // Obtener los detalles de la solicitud de vacaciones
    $request = $this->getRequestById($request_id);

    if ($request['status'] == 'Approved') {
        // Revertir los días de vacaciones usados
        $this->reverseVacationDays($request['employee_id'], $request['start_date'], $request['end_date'], $request['vacation_type_id']);
        
        // Eliminar la solicitud de vacaciones después de revertir los días
        $stmt = $this->db->prepare("DELETE FROM vacation_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        return $stmt->execute();
    } else {
        // Si la solicitud no está aprobada, no se hace nada
        return false;
    }
}




  // Cancelar (eliminar) una solicitud de vacaciones
    public function cancelVacationRequest($request_id) {
        $stmt = $this->db->prepare("DELETE FROM vacation_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        return $stmt->execute();
    }


    // Aprobar o rechazar una solicitud de vacaciones
    public function updateRequestStatus($request_id, $status) {
        
        $stmt = $this->db->prepare("UPDATE vacation_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $request_id);
        return $stmt->execute();
    }


//aqui debo actualizar el sql poruqe hay que agregar los dias de krank y los sonder urlaub si la solicitud es una de las dos
    public function updateVacationDays($employee_id, $start_date, $end_date, $vacation_type_id) {
        
        // Calcular la diferencia en días entre las fechas
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $days_requested = $interval->days + 1;
    
   
    // Determinar la columna a actualizar dependiendo del tipo de vacaciones
    if ($vacation_type_id == 3) {  
        $column_to_update = 'sick_days';
    } elseif ($vacation_type_id == 2) { 
        $column_to_update = 'special_holidays_days';
    } else {
        $column_to_update = 'used_vacation_days';  // Cualquier otro caso sería vacaciones normales
    }

    // Construir la consulta SQL dinámica
    $sql = "UPDATE users SET $column_to_update = $column_to_update + ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $days_requested, $employee_id);

    return $stmt->execute();
    }
    





    public function getRequestById($request_id) {
        
        $stmt = $this->db->prepare("SELECT * FROM vacation_requests WHERE id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    
    public function getVacationTypes() {
  
        $stmt = $this->db->prepare("SELECT id, type_name FROM vacation_types");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

 // Obtener el estado de vacaciones del empleado
 public function getEmployeeVacation($employee_id) {
    $stmt = $this->db->prepare("SELECT total_vacation_days, used_vacation_days, sick_days, special_holidays_days
        FROM users
        WHERE id = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

    // Obtener las próximas vacaciones aprobadas///REVISAR
    public function getNextApprovedVacation($employee_id) {
        $stmt = $this->db->prepare("SELECT start_date, end_date FROM vacation_requests WHERE employee_id = ? AND status = 'Approved' AND start_date >= CURDATE() ORDER BY start_date ASC LIMIT 1");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Obtener el historial de solicitudes de vacaciones junto con el tipo de vacaciones
    public function getVacationHistory($employee_id) {
        $stmt = $this->db->prepare("SELECT vr.id, vr.start_date, vr.end_date, vr.status, vt.type_name, vr.is_half_day , vr.half_day_period 
        FROM vacation_requests vr 
        JOIN vacation_types vt ON vr.vacation_type_id = vt.id 
        WHERE vr.employee_id = ? 
        ORDER BY vr.start_date ASC");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function getApprovedVacations($is_admin, $user_id = null) {
        if ($is_admin) {
            $sql = "SELECT users.username, vacation_requests.start_date, vacation_requests.end_date, vacation_types.type_name 
                    FROM vacation_requests 
                    JOIN users ON vacation_requests.employee_id = users.id
                    JOIN vacation_types ON vacation_requests.vacation_type_id = vacation_types.id 
                    WHERE vacation_requests.status = 'Approved'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT start_date, end_date, vacation_types.type_name 
                    FROM vacation_requests
                    JOIN vacation_types ON vacation_requests.vacation_type_id = vacation_types.id 
                    WHERE employee_id = ? 
                    AND status = 'Approved'";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    

}


<?php
// app/controllers/VacationController.php
require_once __DIR__ . '/../models/VacationModel.php';  // Asegúrate de que esta ruta sea correcta

class VacationController
{

    //forma correcta del constructor, hacerlo asi en el codigo de abajo para no tener que llamar todo el rato al
    private $vacationModel;

    public function __construct()
    {
        $this->vacationModel = new VacationModel();  // Instanciar el modelo
    }

    public function generalDashboard()
    {

        // $is_admin = ($_SESSION['role_id'] == 1);
        $user_id = $_SESSION['user_id'];
        $user_name = $_SESSION['username'];

        $vacations = $this->vacationModel->getApprovedVacations($user_id);
        // Cargar la vista y pasar los datos necesarios
        require_once __DIR__ . '/../views/general_dashboard.php';
    }


    public function manageRequests()
    {
//            $role_id = $_POST['role_id'];
//            $user_id = $_SESSION['user_id'];

        // Asegurarse de que el usuario es admin
//          if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== '1') {
//            header("Location: /views/login_form.php");
//            exit();
//        }

        // Obtener todas las solicitudes pendientes para recibir un mensaje de que no  hay solicitudes pendientes  ACTUALIDAD el mensaje lo muestro ya en la tabla.
        
        $vacationModel = new VacationModel();
        $requestsPending = $vacationModel->getPendingRequests();
        
        /*
        if ($requestsPending === null || empty($requestsPending)) {
            $_SESSION['error_message'] = "Es wurden keine offenen Anträge gefunden.";
            $requestsPending = [];  // Asegurarse de que $requests no esté nulo
        }
        */

        // Cargar la vista con las solicitudes (la vista se encargará de mostrar los mensajes)
        require_once '../app/views/manage_requests.php';
    }

    // Aprobar o rechazar una solicitud
    public function approveRejectRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];  // ID de la solicitud de vacaciones
            $action = $_POST['action'];  // Acción de aprobar o rechazar
            $new_status = ($action === 'approve') ? 'Approved' : 'Rejected';


            // Obtener los detalles de la solicitud de vacaciones
            $vacationModel = new VacationModel();
            $request = $vacationModel->getRequestById($request_id);  // Debes tener una función para obtener una solicitud por su ID

            if ($new_status === 'Approved') {

                $is_half_day = $request['is_half_day'] === 0.5;

                // Actualizar los días de vacaciones del empleado solo si se aprueba
                $vacationModel->updateVacationDays($request['employee_id'], $request['start_date'], $request['end_date'], $request['vacation_type_id'], $is_half_day);
            }

            // Actualizar el estado de la solicitud
            $success = $vacationModel->updateRequestStatus($request_id, $new_status);

            if ($success) {
                if ($new_status === 'Approved') {
                    $_SESSION['success_message'] = "Der Antrag wurde erfolgreich genehmigt.";
                } else {
                    $_SESSION['success_message'] = "Der Antrag wurde abgelehnt.";
                }
            } else {
                $_SESSION['error_message'] = " Fehler beim Aktualisieren der Antrag";
            }


            header("Location: /vacation_app/local/index.php?action=manageRequests");
            exit();
        }
    }


    // FUNCIONA NO TOCAR
    // Método para procesar la solicitud de vacaciones
    public function requestVacation()
    {

        $vacationModel = new VacationModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $employee_id = $_SESSION['user_id'];
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
            $vacation_type_id = $_POST['vacation_type_id'];
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

            $success = $vacationModel->requestVacation($employee_id, $vacation_type_id, $start_datetime, $end_datetime, $is_half_day, $half_day_period);


            if ($success) {
                    $_SESSION['success_message'] = "Der Antrag wurde erfolgreich erstellt";
                } else {
                $_SESSION['error_message'] = " Fehler beim erstellung des Antrags";
                }

            require_once __DIR__ . '/../views/employee_dashboard.php';

        } else {
            $vacation_types = $vacationModel->getVacationTypes();

            require_once __DIR__ . '/../views/request_vacation_form.php';
        }
    }

    public function cancelVacation()
    {

        $is_admin = ($_SESSION['role_id'] === 1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];  // ID de la solicitud a cancelar

            $vacationModel = new VacationModel();
            $request = $vacationModel->getRequestById($request_id);

            if ($request['status'] === 'Approved') {
                // Cancelar una solicitud aprobada y revertir los días
                $success = $vacationModel->cancelApprovedVacation($request_id);
            } else {
                // Cancelar una solicitud pendiente (no aprobada)
                $success = $vacationModel->cancelVacationRequest($request_id);
            }

            if ($success) {
                $_SESSION['success_message'] = "Die Anwesenheit wurde erfolgreich gelöscht.";
            } else {
                $_SESSION['error_message'] = "Die Anwesenheit wurde NICHT erfolgreich gelöscht.";
            }

            // Redirigir según el rol del usuario
            if ($is_admin) {
                header("Location: /vacation_app/local/index.php?action=manageRequests");
            } else {
                header("Location: /vacation_app/app/views/employee_dashboard.php");
            }
            exit();
        }
    }



    public function showRequestHistory()
    {

        $vacationModel = new VacationModel();

        if ($_SESSION['role_id'] !== 1) {
            header("Location: /vacation_app/local/index.php?action=login");
            exit();
        }

        // Obtener la solicitud por ID para revertir los cambios en la tabla de usuarios
        $allRequests = $this->vacationModel->getAllRequests();

        require_once __DIR__ . '/../views/admin_request_history.php';
    }

    public function revertRequest()
    {
        //session_start();

        // Verificar que el usuario es administrador
        if ($_SESSION['role_id'] !== 1) {
            header("Location: /vacation_app/local/index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];
            $vacationModel = new VacationModel();

            $request = $vacationModel->getRequestById($request_id);

            if ($request['status'] === 'Approved'){
                // Revertir la solicitud aprobada y actualizar los días en la tabla de usuarios
                $success = $vacationModel->cancelApprovedVacation($request_id);
            }else if($request['status'] === 'Rejected' || $request['status'] === 'Pending'){
                $success = $vacationModel->removeRejectedOrPendingVacation($request_id);
            }

            if ($success) {
                $_SESSION['success_message'] = "Die Abwesenheit wurde erfolgreich gelöscht";
            } else {
                $_SESSION['error_message'] = "Fehler bei der Stornierung des Antrags.";
            }

            header("Location: /vacation_app/local/index.php?action=showRequestHistory");
            exit();
        }
    }

    public function removeRejectedVacation()
    {

        // Verificar que el usuario es administrador
        if ($_SESSION['role_id'] !== 1) {
            header("Location: /vacation_app/local/index.php?action=login");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];
            $vacationModel = new VacationModel();

            // Revertir la solicitud aprobada y actualizar los días en la tabla de usuarios
            $success = $vacationModel->removeRejectedVacation($request_id);

            if ($success) {
                $_SESSION['success_message'] = "Die Abwesenheit wurde erfolgreich gelöscht.";
            } else {
                $_SESSION['error_message'] = "Fehler bei der löschung des Antrags.";
            }

            header("Location: /vacation_app/local/index.php?action=showRequestHistory");
            exit();
        }
    }
}

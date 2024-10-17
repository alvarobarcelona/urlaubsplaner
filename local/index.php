<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/EmployeeController.php';
require_once __DIR__ . '/../app/controllers/VacationController.php';  

 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/*
if (!isset($_SESSION['user_id'])) {
    // Si no hay una sesión iniciada, redirige al login
    header("Location: /vacation_app/local/index.php?action=login");
    exit();
}
if ($_SESSION['role_id'] != 1) {
    // Si no es administrador, redirige al dashboard de empleado o al login
    header("Location: /vacation_app/app/views/employee_dashboard.php");
    exit();
}
 */



$authController = new AuthController();


if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $authController->login();
    exit();
}


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $authController->logout();
    exit();
}

// Verificar si la acción en la URL es 'signup'
if (isset($_GET['action']) && $_GET['action'] == 'signup') {
    $authController->signup();  // Llama al método signup del controlador
    exit();
}



//////////////////////////////////////


$employeeController = new EmployeeController();

if (isset($_GET['action']) && $_GET['action'] == 'updateHolidays') {
    $employeeController->updateHolidays(); 
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'editEmployee') {
    $employeeController->editEmployee();
    exit();
}



//////////////////////////////////////////


$vacationController = new VacationController();

// Verificar si la acción es 'requestVacation'
if (isset($_GET['action']) && $_GET['action'] == 'requestVacation') {
    $vacationController->requestVacation();
    exit();
}

// Verificar si la acción es 'cancelVacation'
if (isset($_GET['action']) && $_GET['action'] == 'cancelVacation') {
    $vacationController->cancelVacation();
    exit();
}

if (isset($_GET['action'])&& $_GET['action'] =='approveRejectRequest' ) {
     $vacationController->approveRejectRequest();
     exit();
            
    }

if (isset($_GET['action'])&& $_GET['action'] =='manageRequests' ) {
    $vacationController->manageRequests();
    exit();
               
    }






///////////////////////////////////


// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Si el usuario no ha iniciado sesión, redirigir al formulario de login
    header("Location: ../views/login_form.php");
    exit();
}


?>


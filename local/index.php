<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/EmployeeController.php';
require_once __DIR__ . '/../app/controllers/VacationController.php';  

 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$authController = new AuthController();


if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $authController->login();
    exit();
}


if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $authController->logout();
    exit();
}


if (isset($_GET['action']) && $_GET['action'] == 'signup') {
    $authController->signup();
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'createVacationRequestAdmin') {
    $authController = new AuthController();
    $authController->createVacationRequestAdmin();
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

if (isset($_GET['action']) && $_GET['action'] == 'updateProfile') {  
    $employeeController->updateProfile();
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'edit_profile') {
    $employeeController->edit_profile();
    exit();
}
if (isset($_GET['action']) && $_GET['action'] == 'deleteUser') {
    $employeeController = new EmployeeController();
    $employeeController->deleteUser();
    exit();
}



//////////////////////////////////////////


$vacationController = new VacationController();

if (isset($_GET['action']) && $_GET['action'] == 'requestVacation') {
    $vacationController->requestVacation();
    exit();
}


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
if (isset($_GET['action']) && $_GET['action'] == 'generalDashboard') {
    $vacationController->generalDashboard();
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'showRequestHistory') {
    $vacationController->showRequestHistory();
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'revertRequest') {  
    $vacationController->revertRequest();
    exit();
}



///////////////////////////////////


if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login_form.php");
    exit();
}


?>


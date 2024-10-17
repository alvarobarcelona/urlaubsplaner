<?php
require_once __DIR__ . '/../models/UserModel.php';



class AuthController {

    public function login() {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $username = $_POST['username'] ?? '';  
            $password = $_POST['password'] ?? ''; 
            
    
            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);
    
            // Verificar si el usuario existe y si la contraseña es correcta
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id']; //muy importante , ya que en cada pagina compruebo que el rol sea el correcto para evitar que un trabajador pueda ver cosas de un admin.
    
            
                // Redirigir según el rol del usuario
                if ($user['role_id'] == 1) {                  
                    header("Location: /vacation_app/app/views/admin_dashboard.php");
                    exit();
                } elseif ($user['role_id'] == 2) {
                    header("Location: /vacation_app/app/views/employee_dashboard.php");
                    exit();
                } else {
                    echo "Rol no reconocido.<br>";
                }
            } else {
                echo "Usuario o contraseña incorrectos.<br>";
                require_once __DIR__ . '/../views/login_form.php';
            }
        } else {
            // Si no es POST, mostrar el formulario de inicio de sesión
            require_once __DIR__ . '/../views/login_form.php';
        }
    }
    
    



    public function signup() {

            $userModel = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $name = $_POST['name'];  // Nombre completo del usuario
            $mail = $_POST['email'];  // Correo electrónico
            $department_id = $_POST['department_id'];  // ID del departamento seleccionado
            $admin_code = $_POST['admin_code'];
    
            $valid_admin_code = 'admin123';  // Este código de administrador debería estar en un lugar más seguro, como una variable de entorno
    
            // Determinar el rol del usuario
            if ($admin_code === $valid_admin_code) {
                $role_id = 1;  // Rol de administrador
            } else {
                $role_id = 2;  // Rol de usuario normal
            }
    
           
            $existingUser = $userModel->getUsers($username,$password, $name, $mail, $role_id);
    
            // Verificar si el nombre de usuario ya existe
            if ($existingUser) {
                echo "Este nombre de usuario ya está en uso.";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '/vacation_app/local/auth/signup';
                        }, 3000);
                    </script>";
                exit();
            } else {
                // Cifrar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
                // Crear el nuevo usuario
                $success = $userModel->createUser($username, $hashed_password, $role_id, $name, $mail, $department_id);
    
                if ($success) {
                    echo "Usuario registrado exitosamente.";
                    require_once __DIR__ . '/../views/login_form.php';
                    exit();
                } else {
                    echo "Error al registrar el usuario.";
                }
            }
        } else {
            
            $employees = $userModel->getAllEmployees();  // Obtener todos los empleados desde el modelo 
            $departments = $userModel->getAllDepartments();   // Obtener todos los departamentos para el <select>
            require_once __DIR__ . '/../views/signup_form.php'; // Mostrar el formulario de registro
        }
    }
    

public function logout() {

    session_destroy();
    header("Location: /vacation_app/app/views/login_form.php");
    exit();
}
}

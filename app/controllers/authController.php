<?php 
require_once ROOT_PATH . 'app/models/userModel.php';

class AuthController {
    private $model;

    public function __construct($db_connection) {
        $this->model = new UserModel($db_connection);
    }

    public function showForm() {
        global $action;
        require_once ROOT_PATH . 'views/auth/auth.php';
    }

    public function authenticate(){
        $identifier = $_POST['identifier'];
        $password = $_POST['password'];

        $user = $this->model->verifyPassword($identifier, $password);
        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] ="Hello, " . $user['username'];
            $_SESSION['flashmsg'] = ['message' => 'Login successful.', 'type' => 'success'];
            header('Location: /dashboard');
            exit();
        } else {
            $message = "Invalid username/email or password.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            header('Location: /login');
            exit();
        }
    }

    public function storeUser() {
        $username = trim($_POST['username']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $rawPassword = $_POST['password'];

        $old_data = [
            'username' => $username,
            'email' => $_POST['email']
        ];

        if (!$email) {
            $message = "Invalid email format.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            $_SESSION['old_input'] = $old_data;
            header("Location: /signup");
            exit();
        }

        if($rawPassword !== $_POST['confirm_password']) {
            $message = "Passwords do not match.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            $_SESSION['old_input'] = $old_data;
            header("Location: /signup");
            exit();
        }

        if(strlen($rawPassword) < 8) {
            $message = "Password must be at least 8 characters long.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            $_SESSION['old_input'] = $old_data;
            header("Location: /signup");
            exit();
        }

        if($this->model->isDuplicate($username, $email)) {
            $message = "Username or email already exists.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            $_SESSION['old_input'] = $old_data;
            header("Location: /signup");
            exit();
        }

        $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);

        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ];

        $result = $this->model->createUser($data);

        if($result > 0){
            $message = "Registration successful. Please log in.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'success'];
            header("Location: /login");
            exit();
        } elseif ($result === -1){
            $message = "Username or email already exists.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'warning'];
            header("Location: /signup");
            exit();
        } else {
            $message = "Registration failed due to a server error. Please try again.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'danger'];
            $_SESSION['old_input'] = $old_data;
            header("Location: /signup");
            exit();
        }


    }

    public function index() {
        require_once ROOT_PATH . 'views/dashboard/dashboard.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $message = "You have been logged out.";
        $_SESSION['flash'] = ['message' => $message, 'type' => 'info'];
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        header('Location: /login');
        exit();
    }

    public function destroy() {
        $userId = $_SESSION['user_id'];
        $password = $_POST['deletePassword'];
        $user = $this->model->getUserById($userId);
        if($user && $password && password_verify($password, $user['password'])) {
            $this->model->deleteUserById($userId);
            session_unset();
            session_destroy();
            $message = "Your account has been deleted.";
            $_SESSION['flash'] = ['message' => $message, 'type' => 'info'];
            header('Location: /login');
            exit();
        } else {
            $message = "Incorrect password. Account deletion failed.";
            $_SESSION['flashmsg'] = ['message' => $message, 'type' => 'warning'];
            header('Location: /dashboard');
            exit();
        }
    }



}


?>
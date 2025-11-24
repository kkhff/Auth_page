<?php 
// Mulai sesi
session_start();

// Mendefinisikan ROOT_PATH
define('ROOT_PATH', __DIR__ . '/../');

// Menyertakan file database
require_once ROOT_PATH . '/app/config/database.php';

$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');


if (empty($path)) {
    // Jika user sudah login, arahkan ke dashboard
    $action = isset($_SESSION['user_id']) ? 'dashboard' : 'login';
} else {
    // Jika path ada (misalnya 'login', 'signup'), gunakan itu sebagai action
    $action = $path;
}

// Menentukan controller berdasarkan aksi
$controllerName = '';
if(in_array($action, ['login', 'signup', 'authorize', 'regist', 'logout', 'delete', 'dashboard'])) {
    $controllerName = 'authController';

} else {
    http_response_code(404);
    echo "Page not found.";
    exit();
}

// Menyertakan dan menginisialisasi controller
$controllerFile = ROOT_PATH . "app/controllers/{$controllerName}.php";

// Periksa apakah file controller ada
if(!file_exists($controllerFile)) {
    http_response_code(500);
    echo "Controller file not found.";
    exit();
}

require_once $controllerFile;

$controller = new $controllerName($conn);

// Menangani aksi
switch ($action) {
    case 'login':
        $controller->showForm();
        break;
    case 'signup':
        $controller->showForm();
        break;
    case 'authorize':
        $controller->authenticate();
        break;
    case 'regist':
        $controller->storeUser();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'delete':
        $controller->destroy();
        break;
    case 'dashboard':
        if (!isset($_SESSION['user_id'])) { 
            header('Location: /login');
            exit();
        }
        $controller->index();
        break;
    default:
        http_response_code(404);
        echo "Page not found.";
        break;
}


?>
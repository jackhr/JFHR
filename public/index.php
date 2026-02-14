<?php
require_once __DIR__ . '/../core/bootstrap.php';

use App\Controllers\HomeController;
use App\Controllers\MedicineController;
use App\Controllers\UserController;
use Core\Router;

$router = new Router();

// Public routes
$router->get('/', [HomeController::class, 'index']);

$router->get('/users', function () {
    $userController = new UserController();
    $userController->listUsers();  // List all users
});

$router->get('/com/medicine/*', [MedicineController::class, 'handle']);
$router->get('/com/medicine', [MedicineController::class, 'handle']);
$router->post('/com/medicine', [MedicineController::class, 'handle']);
$router->post('/com/medicine/*', [MedicineController::class, 'handle']);

// Group routes under the "/admin" prefix
$router->group('/admin', function (Router $router) {
    $router->get('/', [UserController::class, 'index']);

    $router->get('/dashboard', function () {
        echo "Welcome to the Admin Dashboard!";
    });

    $router->get('/users', function () {
        $userController = new UserController();
        $userController->listUsers();
    });

    $router->post('/login', function () {
        $email = htmlspecialchars(strip_tags($_POST['username']));
        $password = htmlspecialchars(strip_tags($_POST['password']));

        $userController = new UserController();
        $userController->login($email, $password);
    });
});

// Dispatch the request to the appropriate route
$router->dispatch();

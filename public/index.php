<?php
session_start();

require __DIR__ . '/../app/helpers/functions.php';

if (!file_exists(__DIR__ . '/../config/config.php')) {
    header('Location: /install.php');
    exit;
}

$config = require __DIR__ . '/../config/config.php';
foreach ($config as $section => $values) {
    if (is_array($values)) {
        foreach ($values as $key => $value) {
            $_ENV[$key] = $value;
        }
    }
}

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Core\Database;

$database = new Database($config['db']);
$router = new Router();
$auth = new AuthController($database);
$dashboard = new DashboardController($database);

$router->get('/', [$auth, 'showLogin']);
$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'login']);
$router->get('/logout', [$auth, 'logout']);
$router->get('/dashboard', [$dashboard, 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

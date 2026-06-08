<?php
/**
 * Main Router - Front Controller
 * All requests go through this file
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', __DIR__);

// Autoloader for classes
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . '/Controllers/',
        BASE_PATH . '/Models/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Get controller and action from URL
$controller = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Map of allowed controllers
$allowedControllers = ['Home', 'User', 'Admin'];

// Validate controller
if (!in_array($controller, $allowedControllers)) {
    $controller = 'Home';
}

// Build controller class name
$controllerClass = $controller . 'Controller';

// Check if controller exists
if (class_exists($controllerClass)) {
    $controllerInstance = new $controllerClass();
    
    // Check if action exists
    if (method_exists($controllerInstance, $action)) {
        // Call the action
        call_user_func([$controllerInstance, $action]);
    } else {
        // Action not found - show 404 or default
        echo "Action not found: " . $action;
    }
} else {
    // Controller not found
    echo "Controller not found: " . $controllerClass;
}
?>

<?php
// Simple autoload for controllers and core
spl_autoload_register(function($class) {
    $paths = [
        __DIR__ . '/../app/controllers/' . $class . '.php',
        __DIR__ . '/../app/core/' . $class . '.php',
        __DIR__ . '/../app/models/' . $class . '.php',
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) {
            require_once $p;
            return;
        }
    }
});

// routing c=student&a=index or api
$c = strtolower($_GET['c'] ?? 'student');
$a = strtolower($_GET['a'] ?? 'index');

$controllerName = ucfirst($c) . 'Controller';
if (!class_exists($controllerName)) {
    http_response_code(404);
    echo "Controller $controllerName not found";
    exit;
}
$controller = new $controllerName();

if (!method_exists($controller, $a)) {
    http_response_code(404);
    echo "Action $a not found";
    exit;
}
$controller->{$a}();
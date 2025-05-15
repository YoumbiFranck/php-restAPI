<?php
# echo "Hello, World! This is a test file.";
# http://localhost/php-restapi/
# var_dump($_SERVER['REQUEST_URI']);
declare(strict_types=1);
require __DIR__ . '/autoload.php';
require __DIR__ . '/headers.php';
$config = require __DIR__ . '/database/config.php';
set_error_handler("ErrorHandler::handleError");
set_exception_handler('ErrorHandler::handleException');

// ici on veut recupérer chaque partie de l'url
$parts = explode('/', $_SERVER['REQUEST_URI']);

# print_r($parts);

if ($parts[2] != 'products') {
    http_response_code(404);
    exit();
} 

$id = $parts[3] ?? null;
# var_dump($id);


$database = new Database($config['host'], $config['username'], $config['password'], $config['database']);

$productGateway = new ProductGateway($database);

$productController = new ProductController($productGateway);

$productController->processRequest($_SERVER['REQUEST_METHOD'], $id);
# var_dump($_SERVER['REQUEST_METHOD']);
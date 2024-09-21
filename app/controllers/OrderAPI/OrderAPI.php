<?php

require_once __DIR__ . '/OrderAPIController.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$controller = new OrderAPIController();
$method = $_SERVER['REQUEST_METHOD'];

// The base path for the API
$basePath = '/IPass/app/controllers/OrderAPI/OrderAPI.php';

// Remove query parameters from the URL (if any) and get the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path from the URI
$trimmedUri = str_replace($basePath, '', $uri);

// Split the remaining path into parts
$path = explode('/', trim($trimmedUri, '/'));

if ($method == 'GET' && $path[0] == 'orders' && !isset($path[1])) {
    $controller->listOrders(); // GET /orders
} elseif ($method == 'GET' && isset($path[1])) {
    $controller->showOrder($path[1]); // GET /orders/{id}
} elseif ($method == 'POST' && $path[0] == 'orders') {
    $controller->createOrder(); // POST /orders
} elseif ($method == 'PUT' && isset($path[1])) {
    $controller->updateOrder($path[1]); // PUT /orders/{id}
} elseif ($method == 'DELETE' && isset($path[1])) {
    $controller->deleteOrder($path[1]); // DELETE /orders/{id}
} else {
    // Handle invalid routes
    http_response_code(404);
    echo json_encode(['error' => 'Invalid API endpoint']);
}

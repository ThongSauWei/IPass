<?php

require_once __DIR__ . '/../../models/Product.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['ProductName'], $data['ProductDesc'], $data['Category'], $data['Price'], $data['Weight'], $data['ProductImage'], $data['Availability'])) {
        try {
            $product = new Product($data);
            if ($product->addProduct()) {
                http_response_code(201);
                echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $product = new Product();
        $products = $product->getAll();
        echo json_encode($products);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id !== null) {
        $product = new Product();
        try {
            if ($product->deleteProductById($id)) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing product ID']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['ProductID'])) {
        try {
            $product = new Product();
            if ($product->updateProduct($data)) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Only POST, GET, DELETE, and PUT methods are allowed']);
}

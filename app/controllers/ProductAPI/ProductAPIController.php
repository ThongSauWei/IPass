<?php

require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/ProductLogger.php';

class ProductAPIController {

    private $logger;

    public function __construct() {
        $this->logger = new ProductLogger();
    }

    public function handleRequest() {
        // Turn on error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addProduct();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->getProducts();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $this->deleteProduct();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $this->updateProduct();
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
        }
    }

    private function addProduct() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Check for missing fields
        if (isset($data['ProductName'], $data['ProductDesc'], $data['Category'], $data['Price'], $data['Weight'], $data['ProductImage'], $data['Availability'])) {
            $product = new Product($data);
            if ($product->addProduct()) {
                http_response_code(201);
                echo json_encode(['status' => 'success', 'message' => 'Product added successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        }
    }

    private function getProducts() {
        $product = new Product();
        $products = $product->getAll();
        echo json_encode($products);
    }

    private function deleteProduct() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $product = new Product();
            if ($product->deleteProductById($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete product']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing product ID']);
        }
    }

    private function updateProduct() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($data['ProductID'])) {
            $product = new Product();
            if ($product->updateProduct($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Product updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update product']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
        }
    }
}

// Instantiate and call the controller
$controller = new ProductAPIController();
$controller->handleRequest();

<?php

class ProductAPIController {

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

    //ADD FUNCTION
    private function addProduct() {
        // Get the form data
        $productData = [
            'ProductName' => $_POST['productName'] ?? null,
            'ProductDesc' => $_POST['productDesc'] ?? null,
            'Category' => $_POST['category'] ?? null,
            'Price' => $_POST['price'] ?? null,
            'Weight' => $_POST['weight'] ?? null,
            'ProductImage' => $_POST['productImage'] ?? null,
            'Availability' => $_POST['availability'] ?? null,
        ];

        $errors = [];
        foreach ($productData as $key => $value) {
            if ($value === null) {
                $errors[] = "$key is missing";
            }
        }
        if (!is_numeric($productData['Price'])) {
            $errors[] = "Price must be a number";
        }
        if (!is_numeric($productData['Weight'])) {
            $errors[] = "Weight must be a number";
        }
        if (!is_numeric($productData['Availability'])) {
            $errors[] = "Availability must be a number (0 or 1)";
        }

        // If there are errors, return them
        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
            return;
        }

        // Send the data to api.php via POST request using cURL
        $this->sendDataToAPI($productData);
    }

    //SEND DATA TO API (ADD FUNCTION)
    private function sendDataToAPI($data) {
        $url = 'http://localhost/IPass/app/controllers/ProductAPI/api.php'; 
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'cURL Error: ' . curl_error($ch)]);
        } else {
            echo $response; // Output the API response
        }

        curl_close($ch);
    }

    //GET ALL PRODUCT
    private function getProducts() {
        $url = 'http://localhost/IPass/app/controllers/ProductAPI/api.php'; 
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'cURL Error: ' . curl_error($ch)]);
        } else {
            echo $response; // Output the API response
        }

        curl_close($ch);
    }

    //DELETE
    private function deleteProduct() {
        $id = $_GET['id'] ?? null;

        if ($id !== null) {
            $url = 'http://localhost/IPass/app/controllers/ProductAPI/api.php'; 
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['id' => $id]));

            $response = curl_exec($ch);

            if ($response === false) {
                echo json_encode(['status' => 'error', 'message' => 'cURL Error: ' . curl_error($ch)]);
            } else {
                echo $response; // Output the API response
            }

            curl_close($ch);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing product ID']);
        }
    }

    //UPDATE
    private function updateProduct() {
        // Read the input data from the request (PUT requests typically send data in the body)
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if the ProductID is provided
        if (!isset($data['ProductID'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ProductID is missing']);
            return;
        }

        $requiredFields = ['ProductID', 'ProductName', 'ProductDesc', 'Category', 'Price', 'Weight', 'ProductImage', 'Availability'];
        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[] = "$field is missing";
            }
        }

        // If there are any validation errors, return them
        if (!empty($errors)) {
            echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
            return;
        }

        // Send the data to api.php via PUT request using cURL
        $this->sendDataToAPIUpdate($data, 'PUT');
    }

    //UPDATE FUNCTION TRANSFER DATA TO API
    private function sendDataToAPIUpdate($data, $method = 'POST') {
        $url = 'http://localhost/IPass/app/controllers/ProductAPI/api.php'; 
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        if ($response === false) {
            echo json_encode(['status' => 'error', 'message' => 'cURL Error: ' . curl_error($ch)]);
        } else {
            echo $response; // Output the API response
        }

        curl_close($ch);
    }

}

// Instantiate and call the controller
$controller = new ProductAPIController();
$controller->handleRequest();

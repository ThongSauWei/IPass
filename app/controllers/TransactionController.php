<?php

require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/OrderDetailsModel.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../state/OrderStateContext.php';

class TransactionController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }

    // Handles fetching paginated data for AJAX calls
    public function getPaginatedOrders() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];
            $userID = $user['UserID'];

            $customerModel = new Customer();
            $customer = $customerModel->findCustByUserID($userID);
            $customerID = $customer["CustomerID"];

            $orderPerPage = 10;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $orderPerPage;

            // Fetch orders based on the current page
            $orderList = $this->model->getOrdersByCustomer($customerID, $orderPerPage, $offset);

            // Return paginated data as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'orderList' => $orderList,
                'perPage' => $orderPerPage
            ]);
            exit;
            
        } catch (Exception $ex) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $ex->getMessage()]);
            exit;
        }
    }
    
    
    
    public function getOrderDetails() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];
            $userID = $user['UserID'];

            $customerModel = new Customer();
            $customer = $customerModel->findCustByUserID($userID);
            $customerID = $customer["CustomerID"];

            $orderID = isset($_GET['orderID']) ? $_GET['orderID'] : null;

            if ($orderID) {
                // Fetch order details based on the order ID
                $orderDetailsModel = new OrderDetailsModel();
                $orderDetails = $orderDetailsModel->getOrderDetailsByOrder($orderID);
                
                $productModel = new Product();
                
                if (is_array($orderDetails) && !empty($orderDetails)) {
                    foreach ($orderDetails as $key => $item) {
                        $productID = $item["ProductID"];
                        $product = $productModel->getById($productID);
                        $orderDetails[$key]["ProductName"] = $product[0]["ProductName"];
                    }
                }

                $order = $this->model->getOrder($orderID);

                // Return details as JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'orderData' => $order, 
                    'orderDetailsData' => $orderDetails]
                    );
                exit;
            } else {
                throw new Exception("Order ID is required");
            }
        } catch (Exception $ex) {
            echo json_encode(['error' => $ex->getMessage()]);
            exit;
        }
    }
    
    public function cancelOrder() {
        try {
            $orderID = json_decode(file_get_contents('php://input'), true);
            
            $orderContext = $this->model->getOrderContext($orderID);
            $orderContext->cancelOrder();
            
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    // Handles rendering of the main page
    public function showPage() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];
            $userID = $user['UserID'];

            $customerModel = new Customer();
            $customer = $customerModel->findCustByUserID($userID);
            $customerID = $customer["CustomerID"];
            
            $orderPerPage = 10;
            $offset = 0;
            $counter = $offset + 1;

            // Load initial orders (first page)
            $orderList = $this->model->getOrdersByCustomer($customerID, $orderPerPage, $offset);
            
            $orderCount = $this->model->getOrderCount($customerID);
            $pageNum = ceil((float) $orderCount / $orderPerPage);

            // Include the view file
            require_once '../views/Customer/transaction.php';
            
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }
}

// Initialize controller and handle requests
$model = new OrderModel();
$controller = new TransactionController($model);

if (isset($_GET['action']) && $_GET['action'] === 'showPage') {
    $controller->showPage();
}

// Handle AJAX request for pagination FIRST
if (isset($_GET['action']) && $_GET['action'] === 'getPaginatedOrders') {
    $controller->getPaginatedOrders();
    exit; // Make sure no further code is executed
}

if (isset($_GET['action']) && $_GET['action'] === 'getOrderDetails') {
    $controller->getOrderDetails();
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'cancelOrder') {
    $controller->cancelOrder();
    exit;
}

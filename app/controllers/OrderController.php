<?php

require_once '../core/SessionManager.php';
require_once '../models/OrderModel.php';
require_once '../models/OrderDetailsModel.php';
require_once '../models/Product.php';
require_once '../models/Customer.php';

class OrderController {
    private $orderModel;
    private $orderDetailsModel;
    
    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->orderDetailsModel = new OrderDetailsModel();
    }
    
    public function handleOrder() {
        try {
            $jsonObj = json_decode(file_get_contents('php://input'), true);
            $orderID = $jsonObj['orderID'];
            $action = $jsonObj['action'];
            
            $orderContext = $this->orderModel->getOrderContext($orderID);
            switch ($action) {
                case 'Cancel':
                    $orderContext->cancelOrder();
                    break;
                case 'Deliver':
                    $orderContext->deliverOrder();
                    break;
                case 'Complete':
                    $orderContext->completeOrder();
                    break;
                default:
                    echo 'Wait what action you choose?';
            }
            
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }
    
    public function listOrders() {
        try {
            SessionManager::requireLogin();
            $orderList = $this->orderModel->getAllOrders();
            
            $customerModel = new Customer();
            
            foreach ($orderList as $key => $order) {
                $customerID = $order["CustomerID"];
                $customer = $customerModel->findCustByCustID($customerID);
                $orderList[$key]["CustomerName"] = $customer[0]["CustomerName"];
            }
            
            require '../views/Admin/Order/listOrders.php';
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }
    
    public function showOrder() {
        try {
            SessionManager::requireLogin();
            $orderID = isset($_GET['orderID'])? $_GET['orderID'] : null;
            
            if ($orderID) {
                $order = $this->orderModel->getOrder($orderID);
                $orderDetails = $this->orderDetailsModel->getOrderDetailsByOrder($orderID);
            
                $productModel = new Product();
                
                foreach ($orderDetails as $key => $item) {
                    $productID = $item["ProductID"];
                    $product = $productModel->getById($productID);
                    $orderDetails[$key]["ProductName"] = $product[0]["ProductName"];
                }
                
                if ($_GET['action'] === 'viewOrder') {
                    require '../views/Admin/Order/viewOrder.php';
                } else if ($_GET['action'] === 'editOrder') {
                    $customerName = $_GET["customerName"];
                    $order["CustomerName"] = $customerName;
                    
                    require '../views/Admin/Order/editOrder.php';
                }
            
                
            } else {
                throw new Exception("Order ID is required");
            }
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }
}

$controller = new OrderController();

if (isset($_GET['action']) && $_GET['action'] === 'listOrders') {
    $controller->listOrders();
}

if (isset($_GET['action']) && ($_GET['action'] === 'viewOrder' || $_GET['action'] === 'editOrder')) {
    $controller->showOrder();
}

if (isset($_GET['action']) && $_GET['action'] === 'handleOrder') {
    $controller->handleOrder();
}


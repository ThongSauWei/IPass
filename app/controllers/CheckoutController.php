<?php

require_once '../core/SessionManager.php';
require_once '../models/Customer.php';
require_once '../models/CartService.php';
require_once '../models/OrderModel.php';
require_once '../models/Product.php';
require_once '../models/OrderDetailsModel.php';
require_once '../dto/OrderDTO.php';
require_once '../dto/OrderDetailsDTO.php';
require_once '../state/OrderStateContext.php';

class CheckoutController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showPage() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];
            $userID = $user['UserID'];
            
            $customerModel = new Customer();
            $customer = $customerModel->findCustByUserID($userID);
            $customerID = $customer["CustomerID"];

            $cartService = new CartService();
            $cartItems = $cartService->getCartForCustomer($customerID);
            
            $productModel = new Product();
            
            $deliveryFee = 3;
            $subtotal = 0;

            foreach ($cartItems as &$cartItem) {
                $productID = $cartItem["ProductID"];
                $product = $productModel->getById($productID);
                
                if (false) {
                    $discount = 0;
                    $price = number_format($product[0]["Price"] - $discount, 2);
                } else {
                    $price = number_format($product[0]["Price"], 2);
                }
                
                $cartItem["Price"] = $price;
                $subtotal += $price * $cartItem['Quantity'];
            }

            require dirname(__DIR__, 1) . '/views/Customer/checkout.php';
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function createOrder() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try {
                SessionManager::requireLogin();
                $user = $_SESSION['user'];
                $userID = $user['UserID'];

                $customerModel = new Customer();
                $customer = $customerModel->findCustByUserID($userID);
                $customerID = $customer["CustomerID"];

                $cartService = new CartService();
                $cartItems = $cartService->getCartForCustomer($customerID);

                $productModel = new Product();
                $cartTotal = 0;

                $orderID = $this->model->getNewOrderID();
                $orderDetailsList = [];

                foreach ($cartItems as &$cartItem) {
                    $productID = $cartItem["ProductID"];
                    $product = $productModel->getById($productID);
                    $price = $product[0]["Price"];

                    $orderDetails = new OrderDetailsDTO($orderID, $productID, $price, $cartItem["Quantity"], 0);
                    $orderDetailsList[] = $orderDetails;
                    $cartTotal += $price * $cartItem['Quantity'];
                }

                $jsonObj = json_decode(file_get_contents('php://input'), true);
                $discount = $cartTotal - (int) $jsonObj["subtotal"];
                $deliveryFee = $jsonObj["deliveryFee"];
                $address = $jsonObj["fullAddress"];

                $order = new OrderDTO($orderID, $customerID, $cartTotal, $discount, $deliveryFee, date('Y-m-d'), $address);
                $orderContext = new OrderStateContext($orderID);
                $orderContext->placeOrder($order);

                $orderDetailsModel = new OrderDetailsModel();

                foreach ($orderDetailsList as $orderDetails) {
                    $orderDetailsModel->insertOrderDetails($orderDetails);
                }

                $cartService->clearCart($customerID);
                
                echo json_encode(["ok" => true, "orderID" => "$orderID"]);
                
            } catch (Exception $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }
    }
}

$model = new OrderModel();
$controller = new CheckoutController($model);

if (isset($_GET['action']) && $_GET['action'] === 'showPage') {
    $controller->showPage();
}

if (isset($_GET['action']) && $_GET['action'] === 'createOrder') {
    $controller->createOrder();
}


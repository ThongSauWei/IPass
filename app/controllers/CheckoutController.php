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

            if (is_array($cartItems) && !empty($cartItems)) {
                foreach ($cartItems as $key => $cartItem) {
                    $productID = $cartItem["ProductID"];
                    $product = $productModel->getById($productID);

                    if (false) {
                        $discount = 0;
                        $price = number_format($product[0]["Price"] - $discount, 2);
                    } else {
                        $price = number_format($product[0]["Price"], 2);
                    }

                    $cartItems[$key]["Price"] = $price;
                    $subtotal += $price * $cartItem['Quantity'];
                }
            }

            require dirname(__DIR__, 1) . '/views/Customer/checkout.php';
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function createOrder() {
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

            if (is_array($cartItems) && !empty($cartItems)) {
                foreach ($cartItems as $key => $cartItem) {
                    $productID = $cartItem["ProductID"];
                    $product = $productModel->getById($productID);
                    $price = $product[0]["Price"];

                    $orderDetails = new OrderDetailsDTO($orderID, $productID, $price, $cartItem["Quantity"], 0);
                    $orderDetailsList[] = $orderDetails;
                    $cartTotal += $price * $cartItem['Quantity'];
                }
            }

            $paymentIntentID = $_GET["payment_intent"] ?? null;
            $subtotal = (float) $_GET["subtotal"] ?? null;
            $discount = $cartTotal - $subtotal;
            $deliveryFee = (float) $_GET["deliveryFee"] ?? null;
            $address = $_GET["fullAddress"] ?? null;

            include '../web/get-payment-intent.php';

            if (isset($paymentType)) {
                $order = new OrderDTO($orderID, $customerID, $cartTotal, $discount, $deliveryFee, date('Y-m-d'), $address, $paymentType);
                $orderContext = new OrderStateContext($orderID);
                $orderContext->placeOrder($order);

                $orderDetailsModel = new OrderDetailsModel();

                if (is_array($orderDetailsList) && !empty($orderDetailsList)) {
                    foreach ($orderDetailsList as $orderDetails) {
                        $orderDetailsModel->insertOrderDetails($orderDetails);
                    }
                }

                $cartService->clearCart($customerID);

                header("Location: http://localhost/IPass/app/controllers/CompleteCheckoutController.php?action=showPage&orderID=" . $orderID . "&totalAmount=" . ($subtotal + $deliveryFee));
                exit();
            } else {
                header("Location: http://localhost/IPass/app/controllers/CartController.php?action=showCart");
                exit();
            }
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
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


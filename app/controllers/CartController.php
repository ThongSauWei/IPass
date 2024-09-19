<?php

require_once '../core/SessionManager.php';
require_once '../models/Customer.php';
require_once '../models/CartService.php';
require_once '../models/Product.php';

class CartController {
    private $cartService;

    public function __construct() {
        $this->cartService = new CartService();
    }

    public function showCart() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];
            $userID = $user['UserID'];
            
            $customerModel = new Customer();
            $customer = $customerModel->findCustByUserID($userID);
            $customerID = $customer["CustomerID"];

            $cartItems = $this->cartService->getCartForCustomer($customerID);
            
            $productModel = new Product();

            foreach ($cartItems as &$cartItem) {
                $productID = $cartItem["ProductID"];
                $product = $productModel->getById($productID);
                
                if (true) {
                    $discount = 0;
                    $cartItem["PromotionPrice"] = number_format($product[0]["Price"] - $discount, 2);
                }
                
                $cartItem["ProductName"] = $product[0]["ProductName"];
                $cartItem["Price"] = number_format($product[0]["Price"], 2);
                $cartItem["Weight"] = number_format($product[0]["Weight"], 0);
                $cartItem["ProductImage"] = $product[0]["ProductImage"] ?? null;
            }

            require dirname(__DIR__, 1) . '/views/Customer/cart.php';
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                SessionManager::requireLogin();
                $user = $_SESSION['user'];
                $userID = $user['UserID'];

                $customerModel = new Customer();
                $customer = $customerModel->findCustByUserID($userID);
                $customerID = $customer["CustomerID"];
                
                $cartItems = json_decode(file_get_contents('php://input'), true);

                foreach ($cartItems as $cartItem) {
                    $quantity = $cartItem['quantity'];
                    $productID = $cartItem['productID'];

                    $this->cartService->updateCart($quantity, $productID, $customerID);
                }
            } catch (Exception $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }
    }

    public function removeCartItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                SessionManager::requireLogin();
                $user = $_SESSION['user'];
                $userID = $user['UserID'];

                $customerModel = new Customer();
                $customer = $customerModel->findCustByUserID($userID);
                $customerID = $customer["CustomerID"];
                
                $productID = $_POST['productID'];

                $this->cartService->removeCartItem($customerID, $productID);

                echo json_encode(['status' => 'success']);
            } catch (Exception $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }
    }

}

$controller = new CartController();

if (isset($_GET['action']) && $_GET['action'] === 'showCart') {
    $controller->showCart();
}

if (isset($_GET['action']) && $_GET['action'] === 'updateCart') {
    $controller->updateCart();
}

if (isset($_POST['action']) && $_POST['action'] === 'removeCartItem') {
    $controller->removeCartItem();
}


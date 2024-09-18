<?php

require_once '../core/SessionManager.php';
require_once '../models/CartService.php';
require_once '../models/User.php';

class CartController {
    private $cartService;

    public function __construct() {
        $this->cartService = new CartService();
    }

    public function showCart() {
        try {
            SessionManager::requireLogin();
            $user = $_SESSION['user'];

            $cartItems = $this->cartService->getCartForCustomer($customerID);

            $price = 100;
            $weight = 150;

            foreach ($cartItems as &$cartItem) {
                $cartItem["Price"] = $price;
                $cartItem["Weight"] = $weight;
                $price += 100;
                $weight += 350;
            }

            require dirname(__DIR__, 1) . '/views/Customer/cart.php';
        } catch (Exception $ex) {
            echo 'Error: ' . $ex->getMessage();
        }
    }

    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $customerID = $_SESSION['user'];
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
                $customerID = $_SESSION['user'];
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

$controller->showCart();

if (isset($_GET['action']) && $_GET['action'] === 'updateCart') {
    $controller->updateCart();
}

if (isset($_POST['action']) && $_POST['action'] === 'removeCartItem') {
    $controller->removeCartItem();
}


<?php

require_once '../models/CartModel.php';
require_once '../models/CheckoutModel.php';

class CartController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function showCart() {
        $customerID = $_SESSION['user'];
        $cartItems = $this->model->getCart($customerID);
        
        $price = 100;
        $weight = 150;
        
        foreach ($cartItems as &$cartItem) {
            $cartItem["Price"] = $price;
            $cartItem["Weight"] = $weight;
            $price += 100;
            $weight += 350;
        }
        
        require  dirname(__DIR__, 1) . '/views/Customer/cart.php';
    }
    
    public function updateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerID = $_SESSION['user'];
            $cartItems = json_decode(file_get_contents('php://input'), true);
            
            foreach ($cartItems as $cartItem) {
                $quantity = $cartItem['quantity'];
                $productID = $cartItem['productID'];
                
                $this->model->updateCart($quantity, $productID, $customerID);
            }
        }
    }
    
    public function removeCartItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerID = $_SESSION['user'];
            $productID = $_POST['productID'];
            
            $this->model->removeCartItem($customerID, $productID);
            
            echo json_encode(['status' => 'success']);
        }
    }
}

session_start();

$model = new CartModel();
$controller = new CartController($model);

$_SESSION['user'] = "C1001";

$controller->showCart();

if (isset($_GET['action']) && $_GET['action'] === 'updateCart') {
    $controller->updateCart();
}

if (isset($_POST['action']) && $_POST['action'] === 'removeCartItem') {
    $controller->removeCartItem();
}


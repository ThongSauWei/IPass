<?php

require_once '../models/CartModel.php';
require_once '../models/CheckoutModel.php';

class CheckoutController {

    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function showPage() {
        $customerID = $_SESSION['user'];

        $cartModel = new CartModel();
        $cartItems = $cartModel->getCart($customerID);

        $price = 100;
        $deliveryFee = 3;
        $subtotal = 0;

        foreach ($cartItems as &$cartItem) {
            $cartItem["Price"] = $price;
            $subtotal += $price * $cartItem['Quantity'];
            $price += 100;
        }

        require dirname(__DIR__, 1) . '/views/Customer/checkout.php';
    }
}

$model = new CheckoutModel();
$controller = new CheckoutController($model);

session_start();

$controller->showPage();

if (isset($_GET['action']) && $_GET['action'] === 'showPage') {
    $controller->showPage();
}


<?php

require_once '../models/CartModel.php';

class CartController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function showCart($userID) {
        $cartItems = $this->model->getCart($userID);
        
        require '../views/Customer/cart.php';
    }
}

$model = new CartModel();
$controller = new CartController($model);

$userID = "C1001";

$controller->showCart($userID);


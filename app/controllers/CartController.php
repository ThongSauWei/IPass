<?php

require_once '../model/Cart.php';

class CartController {
    private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function getCartItems($userID) {
        
    }
}


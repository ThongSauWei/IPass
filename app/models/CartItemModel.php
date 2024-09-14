<?php

require_once '../core/NewModel.php';

class CartItemModel extends NewModel {
    protected $table = "cartItem";
    
    public function getCartItems($cartID) {
        return $this->findAll()->where("CartID", $cartID)->execute();
    }
}


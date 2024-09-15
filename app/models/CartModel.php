<?php

require_once '../core/NewModel.php';
require_once 'CartItemModel.php';

class CartModel extends NewModel {
    protected $table = "cart";
    
    public function getCart($userID) {
        $data = $this->findAll()->where("CustomerID", $userID)->execute();
        $cartID = $data[0]["CartID"];
        
        return $this->getCartItems($cartID);
    }
    
    private function getCartItems($cartID) {
        $cartItemModel = new CartItemModel();
        $cartItems = $cartItemModel->getCartItems($cartID);
        
        return $cartItems;
    }
}


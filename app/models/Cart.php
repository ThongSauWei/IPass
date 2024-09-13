<?php

require_once '../core/Model.php';
require_once 'CartItem.php';

class Cart {
    use Model;
    
    protected $table = "cart";
    
    public function getCart($userID) {
        $data = $this->first(["customerID" => $userID]);
        $cartID = $data["CartID"];
        
        return $this->getCartItems($cartID);
    }
    
    private function getCartItems($cartID) {
        $cartItemModel = new CartItem();
        $cartItems = $cartItemModel->getCartItems($cartID);
        
        return $cartItems;
    }
}


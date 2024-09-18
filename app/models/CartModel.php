<?php

require_once '../core/NewModel.php';
require_once 'CartItemModel.php';

class CartModel extends NewModel {
    protected $table = "cart";
    
    public function getCart($customerID) {
        $cartID = $this->getCartID($customerID);
        
        return $this->getCartItems($cartID);
    }
    
    public function updateCart($quantity, $productID, $customerID) {
        $cartID = $this->getCartID($customerID);
        
        $cartItemModel = new CartItemModel();
        $cartItemModel->updateCartItem($quantity, $cartID, $productID);
    }
    
    public function removeCartItem($customerID, $productID) {
        $cartID = $this->getCartID($customerID);
        
        $cartItemModel = new CartItemModel();
        $cartItemModel->removeCartItem($cartID, $productID);
    }
    
    private function getCartID($customerID) {
        $data = $this->findAll()->where("CustomerID", $customerID)->execute();
        $cartID = $data[0]["CartID"];
        
        return $cartID;
    }
    
    private function getCartItems($cartID) {
        $cartItemModel = new CartItemModel();
        $cartItems = $cartItemModel->getCartItems($cartID);
        
        return $cartItems;
    }
}


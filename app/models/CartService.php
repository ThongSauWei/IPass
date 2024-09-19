<?php

require_once 'CartModel.php';
require_once 'CartItemModel.php';

class CartService {
    private $cartModel;
    private $cartItemModel;
    
    public function __construct() {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
    }
    
    public function getCartForCustomer($customerID) {
        $cartID = $this->getCartID($customerID);
        return $this->cartItemModel->getCartItems($cartID);
    }
    
    public function updateCart($quantity, $productID, $customerID) {
        $cartID = $this->getCartID($customerID);
        return $this->cartItemModel->updateCartItem($quantity, $cartID, $productID);
    }
    
    public function removeCartItem($customerID, $productID) {
        $cartID = $this->getCartID($customerID);
        return $this->cartItemModel->removeCartItem($cartID, $productID);
    }
    
    public function clearCart($customerID) {
        $cartID = $this->getCartID($customerID);
        return $this->cartItemModel->clearCartItems($cartID);
    }
    
    private function getCartID($customerID) {
        return $this->cartModel->getCartID($customerID);
    }
}



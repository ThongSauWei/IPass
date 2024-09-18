<?php

require_once '../core/NewModel.php';

class CartItemModel extends NewModel {
    protected $table = "cartItem";
    
    public function addCartItem($cartItem) {
        $this->insert($cartItem)->execute();
    }
    
    public function getCartItems($cartID) {
        return $this->findAll()->where("CartID", $cartID)->execute();
    }
    
    public function updateCartItem($quantity, $cartID, $productID) {
        $this->update('Quantity', $quantity)->where('ProductID', $productID)->where('CartID', $cartID)->execute();
    }
    
    public function removeCartItem($cartID, $productID) {
        $this->delete()->where('ProductID', $productID)->where('CartID', $cartID)->execute();
    }
    
    public function test() {
        var_dump($this->findAll()->where("ProductID", "P1001")->execute());
        var_dump($this->findAll()->where("ProductID", "P1002")->execute());
        var_dump($this->findAll()->where("ProductID", "P1001")->orWhere("ProductID", "P1002")->execute());
    }
}

$test = new CartItemModel();
$test->test();


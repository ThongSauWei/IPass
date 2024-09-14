<?php

require_once '../core/Model.php';

class CartItem {
    use Model;
    
    protected $table = "cartItem";
    
    public function getCartItems($cartID) {
        $this->order_column = "cartID";
        
        return $this->where(["cartID" => $cartID]);
    }
}


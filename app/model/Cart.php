<?php

require_once '../core/Database.php';

class Cart {
    use Database;
    
    public function getCart($userID) {
        $data = $this->get_row("SELECT * FROM Cart WHERE CustomerID = ?", [$userID]);
        $cartID = $data["cartID"];
        
        $cartList = $this->getCartItems($cartID);
    }
    
    private function getCartItems($cartID) {
        $data = $this->query("SELECT * FROM CartItem WHERE CartID = ?", [$cartID]);
        
    }
}


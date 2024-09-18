<?php

require_once '../core/NewModel.php';

class CartModel extends NewModel {
    protected $table = "cart";
    
    private function getCartID($customerID) {
        $data = $this->findAll()->where("CustomerID", $customerID)->execute();
        $cartID = $data[0]["CartID"];
        
        return $cartID;
    }
}


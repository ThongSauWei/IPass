<?php

require_once '../core/NewModel.php';

class CartModel extends NewModel {
    protected $table = "cart";
    
    public function getCartID($customerID) {
        $data = $this->findAll()->where("CustomerID", $customerID)->execute();
        $cartID = $data[0]["CartID"];
        
        return $cartID;
    }
    
    public function createCart($customerID) {
        $this->insert([
            "CartID" => $this->getNewCartID(),
            "CustomerID" => $customerID
        ])->execute();
    }
    
    public function deleteCart($customerID) {
        $this->delete()->where("CustomerID", $customerID)->execute();
    }
    
    private function getNewCartID() {
        $carts = $this->findAll()->orderBy("CartID", "DESC")->limit(1)->execute();
        if (empty($carts)) {
            $newCartID = 'CT0001';
        } else {
            $lastCartID = $carts[0]["CartID"];
        
            $index = (int) $lastCartID.substr($lastCartID, 2);
            $index++;
        
            $newCartID = 'CT' . str_pad($index, 4, '0', STR_PAD_LEFT);
        }
        
        return $newCartID;
    }
}


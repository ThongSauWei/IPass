<?php

class OrderDetailsDTO {
    public $orderID;
    public $productID;
    public $unitPrice;
    public $quantity;
    public $discount;
    
    public function __construct($orderID, $productID, $unitPrice, $quantity, $discount) {
        $this->orderID = $orderID;
        $this->productID = $productID;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
        $this->discount = $discount;
    }
}


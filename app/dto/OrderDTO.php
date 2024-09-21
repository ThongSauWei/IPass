<?php

class OrderDTO {
    public $orderID;
    public $customerID;
    public $purchasedAmt;
    public $discount;
    public $deliveryFee;
    public $orderDate;
    public $deliveryAddress;
    
    public function __construct($orderID, $customerID, $purchasedAmt, $discount, $deliveryFee, $orderDate, $deliveryAddress) {
        $this->orderID = $orderID;
        $this->customerID = $customerID;
        $this->purchasedAmt = $purchasedAmt;
        $this->discount = $discount;
        $this->deliveryFee = $deliveryFee;
        $this->orderDate = $orderDate;
        $this->deliveryAddress = $deliveryAddress;
    }
}


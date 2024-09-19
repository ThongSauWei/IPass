<?php

require_once '../core/NewModel.php';

class OrderDetailsModel extends NewModel {
    protected $table = 'orderDetails';
    
    public function insertOrderDetails($orderDetails) {
        $this->insert($orderDetails)->execute();
    }
    
    public function getOrderDetailsByOrder($orderID) {
        return $this->findAll()->where("OrderID", $orderID)->execute();
    }
    
    public function clearAllOrderDetails($orderID) {
        $this->delete()->where("OrderID", $orderID)->execute();
    }
}


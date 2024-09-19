<?php

require_once '../core/NewModel.php';

class OrderModel extends NewModel {
    protected $table = 'orders';
    
    public function createOrder($order) {
        $this->insert($order)->execute();
    }
    
    public function getOrder($orderID) {
        return $this->findAll()->where("OrderID", $orderID)->execute();
    }
    
    public function getOrdersByCustomer($customerID, $limit = 1000, $offset = 0) {
        return $this->findAll()->where("CustomerID", $customerID)->limit($limit)->offset($offset)->execute();
    }
    
    public function getOrderCount($customerID = null) {
        $orderList = isset($customerID)? $this->findAll()->where("CustomerID", $customerID)->execute() : $this->findAll()->execute();
        return count($orderList);
    }
    
    public function getAllOrders() {
        return $this->findAll()->execute();
    }
    
    public function getNewOrderID() {
        $orders = $this->findAll()->orderBy("OrderID", "DESC")->limit(1)->execute();
        if (empty($orders)) {
            $newOrderID = 'O0001';
        } else {
            $lastOrderID = $orders[0]["OrderID"];
        
            $index = (int) $lastOrderID.substr($lastOrderID, 1);
            $index++;
        
            $newOrderID = 'O' . str_pad($index, 4, '0', STR_PAD_LEFT);
        }
        
        return $newOrderID;
    }
}


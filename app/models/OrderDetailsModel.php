<?php

require_once __DIR__ . '/../core/NewModel.php';

class OrderDetailsModel extends NewModel {
    protected $table = 'orderDetails';
    
    public function insertOrderDetails($orderDetails) {
        $this->insert($orderDetails)->execute();
    }
    
    public function getOrderDetailsByOrder($orderID) {
        return $this->findAll()->where("OrderID", $orderID)->execute();
    }
    
    public function updateOrderDetails($orderID, $productID, $data) {
        $class = new ReflectionClass($data);
        $properties = $class->getProperties();
        
        foreach ($properties as $property) {
            $column = $property->getName();
            $value = $property->getValue($data);
            
            $this->update($column, $value);
        }
        
        $this->where("OrderID", $orderID)->where("ProductID", $productID)->execute();
    }
    
    public function clearAllOrderDetails($orderID) {
        $this->delete()->where("OrderID", $orderID)->execute();
    }
}


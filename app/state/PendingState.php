<?php

require_once __DIR__ . '/OrderStateInterface.php';
require_once __DIR__ . '/CancelledState.php';
require_once __DIR__ . '/DeliveringState.php';
require_once __DIR__ . '/../models/OrderDetailsModel.php';
require_once __DIR__ . '/../models/Product.php';

class PendingState implements OrderStateInterface {
    private $context;
    
    public function setContext(OrderStateContext $context) {
        $this->context = $context;
    }
    
    public function placeOrder($data) {
        echo "Order is already placed. Cannot place order at this time";
        
        return false;
    }
    
    public function cancelOrder() {
        echo "Order cancelled successfully";
        
        $this->context->changeState(new CancelledState());
        
        return true;
    }
    
    public function deliverOrder() {
        if ($this->checkAvailability()) {
            echo "Start to deliver the order products";
            $this->context->changeState(new DeliveringState());
            
            return true;
        } else {
            echo "Sorry. One of the products is not available currently. Cancelling this order...";
            $this->context->changeState(new CancelledState());
            return false;
        }
    }

    public function completeOrder() {
        echo 'The order has not delivered yet, cannot be completed';
        
        return false;
    }
    
    private function checkAvailability() {
        $available = true;
        
        $orderDetailsModel = new OrderDetailsModel();
        $productModel = new Product();
        
        $orderItems = $orderDetailsModel->getOrderDetailsByOrder($this->context->getOrderID());
        
        foreach ($orderItems as $item) {
            $product = $productModel->getById($item['ProductID']);
            if ($product[0]["Availability"] != 1) {
                $available = false;
                break;
            }
        }
        
        return $available;
    }
}

<?php

require_once __DIR__ . '/OrderStateInterface.php';

class CancelledState implements OrderStateInterface {
    private $context;
    
    public function setContext(OrderStateContext $context) {
        $this->context = $context;
    }
    
    public function placeOrder($data) {
        echo "Order is cancelled. No further action is allowed";
        
        return false;
    }
    
    public function cancelOrder() {
        echo "Order is cancelled. No further action is allowed";
        
        return false;
    }
    
    public function deliverOrder() {
        echo "Order is cancelled. No further action is allowed";
        
        return false;
    }
    
    public function completeOrder() {
        echo "Order is cancelled. No further action is allowed";
        
        return false;
    }
}

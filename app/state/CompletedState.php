<?php

require_once __DIR__ . '/OrderStateInterface.php';

class CompletedState implements OrderStateInterface {
    private $context;
    
    public function setContext(OrderStateContext $context) {
        $this->context = $context;
    }
    
    public function placeOrder($data) {
        echo "Order is delivered. No further action is allowed";
        
        return false;
    }
    
    public function cancelOrder() {
        echo "Order is delivered. No further action is allowed";
        
        return false;
    }
    
    public function deliverOrder() {
        echo "Order is delivered. No further action is allowed";
        
        return false;
    }
    
    public function completeOrder() {
        echo 'The order is delivered. No further action is allowed';
        
        return false;
    }
}

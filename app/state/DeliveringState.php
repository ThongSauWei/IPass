<?php

require_once __DIR__ . '/OrderStateInterface.php';
require_once __DIR__ . '/CompletedState.php';

class DeliveringState implements OrderStateInterface {
    private $context;
    
    public function setContext(OrderStateContext $context) {
        $this->context = $context;
    }
    
    public function placeOrder($data) {
        echo "Order is already placed. Cannot place order at this time";
        
        return false;
    }
    
    public function cancelOrder() {
        echo "Order is on delivery, cannot cancel anymore";
        
        return false;
    }
    
    public function deliverOrder() {
        echo "Order is already on delivered";
        
        return false;
    }
    
    public function completeOrder() {
        echo 'The order has been delivered';
        
        $this->context->changeState(new CompletedState());
        
        return true;
    }
}

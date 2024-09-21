<?php

require_once 'OrderStateInterface.php';
require_once 'PendingState.php';

class CreatedState implements OrderStateInterface {
    private $context;
    
    public function setContext(OrderStateContext $context) {
        $this->context = $context;
    }
    
    public function placeOrder($data) {
        $orderModel = $this->context->getModel();
        
        if ($orderModel->createOrder($data)) {
            echo 'Order is placed';
            
            $this->context->changeState(new PendingState());
            
            return true;
        }
        
        return false;
    }
    
    public function cancelOrder() {
        echo "You cannot cancel the order at this state";
        
        return false;
    }
    
    public function deliverOrder() {
        echo "You will need to accept the order before you can deliver it";
        
        return false;
    }
    
    public function completeOrder() {
        echo 'The order has not delivered yet, cannot be completed';
        
        return false;
    }
}

<?php

require_once '../core/NewModel.php';
require_once '../state/OrderStateContext.php';
require_once '../state/CancelledState.php';
require_once '../state/CompletedState.php';
require_once '../state/CreatedState.php';
require_once '../state/DeliveringState.php';
require_once '../state/PendingState.php';

class OrderModel extends NewModel {
    protected $table = 'orders';
    
    public function createOrder($order) {
        $this->insert($order)->execute();
        
        return true;
    }
    
    public function getOrder($orderID) {
        return $this->findAll()->where("OrderID", $orderID)->execute()[0];
    }
    
    public function getOrderContext($orderID) {
        $order = $this->findAll()->where("OrderID", $orderID)->execute()[0];
        
        $stateClass = $this->getStateClassFromStatus($order["Status"]);
        
        $orderContext = new OrderStateContext($orderID, new $stateClass);
        
        return $orderContext;
    }
    
    public function getOrdersByCustomer($customerID, $limit = 1000, $offset = 0) {
        return $this->findAll()->where("CustomerID", $customerID)->limit($limit)->offset($offset)->execute();
    }
    
    public function getOrderCount($customerID = null) {
        $orderList = isset($customerID)? $this->findAll()->where("CustomerID", $customerID)->execute() : $this->findAll()->execute();
        
        if (is_array($orderList)) {
            return count($orderList);
        }
        return 0;
    }
    
    public function getAllOrders() {
        return $this->findAll()->execute();
    }
    
    public function updateStatus($orderID, $status) {
        $this->update("Status", $status)->where("OrderID", $orderID)->execute();
    }
    
    public function updatePaymentMethod($orderID, $paymentMethod) {
        $this->update("PaymentType", $paymentMethod)->where("OrderID", $orderID)->execute();
    }
    
    public function deleteOrder($orderID) {
        $this->delete()->where("OrderID", $orderID)->execute();
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
    
    private function getStateClassFromStatus($status) {
        switch ($status) {
            case 'Created':
                return CreatedState::class;
            case 'Pending':
                return PendingState::class;
            case 'Delivering':
                return DeliveringState::class;
            case 'Completed':
                return CompletedState::class;
            case 'Cancelled':
                return CancelledState::class;
        }
    }
}


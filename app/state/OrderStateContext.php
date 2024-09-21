<?php

require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/CreatedState.php';

class OrderStateContext {
    private $state;
    private $model;
    private $orderID;
    
    public function __construct($orderID, OrderStateInterface $state = null) {
        if ($state === null) {
            $state = new CreatedState();
        }
        
        $this->changeState($state);
        $this->model = new OrderModel();
        $this->orderID = $orderID;
    }
    
    public function changeState(OrderStateInterface $state) {
        $this->state = $state;
        $this->state->setContext($this);
    }
    
    public function placeOrder($data) {
        if ($this->state->placeOrder($data)) {
            $this->model->updateStatus($this->orderID, "Pending");
        }
    }
    
    public function cancelOrder() {
        if ($this->state->cancelOrder()) {
            $this->model->updateStatus($this->orderID, "Cancelled");
        }
    }
    
    public function deliverOrder() {
        if ($this->state->deliverOrder()) {
            if ($this->state instanceof DeliveringState) {
                $this->model->updateStatus($this->orderID, "Delivering");
            } else if ($this->state instanceof CancelledState) {
                $this->model->updateStatus($this->orderID, "Cancelled");
            }
        }
    }
    
    public function completeOrder() {
        if ($this->state->completeOrder()) {
            $this->model->updateStatus($this->orderID, "Completed");
        }
    }
    
    public function getModel() {
        return $this->model;
    }
    
    public function getOrderID() {
        return $this->orderID;
    }
}

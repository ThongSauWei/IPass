<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

/**
 *
 * @author User
 */
interface OrderStateInterface {
    public function setContext(OrderStateContext $context);
    public function placeOrder($data);
    public function cancelOrder();
    public function deliverOrder();
    public function completeOrder();
}

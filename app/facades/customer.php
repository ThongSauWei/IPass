<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class CustomerFacade {
    private $customer;

    public function __construct() {
        $this->customer = new Customer();
    }

    public function register($username, $email, $password) {
        return $this->customer->register($username, $email, $password);
    }

    public function login($email, $password) {
        return $this->customer->login($email, $password);
    }
}
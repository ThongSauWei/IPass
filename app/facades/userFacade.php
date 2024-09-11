<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class UserFacade {
    private $adminFacade;
    private $customerFacade;
    private $staffFacade;

    public function __construct() {
        $this->adminFacade = new AdminFacade();
        $this->customerFacade = new CustomerFacade();
        $this->staffFacade = new StaffFacade();
    }

    public function createUser($username, $role) {
        return $this->adminFacade->createUser($username, $role);
    }

    public function registerCustomer($username, $email, $password) {
        return $this->customerFacade->register($username, $email, $password);
    }
}


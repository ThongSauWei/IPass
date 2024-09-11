<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

require_once '../app/Facades/userFacade.php';

class UserController {
    private $userFacade;

    public function __construct() {
        $this->userFacade = new UserFacade();
    }

    public function register($username, $email, $password) {
        return $this->userFacade->registerCustomer($username, $email, $password);
    }

    public function login($email, $password) {
        return $this->userFacade->customerFacade->login($email, $password);
    }

    // Add methods for profile CRUD, password recovery, etc.
}
<?php

require_once '../models/Admin.php';
require_once '../models/Customer.php';
require_once '../models/User.php';

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class UserFacade {

    private $user;
    private $admin;
    private $customer;

    public function __construct() {
        $this->user = new User();
        $this->admin = new Admin();
        $this->customer = new Customer();
    }

    public function registerUser($userData, $customerData = null) {
        $userData['Password'] = $this->user->convertPassword($userData['Password']);
        $this->user->register($userData);

        if ($userData['Role'] === 'customer') {//if the register is customer, register in customer table
            $this->customer->registerCustomer($customerData);
        } else if ($userData['Role'] === 'admin') {// if the register is admin, register in admin table
            $this->admin->createAdmin($userData);
        }
    }

    public function userLogin($identity, $password) {
        try {
            return $this->user->login($identity, $password);
        } catch (Exception $e) {
            error_log('Error during user login: ' . $e->getMessage());
            throw new Exception('Failed to login. Please check your credentials and try again.');
        }
    }

    public function generateUserID() {
        return $this->user->generateUserID();
    }

    public function generateCustomerID() {
        return $this->customer->generateCustomerID();
    }

}

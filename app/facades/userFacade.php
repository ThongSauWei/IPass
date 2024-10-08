<?php

require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Customer.php';
require_once __DIR__ . '/../models/User.php';

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

    // login register part
    public function registerUser($userData, $customerData = null) {
        $this->user->register($userData);

        if ($userData['Role'] === 'customer') {//if the register is customer, register in customer table
            $this->customer->registerCustomer($customerData);
        } else if ($userData['Role'] === 'admin') {// if the register is admin, register in admin table
            $this->admin->addAdmin($userData);
        }
    }

    public function userLogin($identity, $password) {
        $user = $this->user->login($identity, $password);

        if (is_array($user) && isset($user['error']) && $user['error'] === 'inactive') {
            return ['error' => 'inactive']; // Handle the inactive account case
        }

        //check the user if is admin and check the adminrole superadmin or staff;
        if ($user && $user['Role'] === 'admin') {
            $adminRole = $this->admin->getAdminRoleByUserID($user['UserID']);
            if ($adminRole) {
                $user['AdminRole'] = $adminRole;
            }
        }

        return $user;
    }

    public function generateUserID() {
        return $this->user->generateUserID();
    }

    public function generateCustomerID() {
        return $this->customer->generateCustomerID();
    }
    
    public function generateAdminID() {
        return $this->admin->generateAdminID();
    }

    public function updateUserStatus($userID, $newStatus) {
        return $this->user->updateStatus($userID, $newStatus);
    }

    //get data part
    public function usernameExists($username) {
        return $this->user->findByUsername($username);
    }

    public function emailExists($email) {
        return $this->user->findByEmail($email);
    }

    public function getUserBirthday($userID) {
        return $this->user->getUserBirthday($userID);
    }

    public function getCustomerDetails($userID) {
        return $this->customer->findCustByUserID($userID);
    }

    public function getUserDetails($userID) {
        return $this->user->findUserByUserID($userID);
    }

    public function getUserProfileImage($userID) {
        return $this->user->getUserProfileImage($userID);
    }

    public function getUserGender($userID) {
        return $this->user->getUserGender($userID);
    }

    //admin data part
    public function getAdminRoleByUserID($userID) {
        return $this->admin->getAdminRoleByUserID($userID);
    }

    public function getAllStaff() {
        return $this->admin->displayAllStaff();
    }
    
    public function addStaff() {
        //register user with role as 'admin' (since staff is a type of admin)
        $userData['Role'] = 'admin';
        $this->user->register($userData);
        
        //create staff in admin table then AdminRole = staff
        $this->admin->addAdmin($userData);
    }

    public function deleteStaff($userID) {
        return $this->admin->deleteStaff($userID);
    }

    public function staffSelected($userID) {
        return $this->admin->staffSelected($userID);
    }

    //customer data part
    public function getAllCustomers() {
        return $this->customer->displayAllCustomer();
    }

    public function deleteCustomer($userID) {
        return $this->customer->deleteCustomer($userID);
    }

    public function customerSelected($userID) {
        return $this->customer->customerSelected($userID);
    }
    
    public function findCustByCustID($customerID){
        return $this->customer->findCustByCustID($customerID);
    }

    //profile part
    public function updateProfile($userID, $userData, $customerData) {
        // Update the user profile
        $this->user->updateProfile($userID, $userData);

        // Update customer-specific details
        $this->customer->updateProfile($userID, $customerData);
    }

    public function updateAdminProfile($userID, $userData) { //accuatly just use back and just update user data
        $this->user->updateProfile($userID, $userData);
    }

}

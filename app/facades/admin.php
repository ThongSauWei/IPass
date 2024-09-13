<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class AdminFacade{
    private $admin;
    
    // init admin properties (if not init will take the null by default)
    public function __construct($adminID = null, $adminRole = null) {
        $this->admin = new Admin();
    }
    
    public function createUser($username, $role) {
        return $this->admin->createUser($username, $role);
    }

    public function deleteUser($userID) {
        return $this->admin->deleteUser($userID);
    }

    public function updateUser($userID, $newDetails) {
        return $this->admin->updateUser($userID, $newDetails);
    }
}
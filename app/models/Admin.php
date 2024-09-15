<?php

require_once './User.php';
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Admin extends User {
    protected $table = 'Admin';
    
    public function __construct() {
        parent::__construct();
        $this->table = 'Admin';
    }
    
    //get the admin details
    public function getAdminDetails() {
        return $this->where('UserID', $this->id)->execute();
    }
    
    public function hasRole($role) {
        $admin = $this->getAdminDetails();
        //check admin role
        if($admin) {// check admin exist
            return isset($admin[0]['AdminRole']) && $admin[0]['AdminRole'] === $role;//check first element exist and check if the value matches with the &role
        }// === compare value
        return false;
    }
    
    //check staff
    public function isStaff() {
        return $this->hasRole('staff');
    }
    
    //check admin
    public function isAdmin() {
        return $this->hasRole('admin');
    }
    
}
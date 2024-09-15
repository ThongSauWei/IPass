<?php

require_once 'User.php';
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Admin extends User {

    protected $table = 'admin';

    public function __construct() {
        parent::__construct();
    }

    //creating new staff or customer for admin
    public function createAdmin($data, $isAdmin = false) {
        $this->insert($data)->execute();

        if ($isAdmin) {
            $adminData = [
                'AdminID' => $this->generateAdminID(),
                'UserID' => $data['UserID'],
                'AdminRole' => 'admin'
            ];

            $this->insert($adminData)->execute();
        }
    }

    public function updateAdminRole($userID, $role) {
        $this->update('AdminRole', $role)
                ->where('UserID', $userID)
                ->execute();
    }

    public function generateAdminID() {
        $char = 'A';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('AdminID', 'DESC')//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id
            $lastID = !empty($result) ? $result[0]['AdminID'] : null;

            //determine next ID
            if ($lastID) {
                //take last num use intval convert 0001 to 1
                $lastNum = intval(substr($lastID, strlen($prefix))); //substr extracts a portion of a string, strlen return length
                //then +1
                $nextNum = $lastNumber + 1;
            } else {
                $nextNum = 1;
            }

            // let the next id start with 0
            $nextID = $prefix . str_pad($nextNum, $length, '0' . STR_PAD_LEFT);

            return $nextID;
        } catch (Exception $e) {

            error_log('Error generating AdminID: ' . $e->getMessage());
            throw new Exception('Failed to generate AdminID. Please try again later.');
        }
    }

//    //get the admin details
//    public function getAdminDetails() {
//        return $this->where('UserID', $this->id)->execute();
//    }
//    
//    public function hasRole($role) {
//        $admin = $this->getAdminDetails();
//        //check admin role
//        if($admin) {// check admin exist
//            return isset($admin[0]['AdminRole']) && $admin[0]['AdminRole'] === $role;//check first element exist and check if the value matches with the &role
//        }// === compare value
//        return false;
//    }
//    
//    //check staff
//    public function isStaff() {
//        return $this->hasRole('staff');
//    }
//    
//    //check admin
//    public function isAdmin() {
//        return $this->hasRole('admin');
//    }
}

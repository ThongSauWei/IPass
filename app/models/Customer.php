<?php

require_once __DIR__ . '/User.php';

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Customer extends User {
    protected $table = 'customer';
    
    public function registerCustomer($data) {
        //insert customer data to customer
        $this->insert($data)->execute();
    }
    
    public function generateCustomerID() {
        $char = 'U';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('CustomerID', $DESC)//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id
            $lastID = !empty($result) ? $result[0]['CustomerID'] : null;

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

            error_log('Error generating CustomerID: ' . $e->getMessage());
            throw new Exception('Failed to generate CustomerID. Please try again later.');
        }
    }
}
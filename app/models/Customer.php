<?php

require_once __DIR__ . '/User.php';

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Customer extends User {

    protected $table = 'customer';

    //get data part
    public function findCustByUserID($userID) {//get customer info by user id
        $result = $this->findAll()
                ->where('UserID', $userID)
                ->limit(1)
                ->execute();

        if (!empty($result)) {
            return $result[0]; // Return all customer details as an associative array
        }
        return null; // Return null if no customer found
    }

    //profile part
    public function updateProfile($userID, $customerData) {
        foreach ($customerData as $column => $value) {
            $this->update($column, $value);
        }

        $this->where('UserID', $userID);

        return $this->execute();
    }

    //register part
    public function generateCustomerID() {
        $char = 'C';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('CustomerID', 'DESC')//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id if no record set null
            $lastID = !empty($result) ? $result[0]['CustomerID'] : null;

            //determine next ID
            if ($lastID) {
                //take last num use intval convert 0001 to 1
                $lastNum = intval(substr($lastID, strlen($char))); //substr extracts a portion of a string, strlen return length
                //then +1
                $nextNum = $lastNum + 1;
            } else {
                $nextNum = 1;
            }

            // the next UserID with the required format 'U0001'
            $nextID = $char . str_pad($nextNum, $length, '0', STR_PAD_LEFT);

            return $nextID;
        } catch (Exception $e) {

            error_log('Error generating CustomerID: ' . $e->getMessage());
            throw new Exception('Failed to generate CustomerID. Please try again later.');
        }
    }

}

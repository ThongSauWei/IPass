<?php

require_once 'User.php';

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Customer extends User {

    protected $table = 'customer';

    //admin site CRUD customer
    //display all customer
    public function displayAllCustomer() {
        try {
            // Get all customer members from the `customer` table
            $customerList = $this->findAll(['UserID', 'CustomerID', 'CustomerName', 'PhoneNumber' , 'Address', 'Point'])
                    ->execute();  // Fetch all customers without filtering by `UserID`

            $fullCustomerDetails = [];

            // Loop through each customer record and fetch user details
            foreach ($customerList as $customer) {
                $userID = $customer['UserID'];

                // Create a new User model to fetch user details
                $userModel = new User();
                $userDetails = $userModel->findAll(['Username', 'Email', 'Birthday', 'Gender', 'isActive'])
                        ->where('UserID', $userID) // Fetch specific user details based on UserID
                        ->limit(1) // Fetch only one user with that ID
                        ->execute();

                // Merge the customer and user details together
                if (!empty($userDetails)) {
                    $fullCustomerDetails[] = array_merge($customer, $userDetails[0]);
                } else {
                    // If user details are not found, keep the customer info as is
                    $fullCustomerDetails[] = $customer;
                }
            }

            return $fullCustomerDetails;
        } catch (Exception $e) {
            throw new Exception('Failed to display customer: ' . $e->getMessage());
            return []; // Return an empty array on error
        }
    }

    public function deleteCustomer($userID) {
        try {
            //delete from the customer table
            $this->delete()
                    ->where('UserID', $userID)
                    ->execute();

            //delete from the user table
            $this->table = 'user';
            $this->delete()
                    ->where('UserID', $userID)
                    ->execute();

            return true;  //return true if both deletions are successful
        } catch (Exception $e) {
            return false;  //return false if something goes wrong
        }
    }

    public function customerSelected($userID) {
        try {
            // Fetch customer data from the 'customer' table
            $customerDetails = $this->findAll(['UserID', 'CustomerID', 'CustomerName', 'PhoneNumber','Address', 'Point'])
                    ->where('UserID', $userID)
                    ->limit(1)
                    ->execute();

            // If customer found in 'customer' table
            if (!empty($customerDetails)) {
                // Fetch user details from the 'user' table, including ProfileImage
                $userModel = new User();
                $userDetails = $userModel->findAll(['Username', 'Email', 'Birthday', 'Gender', 'ProfileImage', 'isActive'])
                        ->where('UserID', $userID)
                        ->limit(1)
                        ->execute();

                // Merge customer and user details if user data is found
                if (!empty($userDetails)) {
                    return array_merge($customerDetails[0], $userDetails[0]);
                }
            }

            return null;  // Return null if no details are found
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve customer details: ' . $e->getMessage());
        }
    }

    public function registerCustomer($data) {
        //insert customer data to customer
        $this->insert($data)->execute();
    }

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

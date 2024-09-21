<?php
require_once __DIR__ . '/../core/NewModel.php';
require_once __DIR__ . '/User.php';
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

class Admin extends NewModel {

    protected $table = 'admin';

    public function __construct() {
        parent::__construct();
    }
    
    //admin site CRUD staff
    //display all the staff adminrole in admin table
    public function displayAllStaff() {
        try {
            // Step 1: Fetch all staff members from the `admin` table
            $staffList = $this->findAll(['UserID', 'AdminID'])
                    ->where('AdminRole', 'staff')
                    ->execute();

            $fullStaffDetails = [];

            // Step 2: Loop through each staff record and fetch user details
            foreach ($staffList as $staff) {
                $userID = $staff['UserID'];

                // Create a new User model to fetch user details
                $userModel = new User();
                $userDetails = $userModel->findAll(['Username', 'Email', 'Birthday', 'Gender', 'isActive'])
                        ->where('UserID', $userID)
                        ->limit(1)
                        ->execute();

                // Merge the staff and user details together
                if (!empty($userDetails)) {
                    $fullStaffDetails[] = array_merge($staff, $userDetails[0]);
                } else {
                    // If user details are not found, keep the staff info as is
                    $fullStaffDetails[] = $staff;
                }
            }

            return $fullStaffDetails;
        } catch (Exception $e) {
            throw new Exception('Failed to display staff.');
            return []; // Return an empty array on error
        }
    }
    
    //add admin
    public function addAdmin($data) {
        $adminData = [
            'AdminID' => $this->generateAdminID(),
            'UserID' => $data['UserID'],
            'AdminRole' => 'staff'
        ];
        
        $this->insert($adminData)->execute();
    }

    //delete staff
    public function deleteStaff($userID) {
        try {
            // Admin deletion logic
            $this->delete()
                    ->where('UserID', $userID)
                    ->execute();

            // User deletion logic
            $this->table = 'user';
            $this->delete()
                    ->where('UserID', $userID)
                    ->execute();

            return true;  // Return true if both deletions are successful
        } catch (Exception $e) {
            return false;  // Return false if something goes wrong
        }
    }

    public function staffSelected($userID) {
        try {
            // Fetch staff data from the 'admin' table
            $staffDetails = $this->findAll(['UserID', 'AdminID', 'AdminRole'])
                    ->where('UserID', $userID)
                    ->limit(1)
                    ->execute();

            // If staff found in 'admin' table
            if (!empty($staffDetails)) {
                // Fetch user details from the 'user' table, including ProfileImage
                $userModel = new User();
                $userDetails = $userModel->findAll(['Username', 'Email', 'Birthday', 'Gender', 'ProfileImage'])
                        ->where('UserID', $userID)
                        ->limit(1)
                        ->execute();

                // Merge staff and user details if user data is found
                if (!empty($userDetails)) {
                    return array_merge($staffDetails[0], $userDetails[0]);
                }
            }

            return null;  // Return null if no details are found
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve staff details: ' . $e->getMessage());
        }
    }

    public function getAdminRoleByUserID($userID) {
        try {
            // Fetch the admin role for the given user
            $result = $this->findAll(['AdminRole'])
                    ->where('UserID', $userID)
                    ->limit(1)
                    ->execute();

            // Check if result exists and return the role
            if (!empty($result)) {
                return $result[0]['AdminRole']; // Return the AdminRole
            } else {
                return null; // Return null if no admin found
            }
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve admin.');
        }
    }

//    //creating new staff or customer for admin
//    public function createAdmin($data, $isAdmin = false) {
//        $this->insert($data)->execute();
//
//        if ($isAdmin) {
//            $adminData = [
//                'AdminID' => $this->generateAdminID(),
//                'UserID' => $data['UserID'],
//                'AdminRole' => 'staff'
//            ];
//
//            $this->insert($adminData)->execute();
//        }
//    }
//    public function updateAdminRole($userID, $role) {
//        $this->update('AdminRole', $role)
//                ->where('UserID', $userID)
//                ->execute();
//    }



    public function generateAdminID() {
        $char = 'A';
        $length = 4; //0000

        try {
            // last id num
            $result = $this->findAll()
                    ->orderBy('AdminID', 'DESC')//the large num
                    ->limit(1) //make sure only 1 row return
                    ->execute();
            //then get the last id if no record set null
            $lastID = !empty($result) ? $result[0]['AdminID'] : null;

            //determine next ID
            if ($lastID) {
                //take last num use intval convert 0001 to 1
                $lastNum = intval(substr($lastID, strlen($char))); //substr extracts a portion of a string, strlen return length
                //then +1
                $nextNum = $lastNum + 1;
            } else {
                $nextNum = 1;
            }

            // the next AdminID with the required format 'U0001'
            $nextID = $char . str_pad($nextNum, $length, '0', STR_PAD_LEFT);

            return $nextID;
        } catch (Exception $e) {
            throw new Exception('Failed to generate AdminID.');
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

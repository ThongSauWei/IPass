<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start(); //start

class SessionManager {

    public static function checkUserRole() {// static direct access no need create instance
        if (isset($_SESSION['user'])) { //check user if login not
            $userRole = $_SESSION['user']['Role']; //user table role = admin or customer

            if ($userRole === 'admin') {
                header('Location: ../views/Admin/dashboard.view.php'); //if the Role = 'admin' navi to adminsite
            } else {
                header('Location: ../views/Customer/homepage.view.php'); //else then go to customer homepage
            }
            exit();
        } else {
            header('Location: ../views/Customer/login.php'); //if user have login go to login page
            exit();
        }

        
    }
    public static function checkAccess($requiredRole) {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: ../views/Customer/login.php');
            exit();
        }

        $userRole = $_SESSION['user']['Role']; 

        if ($userRole !== $requiredRole) { // chec role matches the required role
            if ($requiredRole === 'admin') {
                header('Location: ../views/Customer/homepage.view.php'); //non-admin users to the customer homepage
            } else {
                header('Location: ../views/Customer/login.php'); //users who don't match the required role to the login page
            }
            exit();
        }
    }
    
    public static function logout() {
        session_start(); //start the session
        session_unset(); //clear all session
        session_destroy(); //destroy the session
        header('Location: ../views/Customer/login.php'); //go to the login page
        exit(); //stop execute
    }
    

}

<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

session_start(); //start

class SessionManager {
    public static function startSession() {//if session havent start, start the session
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
    }
    
    //store user in session
    public static function loginUser($user){
        self::startSession();
        $_SESSION['user'] = $user;
        session_regenerate_id();
        $_SESSION['last_activity'] = time();
    }
    
    //check if the user is logged in
    public static function loggedIn() {
        self::startSession();
        return isset($_SESSION['user']); //return true if the user is login d
    }
    
    //get current loggedin user details
    public static function getUser(){
        self::startSession();
        return $_SESSION['user'] ?? null; //if not login return null or details
    }
    
    //check is admin
    public static function isAdmin(){
        self::startSession();
        return isset($_SESSION['user']['Role'])&&$_SESSION['user']['Role'] === 'admin';
    }
    
    public static function superAdmin(){
        self::startSession();
        return isset($_SESSION['user']['AdminRole']) && $_SESSION['user']['AdminRole'] === 'superadmin';// return if the user admin role is superadmin
    }
    
    public static function staff(){
        self::startSession();
        return isset($_SESSION['user']['AdminRole']) && $_SESSION['user']['AdminRole'] === 'staff';
    }
    
    //for the page need user login one
    public static function requireLogin(){ //require login then get more action for the viewer
        if(!self::loggedIn()){
            header('Location: http://localhost/IPass/app/views/Customer/login.php');//go to login
            exit();
        } else {
            self::validateSession();
        }
    }
    
    public static function validateSession() {
        $timeout = 3600;
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            self::logout();
            header('Location: ../Customer/login.php');
            exit();
        }
        
        $_SESSION['last_activity'] = time();
    }
   
    
    public static function logout(){
        self::startSession();
        session_unset();// remove all variable
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy(); //destroy session
    }
    
}

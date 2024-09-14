<?php

require_once 'config.php';

class NewDatabase {
    private static $instance = null;
    private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO("mysql:hostname=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new NewDatabase();
        }
        
        return self::$instance;
    }
    
    public function query($sql, $data = []) {
        $stmt = $this->pdo->prepare($sql);
        $check = $stmt->execute($data);
        
        if ($check) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        
        return false;
    }
}


<?php

require_once __DIR__ . '/config.php';

class NewDatabase {
    private static $instance = null;
    private $pdo;
    private $connected = false;
    
    public function __construct() {
        
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new NewDatabase();
        }
        
        return self::$instance;
    }
    
    public function query($sql, $data = []) {
        $this->connect();
        
        $stmt = $this->pdo->prepare($sql);
        $check = $stmt->execute($data);
        
        if ($check) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->close();
            
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        
        $this->close();
        
        return false;
    }
    
    private function connect() {
        if (!$this->connected) {
            $this->pdo = new PDO("mysql:hostname=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connected = true;
        }
    }
    
    private function close() {
        $this->pdo = null;
        $this->connected = false;
    }
}
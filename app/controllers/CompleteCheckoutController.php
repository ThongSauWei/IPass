<?php

require_once __DIR__ . '/../core/SessionManager.php';
require_once __DIR__ . '/../models/OrderModel.php';

class CompleteCheckoutController {
    private $model;
    
    public function constructor($model) {
        $this->model = $model;
    }
    
    public function showPage() {
        if($_SERVER["REQUEST_METHOD"] === 'GET') {
            try {
                SessionManager::requireLogin();
                $user = $_SESSION['user'];
                $username = $user["Username"];

                $orderID = $_GET['orderID'];
                $totalAmount = $_GET['totalAmount'];

                require dirname(__DIR__, 1) . '/views/Customer/complete-checkout.php';
            } catch (Exception $ex) {
                echo 'Error: ' . $ex->getMessage();
            }
        }
    }
}

$model = new OrderModel();
$controller = new CompleteCheckoutController($model);

if (isset($_GET['action']) && $_GET['action'] === 'showPage') {
    $controller->showPage();
} else {
    $controller->showPage();
}
<?php

require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/OrderDetailsModel.php';
require_once __DIR__ . '/../../dto/OrderDTO.php';
require_once __DIR__ . '/../../dto/OrderDetailsDTO.php';
require_once __DIR__ . '/../../models/Product.php';

class OrderAPIController {
    private $orderModel;
    private $orderDetailsModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->orderDetailsModel = new OrderDetailsModel();
    }

    // Get a list of all orders
    public function listOrders() {
        try {
            $orders = $this->orderModel->getAllOrders();
            $this->response(200, $orders);
        } catch (Exception $e) {
            $this->response(500, ['error' => $e->getMessage()]);
        }
    }

    // Get details of a single order by ID
    public function showOrder($id) {
        try {
            $order = $this->orderModel->getOrder($id);
            if ($order) {
                $this->response(200, $order);
            } else {
                $this->response(404, ['error' => 'Order not found']);
            }
        } catch (Exception $e) {
            $this->response(500, ['error' => $e->getMessage()]);
        }
    }

    // Create a new order
    public function createOrder() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->isValidOrderData($data)) {
            try {
                // Extract order-related information
                $customerID = $data['customerID'];
                $discount = $data['discount'];
                $deliveryAddress = $data['deliveryAddress'];
                $paymentType = $data['paymentType'];
                $deliveryFee = $data['deliveryFee'];
                $purchasedAmount = 0;

                // Create the order
                $orderID = $this->orderModel->getNewOrderID();
                
                $productModel = new Product();

                $orderDetailsList = [];
                // Extract order details and insert them
                $orderDetails = $data['orderDetails'];
                foreach ($orderDetails as $detail) {
                    $product = $productModel->getById($detail["productID"])[0];
                    $orderItem = new OrderDetailsDTO($orderID, $detail["productID"], $product["Price"], $detail["quantity"], $detail["discount"]);
                    $orderDetailsList[] = $orderItem;

                    $purchasedAmount += ($product["Price"] * $detail["quantity"] - $detail["discount"]);
                }

                $order = new OrderDTO($orderID, $customerID, $purchasedAmount, $discount, $deliveryFee, date('Y-m-d'), $deliveryAddress, $paymentType);
                $this->orderModel->createOrder($order);
                $this->orderModel->updateStatus($orderID, "Pending");

                foreach ($orderDetailsList as $item) {
                    $this->orderDetailsModel->insertOrderDetails($item);
                }
                http_response_code(201);
                echo json_encode(["message" => "Order created successfully", "orderID" => $orderID]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(["error" => $e->getMessage()]);
            }
        } else {
            // Invalid order data
            http_response_code(400);
            echo json_encode(["error" => "Invalid order data"]);
        }
    }

    // Update an existing order by ID
    public function updateOrder($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->isValidOrderData($data)) {
            try {
                $customerID = $data['customerID'];
                $discount = $data['discount'];
                $deliveryAddress = $data['deliveryAddress'];
                $paymentType = $data['paymentType'];
                $deliveryFee = $data['deliveryFee'];
                $purchasedAmount = 0;
                
                $productModel = new Product();
                
                $orderDetails = $data['orderDetails'];
                foreach ($orderDetails as $detail) {
                    $product = $productModel->getById($detail["productID"])[0];
                    $orderItem = new OrderDetailsDTO($id, $detail["productID"], $product["Price"], $detail["quantity"], $detail["discount"]);
                    
                    $orderDetail = $this->orderDetailsModel->getOrderDetails($id, $detail["productID"]);
                    if (is_array($orderDetail) && !empty($orderDetail)) {
                        $this->orderDetailsModel->updateOrderDetails($id, $detail["productID"], $orderItem);
                    } else {
                        $this->orderDetailsModel->insertOrderDetails($orderItem);
                    }

                    $purchasedAmount += ($product["Price"] * $detail["quantity"] - $detail["discount"]);
                }
                
                $order = new OrderDTO($id, $customerID, $purchasedAmount, $discount, $deliveryFee, date('Y-m-d'), $deliveryAddress, $paymentType);
                $this->orderModel->updateOrder($id, $order);
                
                $this->response(200, ['success' => true]);
            } catch (Exception $e) {
                $this->response(500, ['error' => $e->getMessage()]);
            }
        } else {
            $this->response(400, ['error' => 'Invalid order data']);
        }
    }

    // Delete an order by ID
    public function deleteOrder($id) {
        try {
            $this->orderDetailsModel->clearAllOrderDetails($id);
            $this->orderModel->deleteOrder($id);
            $this->response(200, ['success' => true]);
        } catch (Exception $e) {
            $this->response(500, ['error' => $e->getMessage()]);
        }
    }

    // Validate incoming data
    private function isValidOrderData($data) {
        return isset($data['customerID'], $data['deliveryAddress'], $data['paymentType'], $data['discount'], $data['deliveryFee'], $data['orderDetails']) &&
                is_numeric($data['discount']) && is_numeric($data['deliveryFee']);
    }

    // Handle API responses with proper headers
    private function response($status_code, $data) {
        http_response_code($status_code);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}

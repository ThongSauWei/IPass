<?php

require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Product.php';
//require_once __DIR__ . '/../models/ProductLogger.php';

class WishlistController {

    private $wishlist;
    private $product;
//    private $logger;

    public function __construct() {
        $this->wishlist = new Wishlist();
        $this->product = new Product();
//        $this->logger = new ProductLogger();
    }

    public function showWishlist($customerID) {
        return $this->wishlist->getWishlistItems($customerID);
    }

    public function updateWishlistQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'updateWishlistQuantity') {
                $wishlistId = $_POST['wishlistId'] ?? null;
                $productId = $_POST['productId'] ?? null;
                $quantity = $_POST['quantity'] ?? null;

                if ($wishlistId && $productId && $quantity !== null) {
                    $updated = $this->wishlist->updateWishlistItemQuantity($wishlistId, $productId, (int) $quantity);

                    if ($updated) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Failed to update quantity.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
                }
                exit;
            }
        }
    }

    public function handleRequest() {
        $action = $_POST['action'] ?? null;
        if ($action === 'updateWishlistQuantity') {
            $this->updateWishlistQuantity();
        } if ($action === 'addToCart') {
            $this->addToCart();
        } elseif ($action === 'deleteWishlistItem') {
            $this->deleteWishlistItem();
        }
    }

    public function deleteWishlistItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'deleteWishlistItem') {
                $wishlistId = $_POST['wishlistId'] ?? null;
                $productId = $_POST['productId'] ?? null;

                if ($wishlistId && $productId) {
                    try {
                        $deleted = $this->wishlist->deleteWishlistItem($wishlistId, $productId);

                        if ($deleted) {
//                            header('Location: /IPass/app/views/Customer/wish.php');
                            echo json_encode(['success' => true]);
                            exit;
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Failed to delete item.']);
                            exit;
                        }
                    } catch (Exception $e) {
                        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                        exit;
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
                    exit;
                }
            }
        }
    }

    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_POST['productId'] ?? null;
            $customerId = $_POST['custId'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            $wishlistId = $_POST['wishlistId'] ?? null; // Get wishlistId from POST

            if ($productId && $customerId && $quantity !== null) {
                try {
                    // Add to cart
                    $result = $this->product->addToCart($productId, $customerId, (int) $quantity);

                    if ($wishlistId && $productId) {
                        try {
                            $deleted = $this->wishlist->deleteWishlistItem($wishlistId, $productId);

                            if ($deleted) {
//                            header('Location: /IPass/app/views/Customer/wish.php');
                                echo json_encode(['success' => true]);
                                exit;
                            } else {
                                echo json_encode(['success' => false, 'message' => 'Failed to delete item.']);
                                exit;
                            }
                        } catch (Exception $e) {
                            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
                            exit;
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
                        exit;
                    }
                } catch (Exception $e) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                }
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid input.']);
                exit;
            }
        }
    }

}

// Initialize and handle requests
$controller = new WishlistController();
$controller->handleRequest();

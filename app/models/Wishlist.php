<?php

require_once __DIR__ . '/../core/NewModel.php';
//require_once __DIR__ . '/ProductLogger.php';
require_once __DIR__ . '/../core/NewDatabase.php';

class Wishlist extends NewModel {

    protected $table = 'Wishlist';
//    private $logger;

    public function __construct() {
        parent::__construct();
//        $this->logger = new ProductLogger();
    }

    public function getWishlistItems($customerId) {
        // Ensure we're querying the Wishlist table
        $this->table = 'Wishlist';

        // Check if there's a wishlist for the given customer
        $wishlist = $this->findAll()
                ->where('CustomerID', $customerId)
                ->execute();

        if (empty($wishlist)) {
            return []; // No wishlist found for the customer
        }

        // Use the WishlistID to retrieve products from WishlistItem table
        $wishlistId = $wishlist[0]['WishListID'];
        $this->table = 'WishlistItem'; // Switch to WishlistItem table
        $wishlistItems = $this->findAll()
                ->where('WishlistID', $wishlistId)
                ->execute();

        if (empty($wishlistItems)) {
            return []; // No items found in the wishlist
        }

        // Get product details for each item in the wishlist
        $products = [];
        $this->table = 'Product'; // Switch to the Product table for fetching product details

        foreach ($wishlistItems as $item) {
            $productId = $item['ProductID'];
            $productDetails = $this->findAll()
                    ->where('ProductID', $productId)
                    ->execute();

            if (!empty($productDetails)) {
                // Include WishlistID in the product data
                $products[] = array_merge($productDetails[0], [
                    'Quantity' => $item['Quantity'],
                    'WishlistID' => $wishlistId // Ensure WishlistID is included
                ]);
            }
        }

        return $products;
    }

    public function updateWishlistItemQuantity($wishlistId, $productId, $quantity) {
        try {
            $this->table = 'WishlistItem'; // Switch to WishlistItem table

            $result = $this->update('Quantity', $quantity)
                    ->where('WishlistID', $wishlistId)
                    ->where('ProductID', $productId)
                    ->execute();


            return true;
        } catch (Exception $e) {
            return false; // Return false on error
        }
    }

    public function deleteWishlistItem($wishlistId, $productId) {
        try {
            $this->table = 'WishlistItem'; // Switch to WishlistItem table

            $result = $this->delete()
                    ->where('WishlistID', $wishlistId)
                    ->where('ProductID', $productId)
                    ->execute();

//            

            return true;
        } catch (Exception $e) {
            throw new Exception("Error deleting wishlist item.");
            return false;
        }
    }

}

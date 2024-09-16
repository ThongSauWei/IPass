<?php

require_once __DIR__ . '/../core/NewModel.php'; // NewModel is now used
require_once __DIR__ . '/ProductLogger.php';
require_once __DIR__ . '/../core/NewDatabase.php';

class Product extends NewModel {

    protected $table = 'product';
    private $logger;
    protected $allowedColumns = ['ProductId', 'ProductName', 'ProductDesc', 'Category', 'Price', 'Weight', 'ProductImage', 'Availability'];

    public function __construct($productData = []) {
        parent::__construct(); 
        $this->logger = new ProductLogger(); 
        // Initialize properties with provided data
        $this->productName = $productData['ProductName'] ?? null;
        $this->productDesc = $productData['ProductDesc'] ?? null;
        $this->category = $productData['Category'] ?? null;
        $this->productPrice = $productData['Price'] ?? null;
        $this->productImage = $productData['ProductImage'] ?? null;
        $this->availability = $productData['Availability'] ?? null;
        $this->weight = $productData['Weight'] ?? null;
    }

    // GENERATE PRODUCT ID
    private function generateProductId() {
        try {
            $this->table = 'product';

            $result = $this->findAll()
                    ->orderBy('ProductID', 'DESC') 
                    ->limit(1) 
                    ->execute();

            // Log the result of the query
            $this->logger->log("Query result for maximum ProductId: " . print_r($result, true));

            $maxId = $result[0]['ProductID'] ?? null;

            $this->logger->log("Maximum ProductId fetched: " . $maxId);

            $number = ($maxId) ? (int) substr($maxId, 1) + 1 : 1;
            $newId = 'P' . str_pad($number, 4, '0', STR_PAD_LEFT);

            $this->logger->log("Generated ProductId: " . $newId);

            return $newId;
        } catch (Exception $e) {
            $this->logger->log("Error generating product ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to generate product ID. Please try again later.");
        }
    }

    // ADD PRODUCT
    public function addProduct() {
        $productId = $this->generateProductId();

        $productData = [
            'ProductId' => $productId,
            'ProductName' => $this->productName,
            'ProductDesc' => $this->productDesc,
            'Category' => $this->category,
            'Price' => $this->productPrice,
            'Weight' => $this->weight,
            'ProductImage' => $this->productImage,
            'Availability' => $this->availability
        ];

        try {
            $this->logger->log("Attempting to add product: " . json_encode($productData));
            $result = $this->insert($productData)->execute();
            $this->logger->log("Product successfully added with ID: $productId");
            return true;
        } catch (Exception $e) {
            $this->logger->log("Error adding product: " . htmlspecialchars($e->getMessage()));
            return false;
        }
    }

    // GET ALL PRODUCTS
    public function getAll() {
        try {
            $this->table = 'product';
            return $this->findAll()->execute();
        } catch (Exception $e) {
            $this->logger->log("Error fetching all products: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch products. Please try again later.");
        }
    }

    // GET PRODUCT BY ID
    public function getById($id) {
        try {
            $this->table = 'product';
            return $this->findAll()->where('ProductId', $id)->execute();
        } catch (Exception $e) {
            $this->logger->log("Error fetching product by ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch product. Please try again later.");
        }
    }

    // GET CATEGORIES
    public function getCategories() {
        try {
            $this->table = 'product';
            return $this->findAll(['Category'])
                            ->execute();
        } catch (Exception $e) {
            // Log the error
            $this->logger->log("Error fetching categories: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch categories. Please try again later.");
        }
    }

    // SEARCH PRODUCTS BY NAME OR CATEGORY
//    public function getProductsBySearch($searchTerm) {
//        try {
//            $searchTerm = '%' . htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') . '%';
//            $products = $this->findAll()
//                    ->where('ProductName', $searchTerm, self::LIKE)
////                    ->orWhere('Category', $searchTerm, self::LIKE)
//                    ->execute();
//
//            $this->logger->log("Products successfully fetched for search term: $searchTerm");
//            return $products;
//        } catch (Exception $e) {
//            $this->logger->log("Error searching products: " . htmlspecialchars($e->getMessage()));
//            throw new Exception("Unable to search products. Please try again later.");
//        }
//    }
    // FILTER PRODUCTS
//    public function filterProducts($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability) {
//        try {
//            $query = $this->findAll();
//
//            if ($category) {
//                $query = $query->where('Category', $category);
//            }
//
//            if ($priceMin !== '' && $priceMax !== '') {
//                $query = $query->where('Price', [$priceMin, $priceMax], 'BETWEEN');
//            }
//
//            if ($weightMin !== '' && $weightMax !== '') {
//                $query = $query->where('Weight', [$weightMin, $weightMax], 'BETWEEN');
//            }
//
//            if ($availability !== '') {
//                $query = $query->where('Availability', $availability);
//            }
//
//            return $query->execute();
//        } catch (Exception $e) {
//            $this->logger->log("Error filtering products: " . htmlspecialchars($e->getMessage()));
//            throw new Exception("Unable to filter products. Please try again later.");
//        }
//    }
    // GET CATEGORIES AS ARRAY
    public function getCategoriesArray() {
        try {
            return $this->getCategories();
        } catch (Exception $e) {
            $this->logger->log("Error fetching categories: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch categories. Please try again later.");
        }
    }

    // GET PRODUCTS BY CATEGORY
    public function getProductsByCategory($category) {
        if (empty($category)) {
            throw new Exception("Category cannot be empty.");
        }

        try {
//            $this->table = 'Product';
            return $this->findAll()
                            ->where('Category', $category)
                            ->where('Availability', 1)
                            ->execute();
        } catch (Exception $e) {
            $this->logger->log("Error fetching products by category: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch products by category. Please try again later.");
        }
    }

    // UPDATE PRODUCT
    public function updateProduct($productData) {
        try {
            foreach ($productData as $column => $value) {
                if ($column !== 'ProductID' && $column !== 'CustomCategory') {
                    // Update each column except for the 'ProductID' and 'CustomCategory'
                    $this->update($column, $value);
                }
            }

            $this->where('ProductID', $productData['ProductID']);

            $this->execute();

            $this->logger->log("Product updated: " . json_encode($productData));

            return true; 
        } catch (Exception $e) {
            $this->logger->log("Error updating product: " . $e->getMessage());
            throw new Exception("Error updating product.");
        }
    }

    // DELETE PRODUCT
    public function deleteProductById($id) {
        try {
            $this->delete()->where('ProductId', $id)->execute();
            $this->logger->log("Product deleted: " . htmlspecialchars($id));
            return true;
        } catch (Exception $e) {
            $this->logger->log("Error deleting product: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Error deleting product.");
        }
    }

    //CHECK PROMOTION
    private function hasPromotion($productId) {
        try {
            $this->logger->log("Checking promotions for ProductID: " . $productId);

            // Retrieve all promotions related to the product
            $this->table = 'PromotionProducts';
            $promotionProductsQuery = $this->findAll()
                    ->where('ProductID', $productId)
                    ->execute();

            // Log if no promotions found for the product
            if (empty($promotionProductsQuery)) {
                $this->logger->log("No promotions found for ProductID: " . $productId);
                return null;
            }

            // store the active promotion
            $activePromotion = null;
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $currentDate = date('Y-m-d');
            $this->logger->log("Current Date: " . $currentDate);

            //find the active one
            foreach ($promotionProductsQuery as $promotionProduct) {
                $promotionID = $promotionProduct['PromotionID'];
                $this->logger->log("Checking PromotionID: " . $promotionID);

                // Check the Promotion table for the PromotionID and date validity
                $this->table = 'Promotion';
                $promotionQuery = $this->findAll()
                        ->where('PromotionID', $promotionID)
                        ->where('StartDate', $currentDate, NewModel::LESS_THAN_OR_EQUAL_TO)
                        ->where('EndDate', $currentDate, NewModel::GREATER_THAN_OR_EQUAL_TO)
                        ->execute();

                if (!empty($promotionQuery)) {
                    $activePromotion = $promotionQuery[0];
                    $this->logger->log("Active promotion found: " . json_encode($activePromotion));
                    break; // Stop once the first active promotion is found
                } else {
                    $this->logger->log("No active promotion for PromotionID: " . $promotionID);
                }
            }

            return $activePromotion;
        } catch (Exception $e) {
            $this->logger->log("Error checking promotion: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to check promotion. Please try again later.");
        }
    }

    //GET OFFER PRICE
    public function getPriceWithPromotion($productId) {
        $this->table = 'Product';
        $productData = $this->findAll()->where('ProductID', $productId)->execute();

        if (!empty($productData)) {
            $price = $productData[0]['Price'];
            $promotion = $this->hasPromotion($productId);

            if ($price !== null && $promotion !== null) {
                $discountType = $promotion['DiscountType'];
                $discountValue = $promotion['DiscountValue'];

                if ($discountType === 'Percentage') {
                    $discountedPrice = $price - ($price * $discountValue / 100);
                } elseif ($discountType === 'Fixed Amount') {
                    $discountedPrice = $price - $discountValue;
                } else {
                    $discountedPrice = $price;
                }

                return [
                    'originalPrice' => $price,
                    'discountedPrice' => max($discountedPrice, 0)
                ];
            }

            return [
                'originalPrice' => $price,
                'discountedPrice' => null
            ];
        }

        return [
            'originalPrice' => null,
            'discountedPrice' => null
        ];
    }

    // ADD TO CART 
    public function addToCart($productId, $customerId, $quantity) {
        try {
            $this->table = 'Cart';
            $existingCart = $this->findAll()
                    ->where('CustomerID', $customerId)
                    ->execute();

            $existingCartId = null;

            if (!empty($existingCart)) {
                // Get the first cart ID for the customer 
                $existingCartId = $existingCart[0]['CartID'];
            }

            // If a cart exists for the customer, check if the product is already in the CartItem table
            if ($existingCartId !== null) {
                $this->table = 'CartItem';
                $cartItem = $this->findAll()
                        ->where('CartID', $existingCartId)
                        ->where('ProductID', $productId)
                        ->execute();

                if (!empty($cartItem)) {
                    // Update the quantity of the existing cart item
                    $newQuantity = $cartItem[0]['Quantity'] + $quantity;
                    $this->table = 'CartItem';
                    $this->update('Quantity', $newQuantity)
                            ->where('CartID', $existingCartId)
                            ->where('ProductID', $productId)
                            ->execute();

                    return [
                        'status' => 'success',
                        'message' => 'Product quantity updated in cart'
                    ];
                }
            }

            // If no cart exists or product is not in the cart, create a new cart if necessary
            if ($existingCartId === null) {
                $this->table = 'Cart';
                $existingCartId = $this->generateCartId(); 
                $cartData = [
                    'CartID' => $existingCartId,
                    'CustomerID' => $customerId
                ];
                $this->insert($cartData)->execute(); 
            }

            // Insert the product into the CartItem table
            $this->table = 'CartItem';
            $cartItemData = [
                'CartID' => $existingCartId,
                'ProductID' => $productId,
                'Quantity' => $quantity
            ];
            $this->insert($cartItemData)->execute();

            return [
                'status' => 'success',
                'message' => 'Product added to cart successfully'
            ];
        } catch (Exception $e) {
            $this->logger->log("Error adding product to cart: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to add product to cart. Please try again later.");
        }
    }

    //  GENERATE CartID
    private function generateCartId() {
        try {
            $this->table = 'Cart';
            $result = $this->findAll()
                    ->orderBy('CartID', 'DESC')
                    ->limit(1)
                    ->execute();

            $maxId = $result[0]['CartID'] ?? null;
            $number = ($maxId) ? (int) substr($maxId, 2) + 1 : 1;
            return 'CT' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
            $this->logger->log("Error generating CartID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to generate CartID. Please try again later.");
        }
    }

}

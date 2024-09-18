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
//            $this->logger->log("Query result for maximum ProductId: " . print_r($result, true));

            $maxId = $result[0]['ProductID'] ?? null;

//            $this->logger->log("Maximum ProductId fetched: " . $maxId);

            $number = ($maxId) ? (int) substr($maxId, 1) + 1 : 1;
            $newId = 'P' . str_pad($number, 4, '0', STR_PAD_LEFT);

//            $this->logger->log("Generated ProductId: " . $newId);

            return $newId;
        } catch (Exception $e) {
            $this->logger->log("Generate ID", "Error", "Error generate new ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to generate product ID. Please try again later.");
        }
    }

//    // ADD PRODUCT
//    public function addProduct() {
//        $productId = $this->generateProductId();
//
//        $productData = [
//            'ProductId' => $productId,
//            'ProductName' => $this->productName,
//            'ProductDesc' => $this->productDesc,
//            'Category' => $this->category,
//            'Price' => $this->productPrice,
//            'Weight' => $this->weight,
//            'ProductImage' => $this->productImage,
//            'Availability' => $this->availability
//        ];
//
//        try {
////            $this->logger->log("Attempting to add product: " . json_encode($productData));
//            $result = $this->insert($productData)->execute();
//            // Log success
//            $this->logger->log("Add Product", "SUCCESS", "Product successfully added with ID: $productId");
//            return true;
//        } catch (Exception $e) {
////            $this->logger->log("Error adding product: " . htmlspecialchars($e->getMessage()));
//            $this->logger->log("Add Product", "ERROR", "Error adding product: " . htmlspecialchars($e->getMessage()));
//            return false;
//        }
//    }
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
            // Log success
            $this->logger->log("Add", "Success", "Product successfully added with ID: $productId");

            $this->logger->log("Product Detail", "Info", "Product Detail : ID - " . htmlspecialchars($productData['ProductId']) . " Name - "
                    . htmlspecialchars($productData['ProductName']));

            // Perform the database insertion
            $result = $this->insert($productData)->execute();

            return true;
        } catch (Exception $e) {
            // Log error
            $this->logger->log("Add", "Error", "Error adding product: " . htmlspecialchars($e->getMessage()));

            return false;
        }
    }

    // GET ALL PRODUCTS
    public function getAll() {
        try {
            $this->table = 'product';
            return $this->findAll()->execute();
        } catch (Exception $e) {
//            $this->logger->log("Error fetching all products: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch", "Error", "Error get all product: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch products. Please try again later.");
        }
    }

    // GET PRODUCT BY ID
    public function getById($id) {
        try {
            $this->table = 'product';
            return $this->findAll()->where('ProductId', $id)->execute();
        } catch (Exception $e) {
//            $this->logger->log("Error fetching product by ID: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch", "Error", "Error get product by ID: " . htmlspecialchars($e->getMessage()));
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
//            $this->logger->log("Error fetching categories: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch", "Error", "Error get Categories: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch categories. Please try again later.");
        }
    }

    // SEARCH PRODUCTS BY NAME OR CATEGORY
    public function getProductsBySearch($searchTerm) {
        try {
            $searchTerm = '%' . htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8') . '%';
            $products = $this->findAll()
                    ->where('ProductName', $searchTerm, self::LIKE)
                    ->orWhere('Category', $searchTerm, self::LIKE)
                    ->execute();

//            $this->logger->log("Products successfully fetched for search term: $searchTerm");
            return $products;
        } catch (Exception $e) {
//            $this->logger->log("Error searching products: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to search products. Please try again later.");
        }
    }

    // FILTER PRODUCTS
    public function filterProducts($category, $priceMin = null, $priceMax = null, $weightMin = null, $weightMax = null, $availability = '') {
        try {
            $this->findAll(); // Start with SELECT * FROM table
            // Add category filter
            if (!empty($category)) {
                $this->where('Category', $category);
            }

            // Add availability filter
            if ($availability !== '') {
                $this->where('Availability', $availability);
            }

            // Handle price range filtering
            if (!empty($priceMin) && !empty($priceMax)) {
                $this->where('Price', $priceMin, NewModel::GREATER_THAN_OR_EQUAL_TO)
                        ->where('Price', $priceMax, NewModel::LESS_THAN_OR_EQUAL_TO);
            } elseif (!empty($priceMin)) {
                $this->where('Price', $priceMin, NewModel::GREATER_THAN_OR_EQUAL_TO);
            } elseif (!empty($priceMax)) {
                $this->where('Price', $priceMax, NewModel::LESS_THAN_OR_EQUAL_TO);
            }

            // Handle weight range filtering
            if (!empty($weightMin) && !empty($weightMax)) {
                $this->where('Weight', $weightMin, NewModel::GREATER_THAN_OR_EQUAL_TO)
                        ->where('Weight', $weightMax, NewModel::LESS_THAN_OR_EQUAL_TO);
            } elseif (!empty($weightMin)) {
                $this->where('Weight', $weightMin, NewModel::GREATER_THAN_OR_EQUAL_TO);
            } elseif (!empty($weightMax)) {
                $this->where('Weight', $weightMax, NewModel::LESS_THAN_OR_EQUAL_TO);
            }

            // Execute the query and return the filtered products
            return $this->execute();
        } catch (Exception $e) {
            $this->logger->log("Error filtering products: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to filter products. Please try again later.");
        }
    }

//    
    // GET CATEGORIES AS ARRAY
    public function getCategoriesArray() {
        try {
            return $this->getCategories();
        } catch (Exception $e) {
//            $this->logger->log("Error fetching categories: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch", "Error", "Error get Categories detail: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch categories. Please try again later.");
        }
    }

    // GET CATEGORIES WITH FIRST PRODUCT IMAGE
    public function getCategoriesWithImages() {
        try {
            $products = $this->getAll();
            $categoriesWithImages = [];

            // Loop through all products and select the first product for each category
            foreach ($products as $product) {
                $category = $product['Category'];
                if (!isset($categoriesWithImages[$category])) {
                    // If this is the first product for the category, use it as the category image
                    $categoriesWithImages[$category] = $product['ProductImage'];
                }
            }

            return $categoriesWithImages;
        } catch (Exception $e) {
//            $this->logger->log("Error fetching categories with images: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch", "Error", "Error get Categories Image: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch categories with images. Please try again later.");
        }
    }

//    // GET PRODUCTS BY CATEGORY
//    public function getProductsByCategory($category) {
//        if (empty($category)) {
//            throw new Exception("Category cannot be empty.");
//        }
//
//        try {
//            $result = $this->findAll()
//                    ->where('Category', $category)
//                    ->where('Availability', 1)
//                    ->execute();
//            // Ensure it always returns an array
//            return is_array($result) ? $result : [];
//        } catch (Exception $e) {
//            $this->logger->log("Fetch", "Error", "Error get product by Categories: " . htmlspecialchars($e->getMessage()));
//            throw new Exception("Unable to fetch products by category. Please try again later.");
//        }
//    }

    public function getProductsByCategory($category) {
        if (empty($category)) {
            throw new Exception("Category cannot be empty.");
        }

        try {
            // Make sure you're querying the Product table
            $this->table = 'Product'; // Ensure the correct table name is used

            $result = $this->findAll()
                    ->where('Category', $category) // Ensure 'Category' is correct and matches the actual DB column
                    ->where('Availability', 1)
                    ->execute();

//            $this->logger->log("Fetch", "Info", "Generated SQL: " . $result);
            // Ensure it always returns an array
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            $this->logger->log("Fetch", "Error", "Error fetching products by category: " . htmlspecialchars($e->getMessage()));
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

//            $logMessage = "Product Details:\n" .
//                    "ID: " . $productData['ProductId'] . "\n" .
//                    "Name: " . $productData['ProductName'] . "\n" .
//                    "Description: " . $productData['ProductDesc'] . "\n" .
//                    "Category: " . $productData['Category'] . "\n" .
//                    "Price: " . $productData['Price'] . "\n" .
//                    "Weight: " . $productData['Weight'] . "\n" .
//                    "Image: " . $productData['ProductImage'] . "\n" .
//                    "Availability: " . $productData['Availability'];

            $this->logger->log("Update", "Success", "Product updated successfully with ID: " . htmlspecialchars($productData['ProductID']));
//            $this->logger->log("Product Detail", "Info", $logMessage);

            return true;
        } catch (Exception $e) {
//            $this->logger->log("Error updating product: " . $e->getMessage());
            $this->logger->log("Update Product", "Error", "Error update product: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Error updating product.");
        }
    }

    // DELETE PRODUCT
    public function deleteProductById($id) {
        try {
            $this->delete()->where('ProductId', $id)->execute();
//            $this->logger->log("Product deleted: " . htmlspecialchars($id));
            $this->logger->log("Delete", "Success", "Product deleted successfully with ID: " . htmlspecialchars($id));
            return true;
        } catch (Exception $e) {
//            $this->logger->log("Error deleting product: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Delete", "Error", "Error delete product: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Error deleting product.");
        }
    }

    //CHECK PROMOTION
    public function hasPromotion($productId) {
        try {
//            $this->logger->log("Checking promotions for ProductID: " . $productId);
            // Retrieve all promotions related to the product
            $this->table = 'PromotionProducts';
            $promotionProductsQuery = $this->findAll()
                    ->where('ProductID', $productId)
                    ->execute();

            // Log if no promotions found for the product
            if (empty($promotionProductsQuery)) {
//                $this->logger->log("No promotions found for ProductID: " . $productId);
                return null;
            }

            // store the active promotion
            $activePromotion = null;
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $currentDate = date('Y-m-d');
//            $this->logger->log("Current Date: " . $currentDate);
            //find the active one
            foreach ($promotionProductsQuery as $promotionProduct) {
                $promotionID = $promotionProduct['PromotionID'];
//                $this->logger->log("Checking PromotionID: " . $promotionID);
                // Check the Promotion table for the PromotionID and date validity
                $this->table = 'Promotion';
                $promotionQuery = $this->findAll()
                        ->where('PromotionID', $promotionID)
                        ->where('StartDate', $currentDate, NewModel::LESS_THAN_OR_EQUAL_TO)
                        ->where('EndDate', $currentDate, NewModel::GREATER_THAN_OR_EQUAL_TO)
                        ->execute();

                if (!empty($promotionQuery)) {
                    $activePromotion = $promotionQuery[0];
//                    $this->logger->log("Active promotion found: " . json_encode($activePromotion));
//                    $this->logger->log("Fetch", "Success", "Active promotion found: " . htmlspecialchars($activePromotion));
                    break; // Stop once the first active promotion is found
                } else {
                    $this->logger->log("Fetch", "Error", "No active promotion for PromotionID: " . $promotionID);
//                    $this->logger->log("No active promotion for PromotionID: " . $promotionID);
                }
            }

            return $activePromotion;
        } catch (Exception $e) {
//            $this->logger->log("Error checking promotion: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch Promotion", "Error", "Error get promotion: " . htmlspecialchars($e->getMessage()));
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
//            $this->logger->log("test", "test", "userid get: $customerId");
//            $customerId = getCustomerIDByUserID($userId);
            
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
//            $this->logger->log("Error adding product to cart: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Add to Product", "Error", "Error add to cart: " . htmlspecialchars($e->getMessage()));
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
//            $this->logger->log("Error generating CartID: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Generate Cart ID", "Error", "Error generate cart ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to generate CartID. Please try again later.");
        }
    }

    //WISHLIST
    public function addToWishlist($productId, $customerId, $quantity) {
        try {
            $this->table = 'Wishlist';
            $existingWishlist = $this->findAll()
                    ->where('CustomerID', $customerId)
                    ->execute();

            $this->logger->log("CustID", "INFO", "cust ID: $customerId");
            $existingWishlistId = null;

            if (!empty($existingWishlist)) {
                $existingWishlistId = $existingWishlist[0]['WishListID'];
            }

            // If no wishlist exists for the customer, create a new one
            if ($existingWishlistId === null) {
                $this->table = 'Wishlist';
                $existingWishlistId = $this->generateWishlistId();
                $wishlistData = [
                    'WishListID' => $existingWishlistId,
                    'CustomerID' => $customerId
                ];
                $this->insert($wishlistData)->execute();
            }

            // Check if the product is already in the wishlist
            $this->table = 'WishlistItem';
            $existingWishlistItem = $this->findAll()
                    ->where('WishlistID', $existingWishlistId)
                    ->where('ProductID', $productId)
                    ->execute();

            if (!empty($existingWishlistItem)) {
                // Update the quantity of the existing wishlist item
                $newQuantity = $existingWishlistItem[0]['Quantity'] + $quantity;
                $this->table = 'WishlistItem';
                $this->update('Quantity', $newQuantity)
                        ->where('WishlistID', $existingWishlistId)
                        ->where('ProductID', $productId)
                        ->execute();

                return [
                    'status' => 'success',
                    'message' => 'Product quantity updated in wishlist'
                ];
            }

            // Insert the product into the WishlistItem table
            $this->table = 'WishlistItem';
            $wishlistItemData = [
                'WishlistID' => $existingWishlistId,
                'ProductID' => $productId,
                'Quantity' => $quantity
            ];
            $this->insert($wishlistItemData)->execute();

            return [
                'status' => 'success',
                'message' => 'Product added to wishlist successfully'
            ];
        } catch (Exception $e) {
//            $this->logger->log("Error adding product to wishlist: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Add to Wishlist", "Error", "Error add product to wishlist: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to add product to wishlist. Please try again later.");
        }
    }

// GENERATE WishlistID
    private function generateWishlistId() {
        try {
            $this->table = 'Wishlist';
            $result = $this->findAll()
                    ->orderBy('WishListID', 'DESC')
                    ->limit(1)
                    ->execute();

            $maxId = $result[0]['WishListID'] ?? null;
            $number = ($maxId) ? (int) substr($maxId, 2) + 1 : 1;
            return 'W' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } catch (Exception $e) {
//            $this->logger->log("Error generating WishlistID: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Generate Wishlist ID", "Error", "Error generate wishlist ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to generate WishlistID. Please try again later.");
        }
    }

    //GET CUST ID 
    public function getCustomerIDByUserID($userID) {
        try {
            $this->table = 'customer';
            $result = $this->findAll()
                    ->where('UserID', $userID)
                    ->execute();

//            $this->logger->log("Query result for customerID: " . print_r($result, true));
            // Check if we got a result and return the customerID
            if (!empty($result) && isset($result[0]['CustomerID'])) {
                $customerID = $result[0]['CustomerID'];
//                $this->logger->log("Fetched customerID: " . $customerID);
                return $customerID;
            } else {
//                $this->logger->log("No customerID found for userID: " . $userID);
                return null;
            }
        } catch (Exception $e) {
//            $this->logger->log("Error fetching customerID: " . htmlspecialchars($e->getMessage()));
            $this->logger->log("Fetch Customer ID", "Error", "Error get customer ID: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch customer ID. Please try again later.");
        }
    }

    public function getMostOrderedProducts($limit = 10) {
        try {
            // Step 1: Fetch all OrderDetails records
            $this->table = 'OrderDetails';  // Switch to OrderDetails table
            // Use NewModel's findAll() method to fetch all records from OrderDetails
            $orderDetails = $this->findAll()->execute();

            if (empty($orderDetails)) {
                // If there are no orders, return all products as fallback
                $this->table = 'product';  // Switch back to product table
                return $this->findAll()->limit($limit)->execute();
            }

            // Step 2: Count how many times each ProductID appears in OrderDetails (manually group by in PHP)
            $productOrderCount = [];
            foreach ($orderDetails as $orderDetail) {
                $productId = $orderDetail['ProductID'];
                if (!isset($productOrderCount[$productId])) {
                    $productOrderCount[$productId] = 0;
                }
                $productOrderCount[$productId]++;
            }

            // Sort by the most ordered products (DESC)
            arsort($productOrderCount);

            // Limit to the top $limit products
            $mostOrderedProductIds = array_slice(array_keys($productOrderCount), 0, $limit);

            // Step 3: Fetch product details from the Product table using NewModel methods
            $this->table = 'product';  // Switch to product table
            $products = [];

            foreach ($mostOrderedProductIds as $productId) {
                // Fetch each product by its ProductID using where() method
                $product = $this->findAll()->where('ProductID', $productId)->execute();
                if (!empty($product)) {
                    $products[] = $product[0];  // Since findAll returns an array, get the first result
                }
            }

            // Step 4: Return the sorted products
            return $products;
        } catch (Exception $e) {
            $this->logger->log("Fetch", "Error", "Error fetching most ordered products: " . htmlspecialchars($e->getMessage()));
            throw new Exception("Unable to fetch most ordered products. Please try again later.");
        }
    }

}

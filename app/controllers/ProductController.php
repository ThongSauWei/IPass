<?php

require_once __DIR__ . '/../models/Product.php'; // Updated path using __DIR__
//require_once __DIR__ . '/../adapter/ProductInterface.php'; // Updated path using __DIR__
//require_once __DIR__ . '/../adapter/UnitConverterAdapter.php';
//require_once __DIR__ . '/../adapter/CurrencyConverterAdapter.php';

class ProductController {

    private $product;

    public function __construct() {
        $this->product = new Product([]);
    }

    // Handle GET and POST data
    public function handleRequests() {
        // Handle GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->handleGetRequests();
        }
        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequests();
        }
    }

    private function handleGetRequests() {
        // Initialize variables
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        $priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
        $priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';
        $weightMin = isset($_GET['weightMin']) ? $_GET['weightMin'] : '';
        $weightMax = isset($_GET['weightMax']) ? $_GET['weightMax'] : '';
        $availability = isset($_GET['availability']) ? $_GET['availability'] : '';

        // Determine which method to call based on user input
        if (!empty($searchTerm)) {
            $products = $this->getProductsBySearch($searchTerm);
        } elseif ($category || $priceMin || $priceMax || $weightMin || $weightMax || $availability !== '') {
            $products = $this->getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
        } else {
            $products = $this->getAllProducts();
        }

        // Pass products to view
        return $products;
    }

    private function handlePostRequests() {
        if (isset($_POST['addToCart'])) {
            // Handle adding product to cart
            $productId = $_POST['productId'];
            $customerId = $_POST['customerId'];  // You'd retrieve this based on logged-in user
            $quantity = $_POST['quantity'];

            // Call the addToCart function
            $this->addToCart($productId, $customerId, $quantity);
        } elseif (isset($_POST['action'])) {
            // Handle form submissions (like wishlist or other actions)
            $action = $_POST['action'];
            $productId = $_POST['productId'] ?? '';
            $customerId = $_POST['customerId'] ?? '';

            if ($action === 'addToWishList') {
                $this->addToWishlist($productId, $customerId);
            }
        }
    }

    // ADD PRODUCT(ADMIN)
    public function addProduct($productData) {
        try {
            $this->product = new Product($productData);
            $this->product->addProduct();
            return true; // Indicate success
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            return false; // Indicate failure
        }
    }

    // READ ALL PRODUCT
    public function getAllProducts() {
        try {
            $products = $this->product->getAll();
            return $products;
        } catch (Exception $e) {
            echo "<p>Error fetching products: " . $e->getMessage() . "</p>";
            return []; // Return an empty array if there's an error
        }
    }

    // READ SINGLE
    public function getProductById($id) {
        try {
            $productData = $this->product->getById($id);
            if ($productData) {
                return $productData; // Directly return the associative array
            } else {
                return null; // Return null if not found
            }
        } catch (Exception $e) {
            // Log and handle the exception
            return null;
        }
    }

    // Get products by search term
//    public function getProductsBySearch($searchTerm) {
//        return $this->product->getProductsBySearch($searchTerm);
//    }
//
//    public function getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability) {
//        return $this->product->filterProducts($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
//    }
    // GET CATEGORIES
    public function getCategories() {
        return $this->product->getCategories();
    }

    // GET CATEGORIES ARRAY
    public function getCategoriesArray() {
        return $this->product->getCategoriesArray();
    }

    // GET PRODUCT BY CAT
    public function getProductsByCategory($category) {
        return $this->product->getProductsByCategory($category);
    }

    // UPDATE PRODUCT
    public function updateProduct($productData) {
        try {
            $this->product = new Product($productData);
            // Assuming you have an updateProduct method in Product class
            $this->product->updateProduct($productData);
            return true;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            return $this->product->deleteProductById($id);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            return false;
        }
    }

    public function getPriceWithPromotion($productID) {
        // Fetch price and promotion information from the model
        return $this->product->getPriceWithPromotion($productID);
    }

    public function addToCart($productId, $customerId, $quantity) {
        return $this->product->addToCart($productId, $customerId, $quantity);
    }

    // Method to handle form submissions
    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $productId = $_POST['productID'] ?? '';
            $quantity = $_POST['quantity'] ?? 1; 
            $customerId = 'C0001'; 

            if ($action === 'addToCart') {
                return $this->addToCart($productId, $customerId, $quantity);
            } elseif ($action === 'addToWishList') {
                return $this->addToWishlist($productId, $customerId, $quantity);
            }
        }
        return false;
    }

    // Method to get product details and handle GET requests
    public function getProductDetails($productID) {
        if (isset($productID) && !empty($productID)) {
            $product = $this->getProductById($productID);

            if ($product && is_array($product) && isset($product[0])) {
                return $product[0];  // Return product array
            } else {
                return ['error' => "Product not found."];
            }
        } else {
            return ['error' => "No product ID provided."];
        }
    }

    // Method to get related products and additional products to ensure at least 4 products
    public function getRelatedProducts($productID, $category) {
        $relatedProducts = [];
        $additionalProducts = [];
        $displayProducts = [];

        if (!empty($category)) {
            // Fetch related products by category, excluding the current product
            $relatedProducts = $this->getProductsByCategory($category);
            $relatedProducts = array_filter($relatedProducts, function ($p) use ($productID) {
                return $p['ProductID'] !== $productID;
            });

            // If there are fewer than 4 matching products, fetch additional products
            if (count($relatedProducts) < 4) {
                // Fetch all products excluding the current product
                $allProducts = $this->getAllProducts();
                $allProducts = array_filter($allProducts, function ($p) use ($productID) {
                    return $p['ProductID'] !== $productID;
                });

                // Add additional products to ensure at least 4 products are displayed
                $additionalProducts = array_slice($allProducts, 0, 4 - count($relatedProducts));
            }

            // Merge related products with additional products to display at least 4 products
            $displayProducts = array_merge($relatedProducts, $additionalProducts);
        } else {
            return ['error' => "Product category is not defined."];
        }

        return $displayProducts;
    }

    // ADD TO WISHLIST
    public function addToWishlist($productId, $customerId, $quantity) {
        return $this->product->addToWishlist($productId, $customerId, $quantity);
    }

    // Unit conversion (Kg to Gram)
//    public function convertKgToGram($kg) {
//        $converter = new UnitConverterAdapter();
//        return $converter->convert($kg);
//    }
//
//    // Currency conversion (MYR to another currency)
//    public function convertCurrency($myr, $exchangeRate) {
//        $converter = new CurrencyConverterAdapter($exchangeRate);
//        return $converter->convert($myr);
//    }
}

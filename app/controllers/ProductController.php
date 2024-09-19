<?php

require_once __DIR__ . '/../models/Product.php'; // Updated path using __DIR__
//require_once __DIR__ . '/../adapter/ProductInterface.php'; // Updated path using __DIR__
//require_once __DIR__ . '/../adapter/UnitConverterAdapter.php';
//require_once __DIR__ . '/../adapter/CurrencyConverterAdapter.php';
require_once __DIR__ . '/../models/ProductLogger.php';
require_once __DIR__ . '/../core/SessionManager.php';

class ProductController {

    private $product;
    private $logger;
    private $session;

    public function __construct() {
        $this->product = new Product([]);
        $this->logger = new ProductLogger();
        $this->session = new SessionManager();
    }

    // Handle GET and POST data
    public function handleRequests() {
        // Handle GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get category from URL and pass it to handleGetRequests
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $products = $this->handleGetRequests($category);
            return $products; // Return products based on category
        }
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        // Fetch products based on search term
        if ($searchTerm !== '') {
            return $this->getProductsBySearch($searchTerm);
        }

        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//            $this->handlePostRequests();
        }

        if (isset($_GET['action']) && $_GET['action'] === 'updateProduct') {
            $this->updateProduct($_POST);
        }

        if (isset($_GET['action']) && $_GET['action'] === 'addProduct') {
            $this->addProduct($_POST);
        }

        if (isset($_GET['action']) && $_GET['action'] === 'deleteProduct') {
            $this->deleteProduct($_POST);
        }
    }

//    private function handleGetRequests() {
//        // Initialize variables
//        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
//        $category = isset($_GET['category']) ? $_GET['category'] : '';
//        $priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
//        $priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';
//        $weightMin = isset($_GET['weightMin']) ? $_GET['weightMin'] : '';
//        $weightMax = isset($_GET['weightMax']) ? $_GET['weightMax'] : '';
//        $availability = isset($_GET['availability']) ? $_GET['availability'] : '';
//
//        // Determine which method to call based on user input
//        if (!empty($searchTerm)) {
//            $products = $this->getProductsBySearch($searchTerm);
//        } elseif ($category || $priceMin || $priceMax || $weightMin || $weightMax || $availability !== '') {
//            $products = $this->getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
//        } else {
//            $products = $this->getAllProducts();
//        }
//
//        // Pass products to view
//        return $products;
//    }
    private function handleGetRequests($category = '') {
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
        $priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';
        $weightMin = isset($_GET['weightMin']) ? $_GET['weightMin'] : '';
        $weightMax = isset($_GET['weightMax']) ? $_GET['weightMax'] : '';
        $availability = isset($_GET['availability']) ? $_GET['availability'] : '';

        if ($category) {
            $products = $this->getProductsByCategory($category);
        } elseif (!empty($searchTerm)) {
            $products = $this->getProductsBySearch($searchTerm);
        } elseif ($priceMin || $priceMax || $weightMin || $weightMax || $availability !== '') {
            $products = $this->getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
        } else {
            $products = $this->getAllProducts();
        }

        return $products;
    }

//    public function handlePostRequests() {
//        if (isset($_POST['addToCart'])) {
//            // Retrieve the form data
//            $productId = $_POST['productID'];
//            $customerId = $_POST['customerID'];  // You'd retrieve this based on the logged-in user
//            $quantity = $_POST['quantity'];
//            $userId = $_POST['userid'];  // Get the user ID from the form data
//            // Log the captured userID for debugging
////            $this->logger->log("Received userID: $userId");
//            // Check if user is logged in (userid is not 0 or null)
//            if ($userId == 0 || empty($userId)) {
//                // If no user is logged in, redirect to login
////                $this->logger->log("No user logged in. Redirecting to login page.");
//                SessionManager::requireLogin();
//            } else {
//                // If user is logged in, proceed to add the product to cart
////                $this->logger->log("User is logged in. userID: $userId");
//                $this->addToCart($productId, $customerId, $quantity);
//            }
//        } elseif (isset($_POST['action'])) {
//            // Handle form submissions (like wishlist or other actions)
//            $action = $_POST['action'];
//            $productId = $_POST['productI'] ?? '';
//            $customerId = $_POST['customerId'] ?? '';
//            $userId = $_POST['userid'];
//            $quantity = $_POST['quantity'];
//
//            if ($action === 'addToWishList') {
//
//                if ($userId == 0 || empty($userId)) {
//                    SessionManager::requireLogin();
//                } else {
//                    $this->addToWishlist($productId, $customerId, $quantity);
//                }
//            }
//        }
//    }

    // ADD PRODUCT(ADMIN)
    public function addProduct($productData) {
        try {
//            $this->product = new Product($productData);
//            $this->product->addProduct();
//            return true; // Indicate success

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Handle file upload
                $targetDir = __DIR__ . "/../../public/assets/img/ProductImage/"; // Directory where files will be uploaded
                // Check if the uploads directory exists and create it if not
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create directory with appropriate permissions
                }

                // Get the original file extension
                $imageFileType = strtolower(pathinfo($_FILES["ProductImage"]["name"], PATHINFO_EXTENSION));

                // Sanitize the ProductName to create a valid file name
                $sanitizedProductName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $_POST['ProductName']));

                // Create the new file name using the sanitized ProductName and file extension
                $newFileName = $sanitizedProductName . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;

                $uploadOk = 1;

                // Check if the file is an image
                $check = getimagesize($_FILES["ProductImage"]["tmp_name"]);
                if ($check === false) {
                    $uploadOk = 0;
                    $errorMessage = "File is not an image.";
                }

                // Check file size (e.g., limit to 5MB)
                if ($_FILES["ProductImage"]["size"] > 5000000) {
                    $uploadOk = 0;
                    $errorMessage = "Sorry, your file is too large.";
                }

                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $uploadOk = 0;
                    $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    // Redirect with error message
                    header('Location: productList.php?action=add&status=file_error&message=' . urlencode($errorMessage));
                    exit();
                } else {
                    if (move_uploaded_file($_FILES["ProductImage"]["tmp_name"], $targetFile)) {
                        // Set image path to the new file name
                        $imagePath = $newFileName;

                        // Create product data array
                        $productData = [
                            'ProductName' => $_POST['ProductName'],
                            'ProductDesc' => $_POST['ProductDesc'],
                            'Category' => $_POST['Category'],
                            'Price' => $_POST['Price'],
                            'Weight' => $_POST['Weight'],
                            'ProductImage' => $imagePath,
                            'Availability' => $_POST['Availability']
                        ];

                        // Handle custom category
                        if (isset($_POST['customCategory']) && !empty($_POST['customCategory'])) {
                            $productData['Category'] = $_POST['customCategory'];
                        }

                        // Use ProductController to add product
                        $productController = new ProductController();
//                        $addResult = $productController->addProduct($productData);
                        $this->product = new Product($productData);
                        $addResult = $this->product->addProduct();

                        if ($addResult) {
                            // Redirect with success message
                            header('Location: ../views/Admin/Product/displayProduct.php?action=add&status=success');
                        } else {
                            // Redirect with failure message
                            header('Location: ../views/Admin/Product/displayProduct.php?action=add&status=fail');
                        }
                        exit();
                    } else {
                        // Redirect with file upload error
                        header('Location: ../views/Admin/Product/displayProduct.php?action=add&status=file_error');
                        exit();
                    }
                }
            }
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
    public function getProductsBySearch($searchTerm) {
        return $this->product->getProductsBySearch($searchTerm);
    }

//
    public function getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability) {
        return $this->product->filterProducts($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
    }

    // GET CATEGORIES
    public function getCategories() {
        return $this->product->getCategories();
    }

    // GET CATEGORIES ARRAY
    public function getCategoriesArray() {
        return $this->product->getCategoriesArray();
    }

    // GET PRODUCT BY CAT
    // GET PRODUCT BY CAT
    public function getProductsByCategory($category) {
        $products = $this->product->getProductsByCategory($category);
        // Ensure that it always returns an array
        return is_array($products) ? $products : [];
    }

    // UPDATE PRODUCT
//    public function updateProduct($productData) {
//        try {
//            $this->product = new Product($productData);
//            // Assuming you have an updateProduct method in Product class
//            $this->product->updateProduct($productData);
//            return true;
//        } catch (Exception $e) {
//            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
//            return false;
//        }
//    }
    // Function to handle product update
    // Handle product update
    public function updateProduct($productData) {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get product data from the form
                $productData = [
                    'ProductID' => $_POST['productID'] ?? null,
                    'ProductName' => $_POST['productName'] ?? null,
                    'ProductDesc' => $_POST['productDesc'] ?? null, // Added Product Description
                    'Price' => $_POST['price'] ?? null,
                    'Category' => $_POST['category'] ?? null,
                    'Weight' => $_POST['weight'] ?? null,
                    'CustomCategory' => $_POST['customCategory'] ?? null,
                    'Availability' => $_POST['availability'] ?? null,
                ];

                // Handle file upload
                $targetDir = __DIR__ . "/../../public/assets/img/ProductImage/"; // Directory where files will be uploaded
                // Check if the uploads directory exists and create it if not
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create directory with appropriate permissions
                }

                // Check if a new image is uploaded
                if (isset($_FILES['ProductImage']) && $_FILES['ProductImage']['error'] === UPLOAD_ERR_OK) {
                    // Get the original file extension
                    $imageFileType = strtolower(pathinfo($_FILES["ProductImage"]["name"], PATHINFO_EXTENSION));

                    // Sanitize the ProductName to create a valid file name
                    $sanitizedProductName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $_POST['productName']));

                    // Create the new file name using the sanitized ProductName and file extension
                    $newFileName = $sanitizedProductName . '.' . $imageFileType;
                    $targetFile = $targetDir . $newFileName;
                    $uploadOk = 1;

                    // Check if the file is an image
                    $check = getimagesize($_FILES["ProductImage"]["tmp_name"]);
                    if ($check === false) {
                        $uploadOk = 0;
                        $errorMessage = "File is not an image.";
                    }

                    // Check file size (e.g., limit to 5MB)
                    if ($_FILES["ProductImage"]["size"] > 5000000) {
                        $uploadOk = 0;
                        $errorMessage = "Sorry, your file is too large.";
                    }

                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $uploadOk = 0;
                        $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    }

                    // If $uploadOk is set to 0 by an error, handle the error
                    if ($uploadOk == 0) {
                        header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=file_error&message=' . urlencode($errorMessage));
                        exit();
                    } else {
                        // If there's an existing image, delete it
                        if (!empty($_POST['existingProductImage'])) {
                            $existingImagePath = __DIR__ . "/../../public/assets/img/ProductImage/" . $_POST['existingProductImage'];

//                            $this->logger->log("old image- $existingImagePath");
                            if (file_exists($existingImagePath)) {
                                unlink($existingImagePath); // Delete the old image
                            }
                        }

                        // Move uploaded file to target directory
                        if (move_uploaded_file($_FILES["ProductImage"]["tmp_name"], $targetFile)) {
                            $imagePath = $newFileName;
                            // Set the new image path in the product data
                            $productData['ProductImage'] = $imagePath;
                        } else {
                            // Handle upload error
                            header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=file_error');
                            exit();
                        }
                    }
                } else {
                    // If no new image is uploaded, use the existing image path
                    $productData['ProductImage'] = $_POST['existingProductImage'];
                }

                // Check if productID is set
                if ($productData['ProductID'] === null) {
                    // Redirect with an error message
                    //header('Location: productList.php?error=missing_product_id');
                    $errorMessage = "No Product ID";
                    header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=file_error&message=' . urlencode($errorMessage));
                    exit();
                }

                // Determine the final category value
                if (!empty($productData['CustomCategory'])) {
                    // Use custom category if provided
                    $finalCategory = $productData['CustomCategory'];
                } else if (!empty($productData['Category'])) {
                    // Use selected category if no custom category is provided
                    $finalCategory = $productData['Category'];
                } else {
                    // Handle case when neither is provided (optional)
                    header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=missing_category');
                    exit();
                }

                // Update product data with the final category value
                $productData['Category'] = $finalCategory;

                // Call the updateProduct method in ProductController
                try {
                    $updateResult = $this->product->updateProduct($productData);
                    if ($updateResult) {
                        header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=success');
                        exit();
                    } else {
                        header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=fail');
                        exit();
                    }
                } catch (Exception $e) {
                    // Handle any exceptions
                    header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=exception');
                    exit();
                }
            } else {
                // Redirect if the request method is not POST
                header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=invalid_request');
                exit();
            }
        } catch (Exception $e) {
            header('Location: ../views/Admin/Product/displayProduct.php?action=update&status=exception');
            exit();
        }
    }

    public function deleteProduct() {
        try {
//            return $this->product->deleteProductById($id);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $productID = $_POST['productID'];

                $productController = new ProductController();

                // Fetch product data to get the image path
                $productData = $productController->getProductByID($productID);

                // Log the entire product data
//                logMessage("Product data: " . print_r($productData, true));

                if (!empty($productData) && isset($productData[0])) {
                    // Access the first item in the array if it's a nested array
                    $product = $productData[0];
                    $productImagePath = $product['ProductImage']; // Get the current image path
                    // Log the image path and product ID
//                    logMessage("Product ID: $productID");
//                    logMessage("Image path: $productImagePath");
                    // Perform the deletion
//                    $deleted = $productController->deleteProduct($productID);
                    $deleted = $this->product->deleteProductById($productID);

                    if ($deleted) {
                        // If the product was successfully deleted, delete the product image
                        if (!empty($productImagePath)) {
                            $fullImagePath = __DIR__ . "/../../public/assets/img/ProductImage/" . $productImagePath; // Construct full path to image
                            // Log the full path to the image
//                            logMessage("Attempting to delete image at: $fullImagePath");

                            if (file_exists($fullImagePath)) {
                                if (unlink($fullImagePath)) {
//                                    logMessage("Image successfully deleted.");
                                } else {
//                                    logMessage("Failed to delete image.");
                                }
                            } else {
//                                logMessage("Image file does not exist: $fullImagePath");
                            }
                        } else {
//                            logMessage("No image path provided for deletion.");
                        }

                        // Redirect back to product list after deletion
                        header('Location: ../views/Admin/Product/displayProduct.php?action=delete&status=success');
                        exit;
                    } else {
//                        logMessage("Failed to delete product from database.");
                        header('Location: ../views/Admin/Product/displayProduct.php?action=delete&status=fail');
                        exit;
                    }
                } else {
//                    logMessage("Product not found or invalid data: $productID");
                    header('Location: ../views/Admin/Product/displayProduct.php?action=delete&status=not_found');
                    exit;
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            return false;
        }
    }

    public function getPriceWithPromotion($productID) {
        // Fetch price and promotion information from the model
        return $this->product->getPriceWithPromotion($productID);
    }

    public function addToCart($productId, $custId, $quantity) {
        return $this->product->addToCart($productId, $custId, $quantity);
    }

    // Method to handle form submissions
    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $productId = $_POST['productID'] ?? '';
            $quantity = $_POST['quantity'] ?? 1;
//            $customerId = 'C0001';
            $userId = $_POST['userid'];  // Get the user ID from the form data
            $custId = $_POST['custid'];
            // Log the captured userID for debugging (ensure logging does not output directly)
            $this->logger->log("test", "test", "userid get: $userId");
            // Ensure no output is sent to the browser before checking login
            if ($action === 'addToCart') {
                if (empty($userId) || $userId == 0) {
                    // If no user is logged in, redirect to login
//                    $this->logger->log("No user logged in. Redirecting to login page.");
                    // Ensure redirection occurs
                    $this->session->requireLogin();
                    return; // Stop further processing
                } else {
                    // If user is logged in, proceed to add the product to cart
//                    $this->logger->log("User is logged in. userID: $userId");
                    return $this->addToCart($productId, $custId, $quantity);
                }
            } elseif ($action === 'addToWishList') {
                return $this->addToWishlist($productId, $custId, $quantity);
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
    public function addToWishlist($productId, $custId, $quantity) {
        return $this->product->addToWishlist($productId, $custId, $quantity);
    }

    public function hasPromotion($productId) {
        return $this->product->hasPromotion($productId);
    }

    public function getCustomerIDByUserID($userID) {
        return $this->product->getCustomerIDByUserID($userID);
    }

    public function getTransactionLogs() {
        $logger = new ProductLogger();
        return $logger->getLogs();
    }

    public function getMostWantedProducts() {
        return $this->product->getMostOrderedProducts(); // Retrieve the most ordered products
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

// Initialize the controller
$productController = new ProductController();
$productController->handleRequests();

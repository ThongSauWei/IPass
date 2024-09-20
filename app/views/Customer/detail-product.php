<?php
ob_start();

include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../controllers/ProductController.php';
require_once __DIR__ . '/../../core/SessionManager.php';
require_once __DIR__ . '/../../adapter/WeightAdapterInterface.php';
require_once __DIR__ . '/../../adapter/UnitConverterAdapter.php';
require_once __DIR__ . '/../../adapter/CurrencyConverterAdapter.php';

$weightConverter = new UnitConverterAdapter();
$currencyConverter = new CurrencyConverterAdapter();

$productController = new ProductController();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = null;
$relatedProducts = [];
$additionalProducts = [];
$displayProducts = [];
$product = null;

// Handle form submission for adding to cart or wishlist
$result = $productController->handleFormSubmission();

// Now, get the user info after handling submission
$user = SessionManager::getUser();

// Set $userID conditionally based on whether the user is logged in
$userID = $user ? $user['UserID'] : 0;
if ($userID) {
    $customerID = $productController->getCustomerIDByUserID($userID);
}

$selectedCurrency = 'MYR'; // Default currency
if (isset($_GET['currency'])) {
    $selectedCurrency = $_GET['currency'];
}

//echo "User ID: " . htmlspecialchars($userID);
// Determine the current language (default to 'en' for English)
$currentLanguage = isset($_GET['lang']) ? $_GET['lang'] : 'en';
$translateTo = $currentLanguage === 'zh' ? 'en' : 'zh';
$translateText = $currentLanguage === 'zh' ? 'Translate to English' : 'Translate to Chinese';

if (isset($_GET['productID'])) {
    $productID = $_GET['productID'];

    // Get product details
    $productData = $productController->getProductDetails($productID);

    if (isset($productData['error'])) {
        $error = $productData['error'];
    } else {
        $product = $productData;

        $category = $product['Category'] ?? '';
        if (!empty($category)) {
            // Fetch related products
            $displayProducts = $productController->getRelatedProducts($productID, $category);
        } else {
            $error = "Product category is not defined.";
        }

        // Convert prices
        $priceData = $productController->getPriceWithPromotion($productID);
        $originalPrice = $priceData['originalPrice'];
        $discountedPrice = $priceData['discountedPrice'];

        $convertedOriginalPrice = $productController->convertCurrency($originalPrice, 'MYR', $selectedCurrency);
        $convertedDiscountedPrice = $productController->convertCurrency($discountedPrice ?? $originalPrice, 'MYR', $selectedCurrency);

        $currencySymbols = [
            'MYR' => 'RM ',
            'USD' => 'USD ',
            'SGD' => 'SGD ',
            'CNY' => 'CNY ',
            'EUR' => 'EUR ',
            'GBP' => 'GBP '
        ];

        $currencySymbol = $currencySymbols[$selectedCurrency] ?? 'RM';
    }
} else {
    $error = "No product ID provided.";
}

ob_end_flush();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
    .btn-primary {
        background: #E91E63;
        border-color: #E91E63;
    }
    .btn-primary:hover, .btn-primary:focus {
        background: #E91E63;
        border-color: #E91E63;
    }

    .card-title {
        color: #333333;
        transition: color 0.3s, text-decoration 0.3s;
    }

    .card-title:hover,
    .card-title:focus {
        color: #E91E63;
        text-decoration: none;
    }
</style>

<div id="page-content" class="page-content">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <?php if (isset($product)): ?>
            <div class="banner">
                <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('../../../public/assets/img/bg-header.jpg');">
                    <div class="container">
                        <h1 class="pt-5">
                            <?php echo htmlspecialchars($product['ProductName']); ?>
                        </h1>
                        <p class="lead">
                            Save time and leave the groceries to us.
                        </p>
                    </div>
                </div>
            </div>

            <!-- success message -->
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $result): ?>
                <?php if ($result['status'] === 'success'): ?>
                    <?php if ($_POST['action'] === 'addToCart'): ?>
                        <div class="alert alert-success" role="alert">
                            Product added to cart successfully!
                        </div>
                    <?php elseif ($_POST['action'] === 'addToWishList'): ?>
                        <div class="alert alert-success" role="alert">
                            Product added to wishlist successfully!
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($result['message']); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="product-detail">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="slider-zoom">
                                <a href="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" class="cloud-zoom" 
                                   rel="transparentImage: 'data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', 
                                   useWrapper: false, showTitle: false, 
                                   zoomWidth: '500', zoomHeight: '500', 
                                   adjustY: 0, adjustX: 10" 
                                   id="cloudZoom">
                                    <img alt="Detail Zoom thumbs image" src="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" style="width: 100%;">
                                </a>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <strong>Overview</strong><br>
                            <p style="text-align: justify;">
                                <span id="product-desc"><?php echo htmlspecialchars($product['ProductDesc']); ?></span>
                                <a href="#" id="translate-link" data-text="<?php echo htmlspecialchars($product['ProductDesc']); ?>" data-lang="<?php echo htmlspecialchars($translateTo); ?>">
                                    <?php echo htmlspecialchars($translateText); ?>
                                </a>
                            </p>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Price Section -->
                                    <p>
                                        <strong>Price</strong>
                                        <br>
                                        <span id="price">
                                            <?php if ($discountedPrice !== null): ?>
                                                <span id="discounted-price" class="price">
                                                    <?php echo htmlspecialchars($currencySymbol) . number_format($convertedDiscountedPrice, 2); ?>
                                                </span>
                                                <span id="original-price" class="old-price" style="text-decoration: line-through;">
                                                    <?php echo htmlspecialchars($currencySymbol) . number_format($convertedOriginalPrice, 2); ?>
                                                </span>
                                            <?php else: ?>
                                                <span id="original-price" class="price">
                                                    <?php echo htmlspecialchars($currencySymbol) . number_format($convertedOriginalPrice, 2); ?>
                                                </span>
                                            <?php endif; ?>
                                        </span>
                                    </p>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <select id="currency-select" class="form-select d-inline-block" style="width: auto; display: inline;">
                                        <option value="MYR" <?php echo $selectedCurrency === 'MYR' ? 'selected' : ''; ?>>MYR</option>
                                        <option value="USD" <?php echo $selectedCurrency === 'USD' ? 'selected' : ''; ?>>USD</option>
                                        <option value="SGD" <?php echo $selectedCurrency === 'SGD' ? 'selected' : ''; ?>>SGD</option>
                                        <option value="CNY" <?php echo $selectedCurrency === 'USD' ? 'selected' : ''; ?>>CNY</option>
                                        <option value="GBP" <?php echo $selectedCurrency === 'SGD' ? 'selected' : ''; ?>>GBP</option>
                                        <!-- Add more currency options as needed -->
                                    </select>
                                </div>
                            </div>

                            <?php
                            // Get product weight from database
                            $productWeightKg = (float) $product['Weight']; // Weight in kg
// Convert weight to grams
                            $productWeightG = $productController->convertWeight($productWeightKg);
                            ?>

                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Weight Section -->
                                    <p>
                                        <strong>Weight</strong>
                                        <br>
                                        <span id="product-weight" class="price">
                                            <?php echo htmlspecialchars($productWeightKg); ?> kg
                                        </span>
                                    </p>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <select id="weight-unit" class="form-select d-inline-block" style="width: auto; display: inline;">
                                        <option value="KG" selected>KG</option>
                                        <option value="G">G</option>
                                    </select>
                                </div>
                            </div>

                            <p>
                                <strong>Category</strong><br>
                                <?php echo htmlspecialchars($product['Category']); ?>
                            </p>

                            <!--add to cart or wishlish (quantity)-->
                            <form action="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" method="POST">
                                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product['ProductID']); ?>">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userID); ?>">
                                <input type="hidden" name="custid" value="<?php echo htmlspecialchars($customerID); ?>">

                                <p class="mb-1">
                                    <strong>Quantity</strong>
                                </p>
                                <div class="row">
                                    <div class="col-sm-5">
                                        <input class="vertical-spin" type="text" 
                                               data-bts-button-down-class="btn btn-primary" 
                                               data-bts-button-up-class="btn btn-primary" 
                                               value="1" 
                                               name="quantity">
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="pt-1 d-inline-block">Pack (<?php echo htmlspecialchars($product['Weight']); ?> kg)</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col d-flex justify-content-between">
                                        <button type="submit" name="action" value="addToCart" class="mt-3 btn-primary btn-lg">
                                            <i class="fa fa-shopping-basket"></i> Add to Cart
                                        </button>

                                        <button type="submit" name="action" value="addToWishList" class="mt-3 btn-primary btn-lg">
                                            <i class="fa fa-heart"></i> Add to WishList
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <section id="related-product">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="title">Related Products</h2>
                            <div class="product-carousel owl-carousel">
                                <?php if (!empty($displayProducts) && is_array($displayProducts)): ?>
                                    <?php foreach ($displayProducts as $relatedProduct): ?>
                                        <?php
                                        // Fetch price and promotion data
                                        $priceData = $productController->getPriceWithPromotion($relatedProduct['ProductID']);
                                        $promotion = $productController->hasPromotion($relatedProduct['ProductID']);
                                        ?>
                                        <div class="item">
                                            <a href="detail-product.php?productID=<?php echo htmlspecialchars($relatedProduct['ProductID']); ?>" class="card-link">
                                                <div class="card card-product">
                                                    <div class="card-ribbon">
                                                        <div class="card-ribbon-container right">
                                                            <?php if ($promotion) { ?>
                                                                <span class="ribbon ribbon-primary">Promotion</span>
                                                            <?php } ?>

                                                        </div>
                                                    </div>
                                                    <div class="card-badge">
                                                        <div class="card-badge-container left">
                                                            <?php if ($promotion) { ?>
                                                                <?php
                                                                $promotionEndDate = new DateTime($promotion['EndDate']);
                                                                ?>
                                                                <span class="badge badge-default" style="color:black;">
                                                                    Until <?php echo $promotionEndDate->format('m/d'); ?>
                                                                </span>
                                                                <span class="badge badge-primary">
                                                                    <?php
                                                                    if ($promotion['DiscountType'] === 'Percentage') {
                                                                        echo $promotion['DiscountValue'] . '% OFF';
                                                                    } elseif ($promotion['DiscountType'] === 'Fixed Amount') {
                                                                        echo '- RM ' . number_format($promotion['DiscountValue'], 2);
                                                                    }
                                                                    ?>
                                                                </span>
                                                            <?php } ?>

                                                        </div>
                                                        <img src="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($relatedProduct['ProductImage']); ?>" alt="Card image" class="card-img-top">
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="card-title"><?php echo htmlspecialchars($relatedProduct['ProductName']); ?></h4>
                                                        <div class="card-price">
                                                            <?php if ($priceData['discountedPrice'] !== null): ?>
                                                                <span class="discount" style="text-decoration: line-through;">
                                                                    RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                                                </span>
                                                                <span class="reguler">
                                                                    RM <?php echo number_format($priceData['discountedPrice'], 2); ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="reguler">
                                                                    RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <a href="detail-product.php?productID=<?php echo htmlspecialchars($relatedProduct['ProductID']); ?>" class="btn btn-block btn-primary">
                                                            Add to Cart
                                                        </a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No related products found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script>
    //weight
    document.getElementById('weight-unit').addEventListener('change', function () {
        var unit = this.value;
        var weightElement = document.getElementById('product-weight');
        // Get the weight values from the PHP code
        var weightKg = <?php echo json_encode($productWeightKg); ?>;
        var weightG = <?php echo json_encode($productWeightG); ?>;
        // Update the displayed weight based on the selected unit
        if (unit === 'KG') {
            weightElement.textContent = weightKg + ' kg';
        } else if (unit === 'G') {
            weightElement.textContent = weightG + ' g';
        }
    });
    //currency
    document.getElementById('currency-select').addEventListener('change', function () {
        var selectedCurrency = this.value;
        // Reload the page with the selected currency
        window.location.href = 'detail-product.php?productID=<?php echo htmlspecialchars($productID); ?>&currency=' + selectedCurrency;
    });

    // Translate
    document.getElementById('translate-link').addEventListener('click', function (e) {
        e.preventDefault();
        var text = this.getAttribute('data-text');
        var targetLanguage = this.getAttribute('data-lang');
        
        alert("test");

        fetch('../../controllers/ProductController.php?action=translate&lang=' + targetLanguage, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({text: text})
        })
                .then(response => response.json())
                .then(data => {
                    if (data.translatedText) {
                        alert("testin");
                        document.getElementById('product-desc').textContent = data.translatedText;
                        // Update translation link
                        var translateLink = document.getElementById('translate-link');
                        var newLang = targetLanguage === 'zh' ? 'en' : 'zh';
                        var newText = targetLanguage === 'zh' ? 'Translate to English' : 'Translate to Chinese';
                        translateLink.textContent = newText;
                        translateLink.setAttribute('data-lang', newLang);
                        translateLink.setAttribute('data-text', data.translatedText);
                    } else {
                        alert("test1");
                        alert('Error: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    alert("test2");
                    alert('Error: ' + error.message);
                });
    });



</script>

<?php
include_once __DIR__ . '/footer.php';
?>

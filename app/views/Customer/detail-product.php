<?php
include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

$productController = new ProductController();

$error = null;
$relatedProducts = [];
$additionalProducts = [];
$displayProducts = [];
$product = null;

// Handle form submission for adding to cart or wishlist
$result = $productController->handleFormSubmission();

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
    }
} else {
    $error = "No product ID provided.";
}
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
                                <a href="../../<?php echo htmlspecialchars($product['ProductImage']); ?>" class="cloud-zoom" 
                                   rel="transparentImage: 'data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', 
                                   useWrapper: false, showTitle: false, 
                                   zoomWidth: '500', zoomHeight: '500', 
                                   adjustY: 0, adjustX: 10" 
                                   id="cloudZoom">
                                    <img alt="Detail Zoom thumbs image" src="../../<?php echo htmlspecialchars($product['ProductImage']); ?>" style="width: 100%;">
                                </a>
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <p style="text-align: justify;">
                                <strong>Overview</strong><br>
                                <?php echo htmlspecialchars($product['ProductDesc']); ?>
                            </p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Price Section -->
                                    <p>
                                        <strong>Price</strong>
                                        <br>
                                        <?php
                                        // Fetch the price and promotion data
                                        $priceData = $productController->getPriceWithPromotion($productID);

                                        // Check if there's a promotional price
                                        if ($priceData['discountedPrice'] !== null) {
                                            // Display discounted price and original price
                                            ?>
                                            <span class="price">
                                                RM <?php echo number_format($priceData['discountedPrice'], 2); ?>
                                            </span>
                                            <span class="old-price" style="text-decoration: line-through;">
                                                RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                            </span>
                                            <?php
                                        } else {
                                            // No promotion, display original price only
                                            ?>
                                            <span class="price">
                                                RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                            </span>
                                            <?php
                                        }
                                        ?>
                                    </p>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <select class="form-select d-inline-block" style="width: auto; display: inline;">
                                        <option value="MYR" selected>MYR</option>
                                        <option value="SGD">SGD</option>
                                        <option value="USD">USD</option>
                                        <!-- Add more currency options as needed -->
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Weight Section -->
                                    <p>
                                        <strong>Weight</strong>
                                        <br>
                                        <span class="price">
                                            <?php echo htmlspecialchars($product['Weight']); ?> kg
                                        </span>
                                    </p>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <select class="form-select d-inline-block" style="width: auto; display: inline;">
                                        <option value="KG" selected>KG</option>
                                        <option value="G">G</option>
                                        <!-- Add more weight units as needed -->
                                    </select>

                                </div>
                            </div>
                            <p>
                                <strong>Category</strong><br>
                                <?php echo htmlspecialchars($product['Category']); ?>
                            </p>

                            <!--add to cart or wishlish (quantity)-->
                            <form action="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" method="POST">
                                <!-- ProductID Hidden Fields -->
                                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product['ProductID']); ?>">
                                <input type="hidden" name="quantity" value="1"> <!-- Default quantity for wishlist -->
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
                                        <div class="item">
                                            <a href="detail-product.php?productID=<?php echo htmlspecialchars($relatedProduct['ProductID']); ?>" class="card-link">
                                                <div class="card card-product">
                                                    <div class="card-ribbon">
                                                        <div class="card-ribbon-container right">
                                                            <span class="ribbon ribbon-primary">SPECIAL</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-badge">
                                                        <div class="card-badge-container left">
                                                            <span class="badge badge-default">Until 2018</span>
                                                            <span class="badge badge-primary">20% OFF</span>
                                                        </div>
                                                        <img src="../../<?php echo htmlspecialchars($relatedProduct['ProductImage']); ?>" alt="Card image" class="card-img-top">
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="card-title"><?php echo htmlspecialchars($relatedProduct['ProductName']); ?></h4>
                                                        <div class="card-price">
                                                            <?php
                                                            // Fetch the price and promotion data
                                                            $priceData = $productController->getPriceWithPromotion($relatedProduct['ProductID']);

                                                            // Check if there's a promotional price
                                                            if ($priceData['discountedPrice'] !== null) {
                                                                // Display original price (strikethrough) and discounted price
                                                                ?>
                                                                <span class="discount" style="text-decoration: line-through;">
                                                                    RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                                                </span>
                                                                <span class="reguler">
                                                                    RM <?php echo number_format($priceData['discountedPrice'], 2); ?>
                                                                </span>

                                                                <?php
                                                            } else {
                                                                // No promotion, display original price only
                                                                ?>
                                                                <span class="reguler">
                                                                    RM <?php echo number_format($priceData['originalPrice'], 2); ?>
                                                                </span>
                                                                <?php
                                                            }
                                                            ?>
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
<?php
include_once __DIR__ . '/footer.php';
?>

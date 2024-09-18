<?php
include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Instantiate ProductController and handle the request
$productController = new ProductController();
$products = $productController->handleRequests(); // This will fetch products based on GET/POST requests
// Get categories for dropdown
$categories = $productController->getCategoriesArray();

// Initialize variables for search and filters
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
$priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';
$weightMin = isset($_GET['weightMin']) ? $_GET['weightMin'] : '';
$weightMax = isset($_GET['weightMax']) ? $_GET['weightMax'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

// Determine which function to call based on user input
if ($searchTerm !== '') {
    // Fetch products based on search term
    $products = $productController->getProductsBySearch($searchTerm);
} elseif ($category || $priceMin || $priceMax || $weightMin || $weightMax || $availability !== '') {
    // Fetch products based on filters
    $products = $productController->getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
} else {
    // Default case: Fetch all products when no search or filter is applied
    $products = $productController->getAllProducts();
}

$category = isset($_GET['category']) ? $_GET['category'] : '';

$mostWantedProducts = $productController->getMostWantedProducts();

//$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
//var_dump($searchTerm);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

<style>
    .card-title:hover .product-name,
    .card-title:focus .product-name {
        color: #E91E63;
        text-decoration: none;
    }

    //filter dropdown
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: bold;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .form-select {
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        color: #495057;
        background-color: #ffffff;
    }

    .form-select:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
    }

    .form-select-sm {
        font-size: 0.805rem; /* Smaller font size */
        padding: 0.25rem 0.5rem; /* Less padding */
        width: 200px; /* Adjust width as needed */
    }

    .option {
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
    }

    .option:hover {
        background-color: #e2e6ea;
    }

    .option:active {
        background-color: #dae0e5;
    }

</style>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('../../../public/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Shopping Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>
            </div>
        </div>
    </div>

    <!--categorySearch--> 
    <?php include __DIR__ . '/categorySearch.php'; ?>

    <?php
    if ($category | $searchTerm):
        include __DIR__ . '/productCategory.php';
    else:
        ?>
        <!-- Most Wanted Section -->
        <section id="most-wanted">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Most Wanted</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
// Loop through the products and display them
                            foreach ($mostWantedProducts as $product):
                                ?>
                                <?php
                                // Fetch price and promotion data
                                $priceData = $productController->getPriceWithPromotion($product['ProductID']);
                                $promotion = $productController->hasPromotion($product['ProductID']);
                                ?>
                                <div class="item">
                                    <div class="card card-product">
                                        <div class="card-ribbon">
                                            <div class="card-ribbon-container right">
                                                <span class="ribbon ribbon-warning">TOP SELLER</span>
                                            </div>
                                        </div>
                                        <div class="card-badge">
                                            <div class="card-badge-container left">
                                                <!--<span class="badge badge-default" style="background-color: #E91E63; color: white;">Best Seller</span>-->
                                                <?php if ($promotion): ?>
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
                                                <?php endif; ?>
                                            </div>
                                            <a href="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>">
                                                <img src="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Product Image" width="250" height="250" class="card-img-top">
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <a href="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>">
                                                    <?php echo htmlspecialchars($product['ProductName']); ?>
                                                </a>
                                            </h4>
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
                                            <a href="add-to-cart.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="btn btn-block btn-primary">Add to Cart</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        $counter = 0;
        foreach ($categories as $categoryArray) {
            $category = $categoryArray['Category'];
            $counter++;
            $products = $productController->getProductsByCategory($category);
            // Only display section if there are products for this category
            if (is_array($products) && !empty($products)):
                $sectionClass = ($counter % 2 != 0) ? 'gray-bg' : '';
                ?>

                <section id="<?php echo htmlspecialchars($category); ?>" class="<?php echo $sectionClass; ?>">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h2 class="title"><?php echo ucfirst(htmlspecialchars($category)); ?></h2>
                                <div class="product-carousel owl-carousel">
                                    <?php foreach ($products as $product): ?>
                                        <a href="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="item-link">

                                            <?php
                                            // Fetch price and promotion data
                                            $priceData = $productController->getPriceWithPromotion($product['ProductID']);
                                            $promotion = $productController->hasPromotion($product['ProductID']);
                                            ?>

                                            <div class="item">
                                                <div class="card card-product">
                                                    <?php if ($promotion): ?>
                                                        <div class="card-ribbon">
                                                            <div class="card-ribbon-container right">
                                                                <span class="ribbon ribbon-primary">Promotion</span>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
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
                                                        <img src="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Card image" width="250" height="250" class="card-img-top">
                                                    </div>
                                                    <div class="card-body">
                                                        <h4 class="card-title" style="color: #333333;">
                                                            <span class="product-name"><?php echo htmlspecialchars($product['ProductName']); ?></span>
                                                        </h4>
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
                                                        <button class="btn btn-block btn-primary">Add to Cart</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
            endif;
        }
        ?>

    <?php endif; ?>

    <!--display category-->
</div>

<?php
include_once __DIR__ . '/footer.php';
?>

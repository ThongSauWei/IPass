<?php
// Get the category and search term from the URL
$category = isset($_GET['category']) ? $_GET['category'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$priceMin = isset($_GET['priceMin']) ? $_GET['priceMin'] : '';
$priceMax = isset($_GET['priceMax']) ? $_GET['priceMax'] : '';
$weightMin = isset($_GET['weightMin']) ? $_GET['weightMin'] : '';
$weightMax = isset($_GET['weightMax']) ? $_GET['weightMax'] : '';
$availability = isset($_GET['availability']) ? $_GET['availability'] : '';

// Fetch products based on the category or search term
if ($searchTerm) {
    $products = $productController->getProductsBySearch($searchTerm);
} elseif ($category || $priceMin || $priceMax || $weightMin || $weightMax || $availability !== '') {
    // Fetch products based on filters
    $products = $productController->getProductsByFilter($category, $priceMin, $priceMax, $weightMin, $weightMax, $availability);
} else {
    // Default behavior if no category or search term is provided
    $products = [];
}

// Get categories for dropdown
$categories = $productController->getCategoriesArray();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<style>
    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .product-item {
        flex: 1 1 calc(25% - 1rem); /* 25% width minus gap */
        box-sizing: border-box;
        max-width: 300px; /* Adjust max width as needed */
        margin: 0 auto; /* Center items in case of fewer than 4 items */
    }
    .card-product {
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        text-align: center;
    }
    .card-img-top {
        width: 100%;
        height: 200px; /* Fixed height */
        object-fit: cover;
    }
    .card-body {
        padding: 1rem;
    }
    .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    .card-price {
        margin-bottom: 1rem;
    }
</style>

<!-- Display products here -->
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if ($category || $searchTerm || $category || $priceMin || $priceMax || $weightMin || $weightMax || $availability !== ''): ?>
                    <h2 class="title"><?php echo htmlspecialchars($category ? ucfirst($category) : 'Search Results'); ?></h2>
                    <div class="product-grid">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <?php
                                // Fetch price and promotion data
                                $priceData = $productController->getPriceWithPromotion($product['ProductID']);
                                $promotion = $productController->hasPromotion($product['ProductID']);
                                ?>
                                <a href="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="product-item">
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
                                            <img src="../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Card image" class="card-img-top">
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
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No products available for this category or search term.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p>Please select a category or enter a search term to find products.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../models/Product.php'; // Correct path

$productModel = new Product();
$categories = $productModel->getCategories(); // Fetch distinct categories

?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-video text-center bg-dark mb-0 rounded-0">
            <video width="100%" preload="auto" loop autoplay muted>
                <source src="<?= ROOT ?>/assets/media/explore.mp4" type="video/mp4" />
                <source src="<?= ROOT ?>/assets/media/explore.webm" type="video/webm" />
            </video>
            <div class="container">
                <h1 class="pt-5">NSK Grocery: Convenience at Your Doorstep</h1>
                <p class="lead">Fresh Groceries Delivered, Every Day.</p>
            </div>
        </div>
    </div>

    <section id="why">
        <h2 class="title">Why Choose NSK Grocery</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 text-center gray-bg">
                        <div class="card-icon">
                            <div class="card-icon-i text-success">
                                <i class="fas fa-leaf"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Local & Fresh</h4>
                            <p class="card-text">
                                NSK Grocery brings farm-fresh produce directly to your doorstep, cutting out the middlemen and ensuring freshness.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center gray-bg">
                        <div class="card-icon">
                            <div class="card-icon-i text-success">
                                <i class="fa fa-question"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Know Your Suppliers</h4>
                            <p class="card-text">
                                We work closely with trusted local suppliers, ensuring you always know where your groceries come from.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 text-center gray-bg">
                        <div class="card-icon">
                            <div class="card-icon-i text-success">
                                <i class="fas fa-smile"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Supporting Local Businesses</h4>
                            <p class="card-text">
                                By choosing NSK Grocery, you support local farmers and suppliers, helping them grow their businesses.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="pb-0 gray-bg">
        <h2 class="title">Popular Categories</h2>
        <div class="landing-categories owl-carousel">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <?php 
                    // Fetch the image of a product from the current category
                    $productImage = $productModel->getProductImageByCategory($category['Category']);
                    $imagePath = $productImage ? ROOT . '/' . $productImage : ROOT . '/assets/img/default-category.jpg'; 
                    ?>
                    <div class="item">
                        <div class="card rounded-0 border-0 text-center">
                            <img src="<?= ROOT ?>/assets/img/<?= $product['ProductImage'] ?>" alt="<?= $product['ProductName'] ?>">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                <a href="shop.php?category=<?= urlencode($category['Category']) ?>" class="btn btn-primary btn-lg"><?= $category['Category'] ?></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Default shape when no categories are available -->
                <div class="item">
                    <div class="card rounded-0 border-0 text-center">
                        <img src="<?= ROOT ?>/assets/img/default-category.jpg" alt="No Categories Available">
                        <div class="card-img-overlay d-flex align-items-center justify-content-center">
                            <span class="text-muted">No Categories Available</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php
include_once __DIR__ . '/footer.php';
?>

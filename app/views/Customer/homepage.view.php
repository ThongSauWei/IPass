<?php
include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../models/Product.php'; // Ensure correct path
// Initialize Product model
$productModel = new Product();
// Fetch distinct categories
$categories = $productModel->getCategories();
$categoriesWithImages = $productModel->getCategoriesWithImages();
?>

<style>
    .slide-img {
    filter: blur(5px); /* Adjust the blur intensity here */
    -webkit-filter: blur(5px);
    height: 100vh; /* Adjust based on your image height */
    object-fit: cover;
    position: relative;
}

.carousel-caption {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff; /* You can change this based on your preference */
    text-align: center;
    z-index: 10;
}

.promotion-title {
    font-size: 3rem;
    font-weight: bold;
    color: #e41d61; /* Set to a strong color that stands out */
}

.promotion-lead {
    font-size: 1.5rem;
    color: #fff; /* You can adjust based on the background */
}

.btn-primary {
    background-color: #e41d61; /* Button color */
    border: none;
}
</style>

<div id="page-content" class="page-content">
    <div class="banner">
        <!-- Carousel/Slider -->
        <div id="promotionCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators for the slides -->
            <ol class="carousel-indicators">
                <li data-target="#promotionCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#promotionCarousel" data-slide-to="1"></li>
                <li data-target="#promotionCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Slides -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="<?= ROOT ?>/assets/img/slide.jpg" style="height: 600px; " class="d-block w-100 slide-img" alt="Promotion 1">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                        <h1 class="pt-5"">NSK PROMOTION UNDERWAY!</h1>
                        <p class="lead" style="color: #ffc926;">More promotion details? Click the button!</p>
                        <a href="promotion.php" class="btn btn-primary btn-lg">VIEW PROMOTION</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= ROOT ?>/assets/img/slide2.jpg" style="height: 600px; " class="d-block w-100 slide-img" alt="Promotion 2">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                        <h1 class="pt-5">NSK PROMOTION UNDERWAY!</h1>
                        <p class="lead" style="color: #ffc926;">More promotion details? Click the button!</p>
                        <a href="promotion.php" class="btn btn-primary btn-lg">VIEW PROMOTION</a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= ROOT ?>/assets/img/slide3.jpg" style="height: 600px; " class="d-block w-100 slide-img" alt="Promotion 3">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                        <h1 class="pt-5">NSK PROMOTION UNDERWAY!</h1>
                        <p class="lead" style="color: #ffc926;">More promotion details? Click the button!</p>
                        <a href="promotion.php" class="btn btn-primary btn-lg">VIEW PROMOTION</a>
                    </div>
                </div>
            </div>

            <!-- Controls for the slides -->
            <a class="carousel-control-prev" href="#promotionCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#promotionCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
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

                <div class="col-md-12 mt-5 text-center">
                    <a href="shop.php" class="btn btn-primary btn-lg">START SHOPPING</a>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="pb-0 gray-bg">
        <h2 class="title">Popular Categories</h2>
        <div class="landing-categories owl-carousel">
            <?php if (!empty($categoriesWithImages)): ?>
                <?php foreach ($categoriesWithImages as $category => $image): ?>
                    <?php
                    // Use the product image for the category, or a default image if none found
                    $imagePath = $image ? ROOT . '/assets/img/ProductImage/' . $image : ROOT . '/assets/img/default-category.jpg';
                    ?>
                    <div class="item">
                        <div class="card rounded-0 border-0 text-center">
                            <img src="<?= $imagePath ?>" style="width: 350px; height: 330px; " alt="<?= $category ?>">
                            <div class="card-img-overlay d-flex align-items-center justify-content-center">
                                <a href="shop.php?category=<?= urlencode($category) ?>" class="btn btn-primary btn-lg"><?= $category ?></a>
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

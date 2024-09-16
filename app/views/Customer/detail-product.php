
<?php
include_once __DIR__ . '/header.php';
?>

<?php
require_once __DIR__ . '/../../controllers/ProductController.php';

if (isset($_GET['productID']) && !empty($_GET['productID'])) {
    $productController = new ProductController();

    // Ensure the ProductID is treated as a string
    $productID = $_GET['productID'];
    $product = $productController->getProductById($productID);

    // Check if the product exists and access the first item if it's an array of arrays
    if ($product && is_array($product) && isset($product[0])) {
        $product = $product[0]; // Get the first product in the array
    } else {
        $error = "Product not found.";
    }
} else {
    $error = "No product ID provided.";
}
?>

<style>
    button:hover,
    button:focus  {
        background-color: #E91E63;
        text-decoration: none;
    }
    button{
        background-color: #333333;
    }

</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
            <div class="product-detail">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="slider-zoom">
                                <!--<a href="assets/img/meats.jpg" class="cloud-zoom" rel="transparentImage: 'data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', useWrapper: false, showTitle: false, zoomWidth:'500', zoomHeight:'500', adjustY:0, adjustX:10" id="cloudZoom">-->
                                <img alt="Detail Zoom thumbs image" src="../../<?php echo htmlspecialchars($product['ProductImage']); ?>" style="width: 100%;">
                                <!--</a>-->
                            </div>

                            <!--                            <div class="slider-thumbnail">
                                                            <ul class="d-flex flex-wrap p-0 list-unstyled">
                                                                <li>
                                                                    <a href="assets/img/meats.jpg" rel="gallerySwitchOnMouseOver: true, popupWin:'assets/img/meats.jpg', useZoom: 'cloudZoom', smallImage: 'assets/img/meats.jpg'" class="cloud-zoom-gallery">
                                                                        <img itemprop="image" src="assets/img/meats.jpg" style="width:135px;">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="assets/img/fish.jpg" rel="gallerySwitchOnMouseOver: true, popupWin:'assets/img/fish.jpg', useZoom: 'cloudZoom', smallImage: 'assets/img/fish.jpg'" class="cloud-zoom-gallery">
                                                                        <img itemprop="image" src="assets/img/fish.jpg" style="width:135px;">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="assets/img/vegetables.jpg" rel="gallerySwitchOnMouseOver: true, popupWin:'assets/img/vegetables.jpg', useZoom: 'cloudZoom', smallImage: 'assets/img/vegetables.jpg'" class="cloud-zoom-gallery">
                                                                        <img itemprop="image" src="assets/img/vegetables.jpg" style="width:135px;">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="assets/img/frozen.jpg" rel="gallerySwitchOnMouseOver: true, popupWin:'assets/img/frozen.jpg', useZoom: 'cloudZoom', smallImage: 'assets/img/frozen.jpg'" class="cloud-zoom-gallery">
                                                                        <img itemprop="image" src="assets/img/frozen.jpg" style="width:135px;">
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>-->

                        </div>
                        <div class="col-sm-6">
                            <p>
                                <strong>Overview</strong><br>
                                <?php echo htmlspecialchars($product['ProductDesc']); ?>
                            </p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Price Section -->
                                    <p>
                                        <strong>Price</strong>
                                        <br>
                                        <span class="price">
                                            <?php echo htmlspecialchars($product['Price']); ?>
                                        </span>
                                        <span class="old-price">Rp 100.000</span>
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
                                            <?php echo htmlspecialchars($product['Weight']); ?>

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
                            <p class="mb-1">
                                <strong>Quantity</strong>
                            </p>
                            <div class="row">
                                <div class="col-sm-5">
                                    <!-- Set default value to 1 -->
                                    <input class="vertical-spin" type="text" 
                                           data-bts-button-down-class="btn btn-primary" 
                                           data-bts-button-up-class="btn btn-primary" 
                                           value="1" 
                                           name="vertical-spin">
                                </div>
                                <div class="col-sm-6">
                                    <span class="pt-1 d-inline-block">Pack (250 gram)</span>
                                </div>
                            </div>


                            <button class="mt-3 btn btn-lg" style="background-color: #E91E63; color: #ffffff;">
                                <i class="fa fa-shopping-basket"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    <?php endif; ?>

    <section id="related-product">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Related Products</h2>
                    <div class="product-carousel owl-carousel">
                        <?php
                        // Example PHP code to fetch and loop through related products
                        $relatedProducts = [
                            ['img' => 'meats.jpg', 'title' => 'Product Title', 'discount' => 'Rp. 300.000', 'reguler' => 'Rp. 200.000'],
                            ['img' => 'fish.jpg', 'title' => 'Product Title', 'discount' => 'Rp. 300.000', 'reguler' => 'Rp. 200.000'],
                            ['img' => 'vegetables.jpg', 'title' => 'Product Title', 'discount' => 'Rp. 300.000', 'reguler' => 'Rp. 200.000'],
                            ['img' => 'frozen.jpg', 'title' => 'Product Title', 'discount' => 'Rp. 300.000', 'reguler' => 'Rp. 200.000'],
                            ['img' => 'fruits.jpg', 'title' => 'Product Title', 'discount' => 'Rp. 300.000', 'reguler' => 'Rp. 200.000']
                        ];

                        foreach ($relatedProducts as $product) {
                            echo '<div class="item">
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
                                        <img src="assets/img/' . $product['img'] . '" alt="Card image" class="card-img-top">
                                    </div>
                                    <div class="card-body">
                                        <h4 class="card-title">
                                            <a href="views/detail-product.php">' . $product['title'] . '</a>
                                        </h4>
                                        <div class="card-price">
                                            <span class="discount">' . $product['discount'] . '</span>
                                            <span class="reguler">' . $product['reguler'] . '</span>
                                        </div>
                                        <a href="views/detail-product.php" class="btn btn-block btn-primary">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
<?php
include_once __DIR__ . '/footer.php';
?>

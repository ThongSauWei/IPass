
        <?php
        include_once __DIR__ . '/header.php';
        ?>
        <div id="page-content" class="page-content">
            <div class="banner">
                <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
                    <div class="container">
                        <h1 class="pt-5">
                            The Meat Product Title
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
                                <a href="assets/img/meats.jpg" class="cloud-zoom" rel="transparentImage: 'data:image/gif;base64,R0lGODlhAQABAID/AMDAwAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==', useWrapper: false, showTitle: false, zoomWidth:'500', zoomHeight:'500', adjustY:0, adjustX:10" id="cloudZoom">
                                    <img alt="Detail Zoom thumbs image" src="assets/img/meats.jpg" style="width: 100%;">
                                </a>
                            </div>

                            <div class="slider-thumbnail">
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
                            </div>

                        </div>
                        <div class="col-sm-6">
                            <p>
                                <strong>Overview</strong><br>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                            </p>
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>
                                        <strong>Price</strong> (/Pack)<br>
                                        <span class="price">Rp 100.000</span>
                                        <span class="old-price">Rp 150.000</span>
                                    </p>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <p>
                                        <span class="stock available">In Stock: 99</span>
                                    </p>
                                </div>
                            </div>
                            <p class="mb-1">
                                <strong>Quantity</strong>
                            </p>
                            <div class="row">
                                <div class="col-sm-5">
                                    <input class="vertical-spin" type="text" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="" name="vertical-spin">
                                </div>
                                <div class="col-sm-6"><span class="pt-1 d-inline-block">Pack (250 gram)</span></div>
                            </div>

                            <button class="mt-3 btn btn-primary btn-lg">
                                <i class="fa fa-shopping-basket"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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

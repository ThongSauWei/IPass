<?php
include_once __DIR__ . '/header.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Instantiate ProductController and handle the request
$productController = new ProductController();
$products = $productController->handleRequests(); // This will fetch products based on GET/POST requests
// Get categories for dropdown
$categories = $productController->getCategoriesArray();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
<style>
    .card-title:hover .product-name,
    .card-title:focus .product-name {
        color: #E91E63;
        text-decoration: none;
    }

</style>


<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('../../../public/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Shopping Page
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <!--top cat-->
        <div class="row">
            <div class="col-md-12">
                <div class="shop-categories owl-carousel mt-5">
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-carrot"></i></span>
                                <div class="media-body">
                                    <h5>Vegetables</h5>
                                    <p>Freshly Harvested Veggies From Local Growers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-apple"></i></span>
                                <div class="media-body">
                                    <h5>Fruits</h5>
                                    <p>Variety of Fruits From Local Growers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-roast-leg"></i></span>
                                <div class="media-body">
                                    <h5>Meats</h5>
                                    <p>Protein Rich Ingridients From Local Farmers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-fish-1"></i></span>
                                <div class="media-body">
                                    <h5>Fishes</h5>
                                    <p>Protein Rich Ingridients From Local Farmers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-french-fries"></i></span>
                                <div class="media-body">
                                    <h5>Frozen Foods</h5>
                                    <p>Protein Rich Ingridients From Local Farmers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <a href="shop.html">
                            <div class="media d-flex align-items-center justify-content-center">
                                <span class="d-flex mr-2"><i class="sb-bistro-appetizer"></i></span>
                                <div class="media-body">
                                    <h5>Packages</h5>
                                    <p>Protein Rich Ingridients From Local Farmers</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container for the search form and filter button -->
        <!--<div class="container" >-->
        <div class="row" style="margin-top:20px;">
            <!-- Search Form -->
            <div class="col-md-11">
                <form class="d-flex mb-3" method="get" action="">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search by name or category" aria-label="Search" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>">
                    <button class="btn btn-outline-info" type="submit">Search</button>
                </form>
<!--                <form action="shop.php" method="GET">
                    <input type="text" name="search" value="<?php echo htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : ''); ?>" placeholder="Search by name or category" />
                    <button type="submit">Search</button>
                </form>-->
            </div>

            <!-- Filter Button with Bootstrap Icon -->
            <div class="col-md-1 d-flex align-items-center">
                <button class="btn btn-info mb-3 w-100" id="filterButton">
                    <i class="bi bi-funnel"></i> 
                </button>
            </div>

        </div>

        <!-- Filter Drawer -->
        <div class="drawer" id="filterDrawer" style="display:none; position: fixed; right: 0; top: 0; width: 300px; height: 100%; background-color: #f8f9fa; border-left: 1px solid #ddd; box-shadow: -2px 0 5px rgba(0,0,0,0.1); z-index: 1050; overflow-y: auto;">
            <div class="p-3">
                <h4>Filter Products</h4>
                <form method="get" action="">
                    <!-- Category Filter -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['Category']); ?>" <?php echo $cat['Category'] == $category ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['Category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="mb-3">
                        <label for="priceMin" class="form-label">Price Range</label>
                        <input type="number" id="priceMin" name="priceMin" class="form-control" placeholder="Min Price" value="<?php echo htmlspecialchars($priceMin); ?>">
                        <input type="number" id="priceMax" name="priceMax" class="form-control mt-2" placeholder="Max Price" value="<?php echo htmlspecialchars($priceMax); ?>">
                    </div>

                    <!-- Weight Range Filter -->
                    <div class="mb-3">
                        <label for="weightMin" class="form-label">Weight Range</label>
                        <input type="number" id="weightMin" name="weightMin" class="form-control" placeholder="Min Weight (kg)" value="<?php echo htmlspecialchars($weightMin); ?>">
                        <input type="number" id="weightMax" name="weightMax" class="form-control mt-2" placeholder="Max Weight (kg)" value="<?php echo htmlspecialchars($weightMax); ?>">
                    </div>

                    <!-- Availability Filter -->
                    <div class="mb-3">
                        <label for="availability" class="form-label">Availability</label>
                        <select id="availability" name="availability" class="form-select">
                            <option value="">All</option>
                            <option value="1" <?php echo $availability == '1' ? 'selected' : ''; ?>>Available</option>
                            <option value="0" <?php echo $availability == '0' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <button type="button" class="btn btn-secondary ms-2" id="closeFilter">Close</button>
                </form>
            </div>
        </div>
        <!--</div>-->

    </div>

    <!--most wanted-->
    <section id="most-wanted">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Most Wanted</h2>
                    <div class="product-carousel owl-carousel">
                        <div class="item">
                            <div class="card card-product">
                                <div class="card-ribbon">
                                    <div class="card-ribbon-container right">
                                        <span class="ribbon ribbon-primary">SPECIAL</span>
                                    </div>
                                </div>
                                <div class="card-badge">
                                    <div class="card-badge-container left">
                                        <span class="badge badge-default">
                                            Until 2018
                                        </span>
                                        <span class="badge badge-primary">
                                            20% OFF
                                        </span>
                                    </div>
                                    <img src="../../../public/assets/img/meats.jpg" alt="Card image 2" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="detail-product.html">Product Title</a>
                                    </h4>
                                    <div class="card-price">
                                        <span class="discount">Rp. 300.000</span>
                                        <span class="reguler">Rp. 200.000</span>
                                    </div>
                                    <a href="detail-product.html" class="btn btn-block btn-primary">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card card-product">
                                <div class="card-ribbon">
                                    <div class="card-ribbon-container right">
                                        <span class="ribbon ribbon-primary">SPECIAL</span>
                                    </div>
                                </div>
                                <div class="card-badge">
                                    <div class="card-badge-container left">
                                        <span class="badge badge-default">
                                            Until 2018
                                        </span>
                                        <span class="badge badge-primary">
                                            20% OFF
                                        </span>
                                    </div>
                                    <img src="../../../public/assets/img/fish.jpg" alt="Card image 2" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="detail-product.html">Product Title</a>
                                    </h4>
                                    <div class="card-price">
                                        <span class="discount">Rp. 300.000</span>
                                        <span class="reguler">Rp. 200.000</span>
                                    </div>
                                    <a href="detail-product.html" class="btn btn-block btn-primary">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card card-product">
                                <div class="card-ribbon">
                                    <div class="card-ribbon-container right">
                                        <span class="ribbon ribbon-primary">SPECIAL</span>
                                    </div>
                                </div>
                                <div class="card-badge">
                                    <div class="card-badge-container left">
                                        <span class="badge badge-default">
                                            Until 2018
                                        </span>
                                        <span class="badge badge-primary">
                                            20% OFF
                                        </span>
                                    </div>
                                    <img src="../../../public/assets/img/vegetables.jpg" alt="Card image 2" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="detail-product.html">Product Title</a>
                                    </h4>
                                    <div class="card-price">
                                        <span class="discount">Rp. 300.000</span>
                                        <span class="reguler">Rp. 200.000</span>
                                    </div>
                                    <a href="detail-product.html" class="btn btn-block btn-primary">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card card-product">
                                <div class="card-ribbon">
                                    <div class="card-ribbon-container right">
                                        <span class="ribbon ribbon-primary">SPECIAL</span>
                                    </div>
                                </div>
                                <div class="card-badge">
                                    <div class="card-badge-container left">
                                        <span class="badge badge-default">
                                            Until 2018
                                        </span>
                                        <span class="badge badge-primary">
                                            20% OFF
                                        </span>
                                    </div>
                                    <img src="../../../public/assets/img/frozen.jpg" alt="Card image 2" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="detail-product.html">Product Title</a>
                                    </h4>
                                    <div class="card-price">
                                        <span class="discount">Rp. 300.000</span>
                                        <span class="reguler">Rp. 200.000</span>
                                    </div>
                                    <a href="detail-product.html" class="btn btn-block btn-primary">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card card-product">
                                <div class="card-ribbon">
                                    <div class="card-ribbon-container right">
                                        <span class="ribbon ribbon-primary">SPECIAL</span>
                                    </div>
                                </div>
                                <div class="card-badge">
                                    <div class="card-badge-container left">
                                        <span class="badge badge-default">
                                            Until 2018
                                        </span>
                                        <span class="badge badge-primary">
                                            20% OFF
                                        </span>
                                    </div>
                                    <img src="../../../public/assets/img/fruits.jpg" alt="Card image 2" class="card-img-top">
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title">
                                        <a href="detail-product.html">Product Title</a>
                                    </h4>
                                    <div class="card-price">
                                        <span class="discount">Rp. 300.000</span>
                                        <span class="reguler">Rp. 200.000</span>
                                    </div>
                                    <a href="detail-product.html" class="btn btn-block btn-primary">
                                        Add to Cart
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--display category-->
    <?php
    $counter = 0;
    // Display product categories and products
    foreach ($categories as $categoryArray) {
        $category = $categoryArray['Category'];
        $counter++;
        $products = $productController->getProductsByCategory($category);
        $sectionClass = ($counter % 2 != 0) ? 'gray-bg' : '';
        // Display the products
//// Initialize a counter
//    $counter = 0;
//
//    foreach ($categories as $categoryArray) {
//        // Access the actual category name
//        $category = $categoryArray['Category']; // Extract the 'Category' value
//        // Increment the counter
//        $counter++;
//
//        // Fetch products for the current category
//        $products = $controller->getProductsByCategory($category);
//
//        // Apply "gray-bg" class only for the first category or every other category
//        $sectionClass = ($counter % 2 != 0) ? 'gray-bg' : '';
        ?>
        <section id="<?php echo htmlspecialchars($category); ?>" class="<?php echo $sectionClass; ?>">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title"><?php echo ucfirst(htmlspecialchars($category)); ?></h2>
                        <div class="product-carousel owl-carousel">
                            <?php foreach ($products as $product): ?>
                                <a href="detail-product.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="item-link">
                                    <div class="item">
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
                                                <img src="../../<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Card image" width="250" height="250" class="card-img-top">
                                            </div>
                                            <div class="card-body">
                                                <h4 class="card-title" style="color: #333333; ">
                                                    <span class="product-name"><?php echo htmlspecialchars($product['ProductName']); ?></span>
                                                </h4>
                                                <div class="card-price">
                                                    <span class="discount">Rp. 300.000</span>
                                                    <span class="reguler">RM <?php echo htmlspecialchars($product['Price']); ?></span>
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
    }
    ?>

</div>

<script>
    document.getElementById('filterButton').addEventListener('click', function () {
        document.getElementById('filterDrawer').style.display = 'block';
    });

    document.getElementById('closeFilter').addEventListener('click', function () {
        document.getElementById('filterDrawer').style.display = 'none';
    });

    document.getElementById('filterButton').addEventListener('click', function () {
        document.getElementById('filterDrawer').classList.add('drawer-open');
    });

    document.getElementById('closeFilter').addEventListener('click', function () {
        document.getElementById('filterDrawer').classList.remove('drawer-open');
    });

</script>
<?php
include_once __DIR__ . '/footer.php';
?>


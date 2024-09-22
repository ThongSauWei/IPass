<?php
require_once __DIR__ . '/../../facades/userFacade.php';
require_once __DIR__ . '/../../core/SessionManager.php';
require_once __DIR__ . '/../../models/CartService.php';
require_once __DIR__ . '/../../models/Product.php';

SessionManager::startSession();
$isLoggedIn = SessionManager::loggedIn();
$user = SessionManager::getUser();
$userFacade = new UserFacade();
if ($isLoggedIn) {
    //get the customer name
    $userID = $user['UserID'];
    $profileImage = $userFacade->getUserProfileImage($userID);
    $customerDetails = $userFacade->getCustomerDetails($userID);  // Retrieve customer details
    $customerName = $customerDetails['CustomerName'];

    $profileImage = $profileImage ? ROOT . $profileImage : ROOT . '/assets/img/logo/avatar.jpg';
    
    $cartService = new CartService();
    $productModel = new Product();
    $customerID = $customerDetails["CustomerID"];
    
    $cartItemsHeader = $cartService->getCartForCustomer($customerID);
    
    $subtotal = 0;

    if (is_array($cartItemsHeader) && !empty($cartItemsHeader)) {
        $itemCount = count($cartItemsHeader);
        foreach ($cartItemsHeader as $key => $cartItem) {
            $productID = $cartItem["ProductID"];
            $product = $productModel->getById($productID);

            if (false) { // If got promotion
                $discount = 0;
                $promotionPrice = number_format($product[0]["Price"] - $discount, 2);
                $cartItemsHeader[$key]["PromotionPrice"] = $promotionPrice;
            }

            $price = number_format($product[0]["Price"], 2);
            $cartItemsHeader[$key]["ProductName"] = $product[0]["ProductName"];
            $cartItemsHeader[$key]["Price"] = $price;
            $cartItemsHeader[$key]["Weight"] = number_format($product[0]["Weight"], 0);
            $cartItemsHeader[$key]["ProductImage"] = $product[0]["ProductImage"] ?? "meats.jpg";
            
            $subtotal += ($promotionPrice ?? $price) * $cartItem["Quantity"];
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>


        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Groceries Store</title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
        <link href="<?= ROOT ?>/assets/fonts/sb-bistro/sb-bistro.css" rel="stylesheet" type="text/css">
        <link href="<?= ROOT ?>/assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/bootstrap/bootstrap.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/o2system-ui/o2system-ui.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/owl-carousel/owl-carousel.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/cloudzoom/cloudzoom.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/thumbelina/thumbelina.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/packages/bootstrap-touchspin/bootstrap-touchspin.css">
        <link rel="stylesheet" type="text/css" media="all" href="<?= ROOT ?>/assets/css/theme.css">
    </head>
    <body>
        <div class="page-header">
            <!--=============== Navbar ===============-->
            <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-transparent" id="page-navigation">
                <div class="container">
                    <!-- Navbar Brand -->
                    <a href="http://localhost/IPass/app/views/Customer/homepage.view.php" class="navbar-brand">
                        <img src="<?= ROOT ?>/assets/img/logo/nsk.png" alt="">
                    </a>

                    <!-- Toggle Button -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarcollapse">
                        <!-- Navbar Menu -->
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a href="shop.php" class="nav-link">Shop</a>
                            </li>

                            <!-- If user hasn't logged in, show Register and Login -->
                            <?php if (!$isLoggedIn): ?>
                                <li class="nav-item">
                                    <a href="register.php" class="nav-link">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a href="login.php" class="nav-link">Login</a>
                                </li>

                                <!-- If the user is logged in -->
                            <?php else: ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="avatar-header"><img src="<?= $profileImage ?>" alt="User Image" style="height: 30px; width:30px;">
                                        </div> <?= $customerName ?>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="http://localhost/IPass/app/controllers/TransactionController.php?action=showPage">Transactions History</a>
                                        <a class="dropdown-item" href="http://localhost/IPass/app/views/Customer/profile.php">Profile</a>
                                        <a class="dropdown-item" href="http://localhost/IPass/app/views/Customer/wish.php">Wishlist</a>
                                        <a class="dropdown-item" href="/IPass/app/controllers/UserController.php?action=logout">Logout</a>
                                    </div>
                                </li>
                                <?php if (!isset($itemCount)): ?>
                                <li class="nav-item">
                                    <a href="http://localhost/IPass/app/views/Customer/shop.php" class="nav-link">
                                        <i class="fa fa-shopping-basket"></i>
                                    </a>
                                </li>
                                <?php else: ?>
                                <li class="nav-item dropdown">
                                    <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary"><?= $itemCount ?></span>
                                    </a>
                                    <div class="dropdown-menu shopping-cart">
                                        <!-- Cart Details -->
                                        <ul>
                                            <li>
                                                <div class="drop-title">Your Cart</div>
                                            </li>
                                            <li>
                                                <div class="shopping-cart-list">
                                                    <!-- Example Cart Item -->
                                                    <?php foreach ($cartItemsHeader as $item): ?>
                                                    <div class="media">
                                                        <img class="d-flex mr-3" src="<?= ROOT ?>/assets/img/ProductImage/<?= $item["ProductImage"] ?>" width="60">
                                                        <div class="media-body">
                                                            <h5><a href="javascript:void(0)"><?= $item["ProductName"] ?></a></h5>
                                                            <p class="price">
                                                                <?php if (isset($item["PromotionPrice"])): ?>
                                                                <span class="discount text-muted">RM <?= $item["Price"] ?></span>
                                                                <span>RM <?= $item["PromotionPrice"] ?></span>
                                                                <?php else: ?>
                                                                <span>RM <?= $item["Price"] ?></span>
                                                                <?php endif; ?>
                                                            </p>
                                                            <p class="text-muted">Qty: <?= $item["Quantity"] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="drop-title d-flex justify-content-between">
                                                    <span>Total:</span>
                                                    <span class="text-primary"><strong>RM <?= number_format($subtotal, 2) ?></strong></span>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between pl-3 pr-3 pt-3">
                                                <a href="http://localhost/IPass/app/controllers/CartController.php?action=showCart" class="btn btn-default">View Cart</a>
                                                <a href="http://localhost/IPass/app/controllers/CheckoutController.php?action=showPage" class="btn btn-primary">Checkout</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>

        <script type="text/javascript" src="<?= ROOT ?>/assets/js/jquery.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/js/jquery-migrate.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/bootstrap/libraries/popper.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/bootstrap/bootstrap.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/o2system-ui/o2system-ui.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/owl-carousel/owl-carousel.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/cloudzoom/cloudzoom.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/thumbelina/thumbelina.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/packages/bootstrap-touchspin/bootstrap-touchspin.js"></script>
        <script type="text/javascript" src="<?= ROOT ?>/assets/js/theme.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const navItem = document.querySelectorAll('.nav-item.dropdown').forEach(function (element) {
                    const dropdownMenu = element.querySelector('.dropdown-menu');
            
                    element.addEventListener('mouseenter', function () {
                        dropdownMenu.classList.add('show');
                    });
            
                    element.addEventListener('mouseleave', function () {
                        dropdownMenu.classList.remove('show');
                    });
                });
            });
        </script>



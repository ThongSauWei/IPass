<?php
$configPath = dirname(__DIR__, 2) . '/core/config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    echo 'Config file not found: ' . $configPath;
}
?>

<?php
require_once __DIR__ . '/../../facades/userFacade.php';
require_once __DIR__ . '/../../core/SessionManager.php';

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
                    <a href="homepage.view.php" class="navbar-brand">
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
                                        <a class="dropdown-item" href="transaction.php">Transactions History</a>
                                        <a class="dropdown-item" href="profile.php">Profile</a>
                                        <a class="dropdown-item" href="wish.php">Wishlist</a>
                                        <a class="dropdown-item" href="/IPass/app/controllers/UserController.php?action=logout">Logout</a>
                                    </div>
                                </li>

                                <li class="nav-item dropdown">
                                    <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary">5</span>
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
                                                    <div class="media">
                                                        <img class="d-flex mr-3" src="<?= ROOT ?>/assets/img/logo/avatar.jpg" width="60">
                                                        <div class="media-body">
                                                            <h5><a href="javascript:void(0)">Carrot</a></h5>
                                                            <p class="price">
                                                                <span class="discount text-muted">Rp. 700.000</span>
                                                                <span>Rp. 100.000</span>
                                                            </p>
                                                            <p class="text-muted">Qty: 1</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="drop-title d-flex justify-content-between">
                                                    <span>Total:</span>
                                                    <span class="text-primary"><strong>Rp. 2000.000</strong></span>
                                                </div>
                                            </li>
                                            <li class="d-flex justify-content-between pl-3 pr-3 pt-3">
                                                <a href="cart.php" class="btn btn-default">View Cart</a>
                                                <a href="checkout.php" class="btn btn-primary">Checkout</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
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


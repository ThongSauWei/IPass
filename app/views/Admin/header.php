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
SessionManager::requireLogin();

$user = SessionManager::getUser();
$userFacade = new UserFacade();

if ($user) {
    $userID = $user['UserID'];
    $profileImage = $userFacade->getUserProfileImage($userID); // Fetch the user's profile image
    $profileImage = $profileImage ? ROOT . $profileImage : ROOT . '/public/assets/img/default-profile.png'; // Use default if no image found
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>SB Admin 2 - Blank</title>

        <link href="<?= ROOT ?>/assets/vendor/fontawesome-free/css/all.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link href="<?= ROOT ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">


    </head>

    <body id="page-top">


        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= ROOT ?>/../views/Admin/dashboard.view.php">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item active">
                    <a class="nav-link" href="<?= ROOT ?>/../views/Admin/dashboard.view.php">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Dashboard</span></a>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Staff -->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                       aria-controls="collapsePages">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>User</span>
                    </a>
                    <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                         data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <div class="collapse-divider"></div>
                            <!-- Staff Section: Only visible to admin -->
                            <?php if (SessionManager::superAdmin()) : ?>
                                <h6 class="collapse-header">Staff:</h6>
                                <a class="collapse-item" href="./User/displayStaff.php">Display All Staff</a>
                                <a class="collapse-item" href="./User/addStaff.php">Create Staff</a>
                                
                            <?php endif; ?>
                            <h6 class="collapse-header">Customer:</h6>
                            <a class="collapse-item" href="./User/displayCustomer.php">Display All Customer</a>
                            <a class="collapse-item" href="./User/addCustomer.php">Create Customer</a>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Promotion module -->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                       aria-controls="collapsePages">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Promotion</span>
                    </a>
                    <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                         data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <div class="collapse-divider"></div>
                            <h6 class="collapse-header">Voucher:</h6>
                            <a class="collapse-item" href="<?= ROOT ?>/VoucherController/create">Add New Voucher</a>
                            <a class="collapse-item" href="<?= ROOT ?>/VoucherController">View All Voucher</a>
                            <h6 class="collapse-header">Promotion:</h6>
                            <a class="collapse-item" href="<?= ROOT ?>/views/admin/vouchers/blank.html">View All Promotion</a>
                            <a class="collapse-item" href="<?= ROOT ?>/views/admin/vouchers/blank.html">Add New Promotion</a>
                        </div>
                    </div>
                </li>
                <!-- Nav Item - Ticket -->
                <li class="nav-item">
                    <a class="nav-link" href="ticket.html">
                        <i class="fas fa-fw fa-table"></i>
                        <span>Ticket</span></a>
                </li>

                <!-- Nav Item - Product module -->
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
                       aria-controls="collapsePages">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Product</span>
                    </a>
                    <div id="collapsePages" class="collapse" aria-labelledby="headingPages"
                         data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <div class="collapse-divider"></div>
                            <h6 class="collapse-header">Product:</h6>
                            <a class="collapse-item" href="<?= ROOT ?>/../app/views/Admin/Product/displayProduct.php">View All Product</a>
                            <a class="collapse-item" href="<?= ROOT ?>/../app/views/Admin/Product/productLog.php">Product Transaction Log</a>
                            
                        </div>
                    </div>
                </li>


                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Search -->
                        <form
                            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                       aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['user']['Username'] ?? 'Guest'; ?></span>
                                    <img class="img-profile rounded-circle" src="<?= $profileImage ?>" alt="User Image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                     aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="AdminProfile.php">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="settings.php">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/IPass/app/controllers/UserController.php?action=logout">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>

                    </nav>
                    <!-- End of Topbar -->
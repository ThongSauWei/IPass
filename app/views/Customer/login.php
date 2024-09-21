<?php
require_once __DIR__ . '/../../core/SessionManager.php';

SessionManager::startSession();

if (SessionManager::loggedIn()) {
    header('Location: homepage.view.php');
    exit();
}
?>

<?php
include_once __DIR__ . '/header.php';
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Login Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>
                <div class="card card-login mb-5">
                    <div class="card-body">

                        <!-- Moved alert container inside the card body, above the form -->
                        <div id="centered-alert-container">
                            <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php
                                    // Check if the error message is an array, and if so, implode it into a string
                                    if (is_array($_SESSION['error'])) {
                                        echo implode('<br>', $_SESSION['error']); // Convert array elements into a string with <br> as separator
                                    } else {
                                        echo $_SESSION['error']; // If it's a string, display it as-is
                                    }
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['error']); // Clear errors after displaying ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= $_SESSION['success'] ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['success']); // Clear success message after displaying ?>
                            <?php endif; ?>
                        </div>

                        <!-- Login Form -->
                        <form class="form-horizontal" method="post" action="/IPass/app/controllers/UserController.php?action=login">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="identity" required placeholder="Email or Username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <a href="http://localhost/IPass/app/views/Customer/register.php" class="text-light" style="color:#E91E63 !important;" > Don't have an account?</a>
                                    <a href="http://localhost/IPass/app/views/Customer/PassRecoverEmail.php" class="text-light"><i class="fa fa-bell"></i> Forgot password?</a>
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Log In</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>

<!-- Adjust the alert position to align with the card body -->
<style>
    #centered-alert-container {
        margin-bottom: 20px; /* Space between the alert and the form */
    }
</style>

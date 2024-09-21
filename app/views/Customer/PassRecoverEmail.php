<?php
include_once __DIR__ . '/header.php';
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/pass.jpg');">
            <div class="container">
                <h1 class="pt-5">Password Recovery</h1>
                <p class="lead">Please enter your email to reset your password.</p>
                <div class="card card-login mb-5">
                    <div class="card-body">
                        <!-- Alert Container placed at the top of card body -->
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

                        <!-- Form starts here -->
                        <form class="form-horizontal" method="post" action="/IPass/app/controllers/PassRecoveryController.php?action=recovery">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" required placeholder="Enter your email">
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Submit</button>
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <a href="http://localhost/IPass/app/views/Customer/Login.php" class="btn btn-secondary btn-block text-uppercase" style="margin-top: -15px;">Back to Login</a>
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

<!-- Custom CSS for alert positioning -->
<style>
    #centered-alert-container {
        margin-bottom: 15px; /* Add some space between the alert and the form */
        text-align: center;
    }
</style>

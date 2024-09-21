<?php
include_once __DIR__ . '/header.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "Invalid token!";
    exit();
}

// Retrieve success and error messages from session
$successMessage = $_SESSION['success'] ?? '';
$errorMessage = $_SESSION['error'] ?? [];
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/pass.jpg');">
            <div class="container">
                <h1 class="pt-5">Reset Your Password</h1>
                <p class="lead">Enter and confirm your new password below.</p>
                <div class="card card-login mb-5">
                    <div class="card-body">
                        <!-- Alert Container placed at the top of card body -->
                        <div id="centered-alert-container">
                            <?php if (!empty($successMessage)): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= $successMessage ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['success']); // Clear success message after displaying ?>
                            <?php endif; ?>

                            <?php if (!empty($errorMessage)): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php
                                    // Check if the error message is an array, and if so, implode it into a string
                                    if (is_array($errorMessage)) {
                                        echo implode('<br>', $errorMessage); // Convert array elements into a string with <br> as separator
                                    } else {
                                        echo $errorMessage; // If it's a string, display it as-is
                                    }
                                    ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['error']); // Clear error message after displaying ?>
                            <?php endif; ?>
                        </div>

                        <!-- Form starts here -->
                        <form class="form-horizontal" method="POST" action="/IPass/app/controllers/PassRecoveryController.php?action=reset&token=<?php echo $_GET['token']; ?>">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="new_password" id="new_password" required placeholder="Enter new password">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm new password">
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Reset Password</button>
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <a href="http://localhost/IPass/app/views/Customer/Login.php" class="btn btn-secondary btn-block text-uppercase">Back to Login</a>
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

<!-- Remove the fixed position of the alert container -->
<style>
    #centered-alert-container {
        margin-bottom: 15px; /* Space between the alert and the form */
    }
</style>

<?php
require_once __DIR__ . '/../../../facades/UserFacade.php';
require_once __DIR__ . '/../../../core/SessionManager.php';

SessionManager::startSession();
SessionManager::requireLogin();

// Fetch customer details from the session
$customerDetails = $_SESSION['customer'] ?? null;

if (!$customerDetails) {
    echo "No customer details found in session.";
    exit();
}

// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['error'] ?? [];
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after rendering
?>

<?php include_once __DIR__ . '/../../../../app/views/Admin/header.php'; ?>

<div id="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <div class="container-fluid">

                <!-- Display Success Message -->
                <?php if ($successMessage): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $successMessage ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Display Error Messages -->
                <?php if (!empty($errorMessages)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach ($errorMessages as $errorMessage): ?>
                            <p><?= $errorMessage ?></p>
                        <?php endforeach; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Page Heading -->
                <h1 class="h3 mb-4 text-gray-800">Customer Profile Details</h1>

                <div class="row">
                    <!-- Profile Image Card -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-body text-center">
                                <img class="img-fluid img-profile rounded-circle mb-4" style="height: 470px;" src="<?= isset($customerDetails['ProfileImage']) && !empty($customerDetails['ProfileImage']) ? ROOT . $customerDetails['ProfileImage'] : ROOT . '/assets/img/ProfileImage/default-profile.png' ?>" alt="Customer Image">
                                <h5><?= $customerDetails['Username'] ?></h5>
                                <p class="text-muted"><?= $customerDetails['Email'] ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Details Card -->
                    <div class="col-lg-8">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" value="<?= $customerDetails['Username'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="<?= $customerDetails['Email'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="customerName">Customer Name</label>
                                    <input type="text" class="form-control" id="customerName" value="<?= $customerDetails['CustomerName'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="phoneNumber">Phone Number</label>
                                    <input type="text" class="form-control" id="phoneNumber" value="<?= $customerDetails['PhoneNumber'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="birthday">Birthday</label>
                                    <input type="date" class="form-control" id="birthday" value="<?= $customerDetails['Birthday'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" disabled>
                                        <option value="m" <?= $customerDetails['Gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                                        <option value="f" <?= $customerDetails['Gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                                        <option value="o" <?= $customerDetails['Gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" value="<?= $customerDetails['Address'] ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="points">Points</label>
                                    <input type="text" class="form-control" id="points" value="<?= $customerDetails['Point'] ?>" readonly>
                                </div>

                                <a href="/IPass/app/views/Admin/User/displayCustomer.php" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../../../app/views/Admin/footer.php'; ?>

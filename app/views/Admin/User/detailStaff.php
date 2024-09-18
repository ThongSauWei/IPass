<?php
require_once __DIR__ . '/../../../facades/UserFacade.php';
require_once __DIR__ . '/../../../core/SessionManager.php';

SessionManager::startSession();
SessionManager::requireLogin();

// Fetch staff details from the session
$staffDetails = $_SESSION['staff'] ?? null;

if (!$staffDetails) {
    echo "No staff details found in session.";
    exit();
}

// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['error'] ?? [];
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after rendering
?>

<?php include_once __DIR__ . '/../../../../app/views/Admin/header.php'; ?>

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- Begin Page Content -->
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
                <h1 class="h3 mb-4 text-gray-800">Staff Profile Details</h1>

                <div class="row">
                    <!-- Profile Image Card -->
                    <div class="col-lg-4">
                        <div class="card shadow mb-4">
                            <div class="card-body text-center">
                                <img class="img-fluid img-profile rounded-circle mb-4" style="height: 470px;" src="<?= isset($staffDetails['ProfileImage']) ? ROOT . $staffDetails['ProfileImage'] : ROOT . '/assets/img/default-profile.png' ?>" alt="Staff Image">
                                <h5><?= isset($staffDetails['Username']) ? $staffDetails['Username'] : 'N/A' ?></h5>
                                <p class="text-muted"><?= isset($staffDetails['Email']) ? $staffDetails['Email'] : 'N/A' ?></p>
                                <p class="text-muted"><strong><?= isset($staffDetails['AdminRole']) ? ucfirst($staffDetails['AdminRole']) : 'N/A' ?></strong></p>
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
                                    <input type="text" class="form-control" id="username" name="username" value="<?= isset($staffDetails['Username']) ? $staffDetails['Username'] : 'N/A' ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= isset($staffDetails['Email']) ? $staffDetails['Email'] : 'N/A' ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="birthday">Birthday</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" value="<?= isset($staffDetails['Birthday']) ? $staffDetails['Birthday'] : '' ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender" disabled>
                                        <option value="m" <?= isset($staffDetails['Gender']) && $staffDetails['Gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                                        <option value="f" <?= isset($staffDetails['Gender']) && $staffDetails['Gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                                        <option value="o" <?= isset($staffDetails['Gender']) && $staffDetails['Gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="staffRole">Admin Role</label>
                                    <input type="text" class="form-control" id="staffRole" value="<?= isset($staffDetails['AdminRole']) ? $staffDetails['AdminRole'] : 'N/A' ?>" readonly>
                                </div>

                                <a href="/IPass/app/views/Admin/User/displayStaff.php" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../../../../app/views/Admin/footer.php'; ?>

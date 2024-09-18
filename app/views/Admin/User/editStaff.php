<?php
require_once __DIR__ . '/../../../core/SessionManager.php';
require_once __DIR__ . '/../../../facades/UserFacade.php';

SessionManager::startSession();
SessionManager::requireLogin();

$userFacade = new UserFacade();

// Fetch staff details using the staff ID passed in the URL
$staffID = $_GET['id'] ?? null;
if (!$staffID) {
    $_SESSION['error'] = "No staff member selected for editing.";
    header('Location: displayStaff.php'); // Redirect if no staff is selected
    exit();
}

$staffDetails = $userFacade->staffSelected($staffID); // Fetch the selected staff data

if (!$staffDetails) {
    $_SESSION['error'] = "Staff member not found.";
    header('Location: displayStaff.php'); // Redirect if no staff is found
    exit();
}

// Check for success or error messages
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']); // Clear the messages after rendering
// Set default profile image if not available
$profileImage = $staffDetails['ProfileImage'] ? ROOT . $staffDetails['ProfileImage'] : ROOT . '/assets/img/default-profile.png';
?>

<?php
include_once __DIR__ . '/../header.php';
?>

<body id="page-top">

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

                    <!-- Display Error Message -->
                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $errorMessage ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Edit Staff Profile</h1>

                    <div class="row">

                        <!-- Profile Image Card -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <img class="img-fluid img-profile rounded-circle mb-4" style="height: 470px;" src="<?= $profileImage ?>" alt="Staff Image">
                                    <h5><?= $staffDetails['Username'] ?></h5>
                                    <p class="text-muted"><?= $staffDetails['Email'] ?></p>
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
                                    <form action="/IPass/app/controllers/AdminController.php?action=updateStaffProfile" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="UserID" value="<?= $staffDetails['UserID'] ?>">

                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= $staffDetails['Username'] ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $staffDetails['Email'] ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="birthday">Birthday</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $staffDetails['Birthday'] ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="m" <?= $staffDetails['Gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                                                <option value="f" <?= $staffDetails['Gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                                                <option value="o" <?= $staffDetails['Gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="profileImage">Profile Image</label>
                                            <input type="file" class="form-control" id="profileImage" name="profileImage">
                                        </div>

                                        <button type="submit" class="btn btn-success">Update Profile</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>

            <?php
            include_once __DIR__ . '/../footer.php';
            ?>
        </div>
    </div>
</body>
</html>

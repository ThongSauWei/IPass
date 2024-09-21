<?php
require_once __DIR__ . '/../../core/SessionManager.php';
require_once __DIR__ . '/../../facades/userFacade.php';

SessionManager::startSession();
SessionManager::requireLogin();

$user = SessionManager::getUser();
$userFacade = new UserFacade();
$userID = $user['UserID'];
$profileImage = $userFacade->getUserProfileImage($userID);
$adminDetails = $userFacade->getUserDetails($userID);
$adminRole = $userFacade->getAdminRoleByUserID($userID); // Fetch AdminRole for admin

$profileImage = $profileImage ? ROOT . $profileImage : ROOT . '/assets/img/default-profile.png';

// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['error'] ?? [];  // Assume $_SESSION['error'] is an array
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after rendering
?>

<?php
include_once __DIR__ . '/header.php';
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
                    <h1 class="h3 mb-4 text-gray-800">Admin Profile</h1>

                    <div class="row">

                        <!-- Profile Image Card -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <img class="img-fluid img-profile rounded-circle mb-4" style="height: 470px;" src="<?= $profileImage ?>" alt="Admin Image">
                                    <h5><?= $adminDetails['Username'] ?></h5>
                                    <p class="text-muted"><?= $adminDetails['Email'] ?></p>
                                    <p class="text-muted"><strong>Admin Role:</strong> <?= ucfirst($adminRole) ?></p>
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
                                    <form action="/IPass/app/controllers/ProfileController.php?action=updateAdminProfile" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?= $adminDetails['Username'] ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $adminDetails['Email'] ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="birthday">Birthday</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday" value="<?= $adminDetails['Birthday'] ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="m" <?= $adminDetails['Gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                                                <option value="f" <?= $adminDetails['Gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                                                <option value="o" <?= $adminDetails['Gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="adminRole">Admin Role</label>
                                            <input type="text" class="form-control" id="adminRole" value="<?= $adminRole ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="profileImage">Profile Image</label>
                                            <input type="file" class="form-control" id="profileImage" name="profileImage">
                                        </div>
                                        <a href="/IPass/app/views/Admin/dashboard.view.php" class="btn btn-secondary mr-2" style="width: 8rem;" >Back</a>
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
            include_once __DIR__ . '/footer.php';
            ?>

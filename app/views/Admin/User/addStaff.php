<?php
require_once __DIR__ . '/../../../core/SessionManager.php';
require_once __DIR__ . '/../../../facades/UserFacade.php';

SessionManager::startSession();
SessionManager::requireLogin();



// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['error'] ?? [];
unset($_SESSION['success'], $_SESSION['error']); // Clear messages after rendering
?>

<?php include_once __DIR__ . '/../../../../app/views/Admin/header.php'; ?>

<body id="page-top">
    <div id="wrapper">
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

                    <h1 class="h3 mb-4 text-gray-800">Add Staff</h1>

                    <div class="row">
                        <!-- Profile Image Preview Card -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-body text-center">
                                    <img id="profileImagePreview" class="img-fluid img-profile rounded-circle mb-4" style="height: 470px;" src="<?= ROOT . '/assets/img/ProfileImage/default-profile.png' ?>" alt="Staff Image Preview">
                                </div>
                            </div>
                        </div>

                        <!-- Add Staff Form -->
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Staff Information</h6>
                                </div>
                                <div class="card-body">
                                    <form action="/IPass/app/controllers/AdminController.php?action=addStaff" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>                                        

                                        <div class="form-group">
                                            <label for="birthday">Birthday</label>
                                            <input type="date" class="form-control" id="birthday" name="birthday" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control" id="gender" name="gender">
                                                <option value="m">Male</option>
                                                <option value="f">Female</option>
                                                <option value="o">Other</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirmPass">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirmPass" name="confirmPass" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="profileImage">Profile Image</label>
                                            <input type="file" class="form-control" id="profileImage" name="profileImage">
                                        </div>

                                        <a href="/IPass/app/views/Admin/User/displayStaff.php" class="btn btn-secondary" style="width: 8rem;">Back</a>
                                        <button type="submit" class="btn btn-success">Add Staff</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../../../app/views/Admin/footer.php'; ?>

    <!-- JavaScript to preview image before uploading -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('profileImagePreview');
                output.src = reader.result; // Set the preview to the selected image
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        document.getElementById('profileImage').addEventListener('change', previewImage);
    </script>

<?php
include_once __DIR__ . '/header.php';

// Fetch user and customer details from the database
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../core/SessionManager.php';

SessionManager::requireLogin();
// Start session and get logged-in user details
SessionManager::startSession();
$user = SessionManager::getUser();

if ($user) {
    $userID = $user['UserID'];
    $userFacade = new UserFacade();

    // Get customer details
    $customerModel = new Customer();
    $customerDetails = $userFacade->getCustomerDetails($userID);

    // Get user details
    $profileImage = $userFacade->getUserProfileImage($userID);
    $email = $user['Email'] ?? 'No email found';
    $username = $user['Username'];
    $birthday = $userFacade->getUserBirthday($userID);
    $gender = $userFacade->getUserGender($userID);

    $customerName = $customerDetails['CustomerName'] ?? 'Guest';
    $phone = $customerDetails['PhoneNumber'] ?? '';
    $address = $customerDetails['Address'] ?? '';
    $points = $customerDetails['Points'] ?? 0;

    $profileImage = $profileImage ? ROOT . $profileImage : ROOT . '/assets/img/logo/avatar.jpg';

    if (!empty($birthday)) {
        $birthday = date('Y-m-d', strtotime($birthday));
    }
} else {
    header('Location: login.php');
    exit();
}

// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessages = $_SESSION['error'] ?? [];
unset($_SESSION['success'], $_SESSION['error']);
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Profile</h1>
                <p class="lead">Update Your Account Info</p>
            </div>
        </div>
    </div>

    <section id="profile">
        <div class="container">

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
                    <?php foreach ($errorMessages as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6 text-center">
                    <!-- Profile Image Section -->
                    <div class="profile-image text-center">
                        <label for="profileImage" style="cursor: pointer; border-radius: 50%; width: 156px; height: 156px; object-fit: cover; border: 3px solid #ccc;">
                            <img id="profileImagePreview" src="<?= $profileImage ?>" alt="User Image" class="img-circle profile-image-upload" width="150" height="150" style="border-radius: 50%; border: 2px solid #e41d61;">
                            <small class="d-block mt-2" style="color:#001dff;">Click the image to change</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6">
                    <h5 class="mb-3" style="margin-top: 10px;">ACCOUNT DETAILS</h5>
                    <form action="/IPass/app/controllers/ProfileController.php?action=update" method=POST enctype="multipart/form-data">
                        <fieldset>
                            <!-- Profile Image Upload -->
                            <input type="file" name="profileImage" id="profileImage" accept="image/*" style="display: none;" onchange="previewImage(event)">

                            <!-- Name -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="name">Name:</label>
                                    <input class="form-control" placeholder="Full Name" name="fullname" type="text" value="<?= $customerName ?>" required>
                                </div>
                            </div>

                            <!-- Email (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="email">Email:</label>
                                    <input class="form-control" placeholder="Email Address" type="email" value="<?= $email ?>" readonly>
                                </div>
                            </div>

                            <!-- Username (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="username">Username:</label>
                                    <input class="form-control" placeholder="Username" type="text" value="<?= $username ?>" readonly>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="phone">Phone Number:</label>
                                    <input class="form-control" placeholder="Phone Number" name="phone" type="tel" value="<?= $phone ?>" required>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea class="form-control" name="address" placeholder="Address"><?= $address ?></textarea>
                            </div>

                            <!-- Gender -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="gender">Gender:</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="m" <?= ($gender === 'm') ? 'selected' : '' ?>>Male</option>
                                        <option value="f" <?= ($gender === 'f') ? 'selected' : '' ?>>Female</option>
                                        <option value="o" <?= ($gender === 'o') ? 'selected' : '' ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Birthday -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="date">Birthday:</label>
                                    <input class="form-control" type="date" name="birthday" id="birthday" value="<?= $birthday ?>" required>
                                </div>
                            </div>

                            <!-- Points (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="point">Points:</label>
                                    <input class="form-control" placeholder="Points" type="text" value="<?= $points ?>" readonly>
                                </div>
                            </div>

                            <!-- Update Button -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Preview the image before uploading
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

<?php
include_once __DIR__ . '/footer.php';
?>

<?php
include_once __DIR__ . '/header.php';

// Fetch user and customer details from the database
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../core/SessionManager.php';

SessionManager::requireLogin();
// Start session and get logged-in user details
SessionManager::startSession();
$user = SessionManager::getUser();

// Check if user is logged in
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
    $birthday = $userFacade->getUserBirthday($userID); // Assuming the birthday is stored in the 'user' table
    $gender = $userFacade->getUserGender($userID); // Assuming gender is stored in the 'user' table
    // Get ustomer details
    $customerName = $customerDetails['CustomerName'] ?? 'Guest';
    $phone = $customerDetails['PhoneNumber'] ?? '';
    $address = $customerDetails['Address'] ?? '';
    $points = $customerDetails['Points'] ?? 0;

    $profileImage = $profileImage ? ROOT . $profileImage : ROOT . '/assets/img/logo/avatar.jpg';

    if (!empty($birthday)) {
        // Format the birthday to 'd-m-Y H:i:sa' format
        $formattedBirthday = date("d-m-Y", strtotime($birthday));
    } else {
        // Convert the birthday to 'YYYY-MM-DD' format if it's not
        $birthday = date('Y-m-d', strtotime($birthday));
    }
} else {
    // Redirect if not logged in
    header('Location: login.php');
    exit();
}
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
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6 text-center">
                    <!-- Profile Image Section -->
                    <div class="profile-image text-center">
                        <!-- Profile Image with click to change functionality -->
                        <label for="profileImage" style="cursor: pointer; border-radius: 50%; width: 150px; height: 150px; object-fit: cover; border: 3px solid #ccc;">
                            <img id="profileImagePreview" src="<?= $profileImage ?>" alt="User Image" class="img-circle profile-image-upload" width="150" height="150" style="border-radius: 50%; border: 2px solid #e41d61; margin-left: -3px; margin-top: -2px;">
                            <small class="d-block mt-2" style="color:#001dff;">Click the image to change</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6">
                    <h5 class="mb-3">ACCOUNT DETAILS</h5>
                    <form action="/IPass/app/controllers/ProfileController.php?action=update" method=POST enctype="multipart/form-data">
                        <fieldset>
                            <!-- Profile Image Upload -->
                            <input type="file" name="profileImage" id="profileImage" accept="image/*" style="display: none;" onchange="previewImage(event)">

                            <!-- Name -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="name" style="margin-bottom:0px !important;">Name:</label>
                                    <input class="form-control" placeholder="Full Name" name="fullname" type="text" value="<?= $customerName ?>" required>
                                </div>
                            </div>

                            <!-- Email (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="email" style="margin-bottom:0px !important;">Email:</label>
                                    <input class="form-control" placeholder="Email Address" type="email" value="<?= $email ?>" readonly>
                                </div>
                            </div>

                            <!-- Username (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="username" style="margin-bottom:0px !important;">Username:</label>
                                    <input class="form-control" placeholder="Username" type="text" value="<?= $username ?>" readonly>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="phone" style="margin-bottom:0px !important;">Phone Number:</label>
                                    <input class="form-control" placeholder="Phone Number" name="phone" type="tel" value="<?= $phone ?>" required>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <label for="address" style="margin-bottom:0px !important;">Address:</label>
                                <textarea class="form-control" name="address" placeholder="Address"><?= $address ?></textarea>
                            </div>

                            <!-- Gender -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="gender" style="margin-bottom:0px !important;">Gender:</label>
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
                                    <label for="date" style="margin-bottom:0px !important;">Birthday:</label>
                                    <input class="form-control" type="date" name="birthday" id="birthday" value="<?= $birthday ?>" required>
                                </div>
                            </div>

                            <!-- Points (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <label for="point" style="margin-bottom:0px !important;">Point:</label>
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

<!-- Display success message if available -->
<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <script>
        alert("<?= $_SESSION['success'] ?>"); // Show success message using JavaScript alert
    </script>
    <?php unset($_SESSION['success']); // Clear the success message after displaying   ?>
<?php endif; ?>

<!-- Display error messages as alerts -->
<?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
    <script>
        let errors = <?php echo json_encode($_SESSION['errors']); ?>;
        errors.forEach(function (error) {
            alert(error); // show each error using a JavaScript alert
        });
    </script>
    <?php unset($_SESSION['errors']); // clear errors after displaying  ?>
<?php endif; ?>

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
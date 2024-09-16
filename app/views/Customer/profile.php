<?php
include_once __DIR__ . '/header.php';

// Fetch user and customer details from the database
require_once __DIR__ . '/../../models/Customer.php';
require_once __DIR__ . '/../../core/SessionManager.php';

// Start session and get logged-in user details
SessionManager::startSession();
$user = SessionManager::getUser();

// Check if user is logged in
if ($user) {
    $userID = $user['UserID'];

    // Get customer details
    $customerModel = new Customer();
    $customerDetails = $customerModel->findCustByUserID($userID);

    // Get the profile image from the 'user' table
    $profileImage = $user['ProfileImage'] ?? ROOT . '/assets/img/logo/avatar.jpg'; // Default avatar
    // Get other user details
    $customerName = $customerDetails[0]['CustomerName'] ?? 'Guest';
    $email = $user['Email'];
    $username = $user['Username'];
    $phone = $customerDetails[0]['PhoneNumber'] ?? '';
    $address = $customerDetails[0]['Address'] ?? '';
    $points = $customerDetails[0]['Point'] ?? 0;
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
                <h1 class="pt-5">
                    Profile
                </h1>
                <p class="lead">
                    Update Your Account Info
                </p>
            </div>
        </div>
    </div>

    <section id="profile">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6 text-center">
                    <!-- Profile Image Section -->
                    <div class="profile-image">
                        <img src="<?= $profileImage ?>" alt="User Image" class="img-circle" width="150" height="150">
                        <form action="upload_profile_image.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="profileImage" accept="image/*">
                            <button type="submit" class="btn btn-secondary mt-2">Change Image</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6">
                    <h5 class="mb-3">ACCOUNT DETAILS</h5>
                    <form action="update_profile.php" method="post">
                        <fieldset>
                            <!-- Name -->
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Name" name="fullname" type="text" value="<?= $customerName ?>" required>
                                </div>
                            </div>

                            <!-- Email (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Email Address" type="email" value="<?= $email ?>" readonly>
                                </div>
                            </div>

                            <!-- Username (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Username" type="text" value="<?= $username ?>" readonly>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Phone Number" name="phone" type="tel" value="<?= $phone ?>" required>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="form-group">
                                <textarea class="form-control" name="address" placeholder="Address"><?= $address ?></textarea>
                            </div>

                            <!-- Points (readonly) -->
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Points" type="text" value="<?= $points ?>" readonly>
                                </div>
                            </div>

                            <!-- Update Button -->
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                                <div class="clearfix"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include_once __DIR__ . '/footer.php';
?>


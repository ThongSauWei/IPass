<?php
require_once __DIR__ . '/../../../facades/UserFacade.php';
require_once __DIR__ . '/../../../core/SessionManager.php';

SessionManager::startSession();

$userFacade = new UserFacade();

//get all the staff
$staffMembers = $userFacade->getAllStaff(); // Assumes this method exists and fetches the staff
// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']); // Ensure the messages are cleared after rendering
?>


<?php
include_once __DIR__ . '/../header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Staff Management</h1>
    <p class="mb-4">Below is the list of all staff members with their respective details.</p>

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
    <?php endif; ?>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Staff Members</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>UserID</th>
                            <th>AdminID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>UserID</th>
                            <th>AdminID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($staffMembers)) : ?>
                            <?php foreach ($staffMembers as $staff) : ?>
                                <tr>
                                    <td><?= $staff['UserID'] ?></td>
                                    <td><?= $staff['AdminID'] ?></td>
                                    <td><?= isset($staff['Username']) ? $staff['Username'] : 'N/A' ?></td>
                                    <td><?= isset($staff['Email']) ? $staff['Email'] : 'N/A' ?></td>
                                    <td><?= !empty($staff['Birthday']) ? date('d-m-Y', strtotime($staff['Birthday'])) : 'N/A' ?></td>
                                    <td><?= isset($staff['Gender']) ? ($staff['Gender'] === 'm' ? 'Male' : ($staff['Gender'] === 'f' ? 'Female' : 'Other')) : 'Other' ?></td>
                                    <td>
                                        <div class="col-md-12 justify-content-between align-items-center" style="margin-right: -18px;">
                                            <a href="AdminController.php?action=editStaff&id=<?= $staff['UserID'] ?>" class="btn btn-primary btn-sm" style="padding-left: 36px; padding-right: 36px;" >
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="/IPass/app/controllers/AdminController.php?action=deleteStaff&id=<?= $staff['UserID'] ?>" class="btn btn-danger btn-sm" style="padding-left: 30px; padding-right: 30px; margin-left: 5px;" 
                                               onclick="return confirm('Are you sure you want to delete this staff?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">No staff members found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once __DIR__ . '/../../../../app/views/Admin/footer.php';
?>

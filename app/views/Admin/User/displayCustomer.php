<?php
include_once __DIR__ . '/../header.php'; ?>

<?php
require_once __DIR__ . '/../../../core/NewModel.php'; // Or correct path to your database interaction
require_once __DIR__ . '/../../../facades/UserFacade.php';

// Initialize the UserFacade
$userFacade = new UserFacade();

// Fetch all staff members (AdminRole = 'staff')
$staffMembers = $userFacade->getAllStaff(); // Assumes this method exists and fetches the staff

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Staff Management</h1>
    <p class="mb-4">Below is the list of all staff members with their respective details.</p>

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
                                    <td><?= $staff['Username'] ?></td>
                                    <td><?= $staff['Email'] ?></td>
                                    <td><?= !empty($staff['Birthday']) ? date('d-m-Y', strtotime($staff['Birthday'])) : 'N/A' ?></td>
                                    <td><?= $staff['Gender'] === 'm' ? 'Male' : ($staff['Gender'] === 'f' ? 'Female' : 'Other') ?></td>
                                    <td>
                                        <a href="edit_staff.php?id=<?= $staff['UserID'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                        <a href="delete_staff.php?id=<?= $staff['UserID'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff?')"><i class="fas fa-trash"></i> Delete</a>
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

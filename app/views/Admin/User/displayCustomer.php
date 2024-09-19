<?php
require_once __DIR__ . '/../../../facades/UserFacade.php';
require_once __DIR__ . '/../../../core/SessionManager.php';

SessionManager::startSession();

$userFacade = new UserFacade();

// Get all the customers
$customers = $userFacade->getAllCustomers(); // Fetches customer details from facade
// Display success or error message
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']); // Ensure the messages are cleared after rendering
?>


<?php
include_once __DIR__ . '/../../../../app/views/Admin/header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Customer Management</h1>
    <p class="mb-4">Below is the list of all customers with their respective details.</p>

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
            <h6 class="m-0 font-weight-bold text-primary">Customers</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>UserID</th>
                            <th>CustomerID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Point</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>UserID</th>
                            <th>CustomerID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Customer Name</th>
                            <th>Phone Number</th>
                            <th>Birthday</th>
                            <th>Gender</th>
                            <th>Point</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($customers)) : ?>
                            <?php foreach ($customers as $customer) : ?>
                                <tr>
                                    <td><?= $customer['UserID'] ?></td>
                                    <td><?= $customer['CustomerID'] ?></td>
                                    <td><?= isset($customer['Username']) ? $customer['Username'] : 'N/A' ?></td>
                                    <td><?= isset($customer['Email']) ? $customer['Email'] : 'N/A' ?></td>
                                    <td><?= isset($customer['CustomerName']) ? $customer['CustomerName'] : 'N/A' ?></td>
                                    <td><?= isset($customer['PhoneNumber']) ? $customer['PhoneNumber'] : 'N/A' ?></td>
                                    <td><?= !empty($customer['Birthday']) ? date('d-m-Y', strtotime($customer['Birthday'])) : 'N/A' ?></td>
                                    <td><?= isset($customer['Gender']) ? ($customer['Gender'] === 'm' ? 'Male' : ($customer['Gender'] === 'f' ? 'Female' : 'Other')) : 'Other' ?></td>
                                    <td><?= isset($customer['Point']) ? $customer['Point'] : '0' ?></td>
                                    <td>
                                        <div class="col-md-12 d-flex align-items-center">
                                            <a href="<?= ROOT ?>/../app/controllers/CustomerController.php?action=detailCustomer&id=<?= urlencode($customer['UserID']) ?>" class="btn btn-primary btn-sm" style="padding-left: 36px; padding-right: 36px;">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            <a href="<?= ROOT ?>/../app/controllers/CustomerController.php?action=deleteCustomer&id=<?= urlencode($customer['UserID']) ?>"  class="btn btn-danger btn-sm" style="padding-left: 30px; padding-right: 30px; margin-left: 5px;" 
                                               onclick="return confirm('Are you sure you want to delete this customer?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                        <!-- Toggle switch for activating/deactivating the user -->
                                        <form action="<?= ROOT ?>/../app/controllers/CustomerController.php?action=toggleStatus&id=<?= urlencode($customer['UserID']) ?>" method="POST" style="display:inline;">
                                            <div class="custom-control custom-switch" style="margin-left: 12px;" >
                                                <input type="checkbox" class="custom-control-input" id="switch<?= $customer['UserID'] ?>" 
                                                       <?= isset($customer['isActive']) && $customer['isActive'] == 1 ? 'checked' : '' ?> onchange="this.form.submit()">
                                                <label class="custom-control-label" for="switch<?= $customer['UserID'] ?>">
                                                    <?= isset($customer['isActive']) && $customer['isActive'] == 1 ? 'Active' : 'Inactive' ?>
                                                </label>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center">No customers found.</td>
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
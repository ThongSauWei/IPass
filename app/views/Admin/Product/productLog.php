<?php
include_once __DIR__ . '/../../../../app/views/Admin/header.php';
require_once __DIR__ . '/../../../controllers/ProductController.php';

$productController = new ProductController();
$logs = $productController->getTransactionLogs();

// Handle operation filter from dropdown (default is 'All')
$operationFilter = isset($_GET['operation']) ? $_GET['operation'] : 'All';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Product Transaction Log</h1>

    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex">
            <form method="GET" action="" class="form-inline">
                <div class="form-group mb-2">
                    <label for="operationFilter" class="sr-only">Filter by Operation:</label>
                    <select name="operation" id="operationFilter" class="form-control" onchange="this.form.submit()">
                        <option value="All" <?php echo $operationFilter == 'All' ? 'selected' : ''; ?>>All</option>
                        <option value="Add" <?php echo $operationFilter == 'Add' ? 'selected' : ''; ?>>Add</option>
                        <option value="Update" <?php echo $operationFilter == 'Update' ? 'selected' : ''; ?>>Update</option>
                        <option value="Delete" <?php echo $operationFilter == 'Delete' ? 'selected' : ''; ?>>Delete</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transaction Log</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                Operation
                                <!-- Add reverse icon with a link to toggle sorting -->
                                <a href="?sort=<?php echo isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'asc' : 'desc'; ?>">
                                    <i class="fa <?php echo isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'fa-arrow-up' : 'fa-arrow-down'; ?>" aria-hidden="true"></i>
                                </a>
                            </th>
                            <th>Transaction Status</th>
                            <th>Event</th>
                            <th>Event Time</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Operation</th>
                            <th>Transaction Status</th>
                            <th>Event</th>
                            <th>Event Time</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php
                            // Check for sorting order from the query parameter
                            if (isset($_GET['sort']) && $_GET['sort'] == 'desc') {
                                $logs = array_reverse($logs);
                            }

                            // Filter logs by selected operation
                            if ($operationFilter !== 'All') {
                                $logs = array_filter($logs, function($log) use ($operationFilter) {
                                    return strpos($log, "Operation: $operationFilter") !== false;
                                });
                            }
                            ?>

                            <?php if (!empty($logs)): ?>
                                <?php foreach ($logs as $log): ?>
                                    <?php
                                    // Initialize variables to avoid undefined index errors
                                    $operation = $status = $event = $eventTime = 'Unknown';

                                    // Attempt to parse the log entry
                                    if (preg_match('/\[(.*?)\] Operation: (.*?), Status: (.*?), Event: (.*)/', $log, $matches)) {
                                        $eventTime = $matches[1];
                                        $operation = $matches[2];
                                        $status = $matches[3];
                                        $event = $matches[4];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($operation); ?></td>
                                        <td>
                                            <?php
                                            // Determine the color class based on the status
                                            $statusClass = '';
                                            if ($status === 'Success') {
                                                $statusClass = 'text-success'; // Green for success
                                            } elseif ($status === 'Info') {
                                                $statusClass = 'text-info'; // Blue for info
                                            } elseif ($status === 'Error') {
                                                $statusClass = 'text-danger'; // Red for error
                                            }
                                            ?>
                                            <span class="<?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>

                                        <td><?php echo htmlspecialchars($event); ?></td>
                                        <td><?php echo htmlspecialchars($eventTime); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No records found for the selected operation.</td>
                                </tr>
                            <?php endif; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No transaction log found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<?php
include_once __DIR__ . '/../../../../app/views/Admin/footer.php';
?>

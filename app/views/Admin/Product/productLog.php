<?php
include_once __DIR__ . '/../../../../app/views/Admin/header.php';
require_once __DIR__ . '/../../../controllers/ProductController.php';

$productController = new ProductController();
$logs = $productController->getTransactionLogs();
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Product Transaction Log</h1>

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
                            <th>Operation</th>
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
                                    <td><?php echo htmlspecialchars($status); ?></td>
                                    <td><?php echo htmlspecialchars($event); ?></td>
                                    <td><?php echo htmlspecialchars($eventTime); ?></td>
                                </tr>
                            <?php endforeach; ?>
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

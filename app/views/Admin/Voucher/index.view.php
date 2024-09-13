<?php
include_once __DIR__ . '/../header.php';

?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Add Voucher Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Vouchers</h6>
        </div>
        <div class="card-body">
    <!-- Vouchers Table -->
    <div class="table-responsive">
        <table id="voucherTable" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Voucher Code</th>

                <th>Discount Type</th>
                <th>Discount Value</th>
                <th>Point Required</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <?php if (!empty($data)): ?>
                <?php foreach ($data['vouchers'] as $voucher): ?>
                    <tr>
                        <td><?= htmlspecialchars($voucher->VoucherCode) ?></td>
                        <td><?= htmlspecialchars($voucher->VoucherType) ?></td>
                        <td><?= htmlspecialchars($voucher->Value) ?></td>
                        <td><?= htmlspecialchars($voucher->PointRequired) ?></td>
                        <td><?= htmlspecialchars($voucher->Status) ?></td>
                        <td><?= htmlspecialchars($voucher->StartDate) ?></td>
                        <td><?= htmlspecialchars($voucher->EndDate) ?></td>
                        <td>
                            <a href="<?= $voucher->VoucherID ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="<?=ROOT?>/VoucherController/delete/<?= $voucher->VoucherID ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this voucher?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No vouchers found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
<!-- /.container-fluid -->

</div>

</div>
</div>
<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#voucherTable').DataTable();
    });
</script>

<?php
include_once __DIR__ . '/../footer.php';
?>

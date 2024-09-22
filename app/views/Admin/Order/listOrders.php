<?php 
require_once __DIR__ . '/../header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Order Management</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Purchased</th>
                            <th>Discount</th>
                            <th>Delivery</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orderList)): ?>
                        <?php foreach ($orderList as $order): ?>
                        <tr onclick="window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=viewOrder&orderID=<?= $order["OrderID"] ?>'">
                            <td><?= $order["OrderID"] ?></td>
                            <td><?= $order["CustomerName"] ?></td>
                            <td>RM <?= $order["PurchasedAmt"] ?></td>
                            <td>RM <?= $order["Discount"] ?></td>
                            <td>RM <?= $order["DeliveryFee"] ?></td>
                            <td>RM <?= number_format($order["PurchasedAmt"] - $order["Discount"] + $order["DeliveryFee"], 2) ?></td>
                            <td><?= $order["OrderDate"] ?></td>
                            <td><?= $order["Status"] ?></td>
                            <td><?= $order["PaymentType"] ?></td>
                            <td>
                                <button class="btn btn-outline-danger" onclick="showDialog(event)">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan='10' class='text-center'>No order records found.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>     
    </div>
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body delete">
                    Are you sure you want to delete this order?
                </div>
                <div class="modal-footer">
                    <button id='<?= $order["OrderID"] ?>' type='button' class='btn btn-danger' onclick="clickDelete(this)">Sure</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeDialog()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
    function showDialog(event) {
        event.stopPropagation();
        
        $('#notificationModal').modal('show');
    }
    
    function closeDialog() {
        $('#notificationModal').modal('hide');
    }
    
    function clickDelete(element) {
        
        window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=deleteOrder&orderID=' + element.id;
    }
</script>
<?php
include_once '../views/Admin/footer.php';
?>

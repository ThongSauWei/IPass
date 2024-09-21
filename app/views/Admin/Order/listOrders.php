<?php 
require_once '../views/Admin/header.php';
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
                    <?php if (!empty($orderList)): ?>
                    <tbody>
                        <?php foreach ($orderList as $order): ?>
                        <tr onclick="window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=viewOrder&orderID=<?= $order["OrderID"] ?>'">
                            <td><?= $order["OrderID"] ?></td>
                            <td><?= $order["CustomerID"] ?></td>
                            <td>RM <?= $order["PurchasedAmt"] ?></td>
                            <td>RM <?= $order["Discount"] ?></td>
                            <td>RM <?= $order["DeliveryFee"] ?></td>
                            <td>RM <?= number_format($order["PurchasedAmt"] - $order["Discount"] + $order["DeliveryFee"], 2) ?></td>
                            <td><?= $order["OrderDate"] ?></td>
                            <td><?= $order["Status"] ?></td>
                            <td><?= $order["PaymentType"] ?></td>
                            <td>
                                <button class="btn btn-outline-warning" onclick="window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=editOrder&orderID=<?= $order["OrderID"] ?>'">Edit</button>
                                <button class="btn btn-outline-danger" onclick="window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=deleteOrder&productID=<?= $oder["OrderID"] ?>'">Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<?php
include_once '../views/Admin/footer.php';
?>

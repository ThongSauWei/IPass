<?php
require_once '../views/Admin/header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Order Management</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">View Order</h6>
        </div>
        <div class="card-body">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order ID : <?= $order['OrderID'] ?></h5>
                    <button onclick="backToOrderListPage()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p id="billing-details">
                                <strong>Billing Detail:</strong><br>
                                <?php 
                                $address = explode(',', $order["DeliveryAddress"]);
                                foreach($address as $line) {
                                    echo "$line<br>";
                                } 
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p id="payment-method">
                                <strong>Payment Method:</strong><br>
                                <?= $order["PaymentType"] ?>
                            </p>
                            <p id="payment-date">
                                <strong>Order Date</strong><br>
                                <?= $order["OrderDate"] ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <strong>Order:</strong>
                            </p>
                            <div class="table-responsive">
                                <table class="table" id="order-details">
                                    <thead>
                                        <tr>
                                            <th>Products</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <?php if (!empty($orderDetails)): ?>
                                    <tbody>
                                        <?php foreach ($orderDetails as $item): ?>
                                        <tr>
                                            <td><?= $item["ProductName"] ?> x<?= $item["Quantity"] ?></td>
                                            <td class="text-right">RM <?= number_format($item["Quantity"] * $item["UnitPrice"] - $item["Discount"], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <?php endif; ?>
                                    <tfoot>
                                        <tr>
                                            <td><strong>Cart Subtotal</strong></td>
                                            <td class="text-right">RM<?= number_format($order["PurchasedAmt"] - $order["Discount"], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Shipping</strong></td>
                                            <td class="text-right">RM <?= number_format($order["DeliveryFee"], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>ORDER TOTAL</strong></td>
                                            <td class="text-right"><strong>RM <?= number_format($order["PurchasedAmt"] - $order["Discount"] + $order["DeliveryFee"], 2) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between; width: 100%;">
                    <div style="display: flex; gap: 20px;">
                        <button type="button" class="btn btn-danger" value="Cancel">Cancel Order</button>
                        <button type="button" class="btn btn-primary" value="Deliver">Deliver Order</button>
                        <button type="button" class="btn btn-info" value="Complete">Complete Order</button>
                    </div>
                    <button onclick="backToOrderListPage()" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                    <div class="modal-body notification">
                        <!-- Message content will go here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function backToOrderListPage() {
        window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=listOrders';
    }
    
    function handleOrder(element) {
        let orderID = <?= $order["OrderID"] ?>
        const action = element.value;
    
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "OrderController.php?action=handleOrder", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('.modal-body.notification').innerText = xhr.responseText;
                $('#notificationModal').modal('show');
            }
        };

        xhr.send(JSON.stringify({ orderID, action }));
    }
</script>


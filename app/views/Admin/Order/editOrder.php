<?php
require_once '../views/Admin/header.php';
?>
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Order Management</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Order</h6>
        </div>
        <div class="card-body">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Order ID : <?= $order['OrderID'] ?></h5>
                    <h5 class='model-title' id='customerLabel'>Customer : <?= $order['CustomerName'] . " (" . $order['CustomerID'] . ")" ?></h5>
                    <button onclick="backToOrderListPage()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p id="purchased-amount">
                                <strong>Purchased Amount:</strong><br>
                                <input type='text' name='purchasedAmount' id='purchasedAmount' value='<?= $order["PurchasedAmt"] ?>' />
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p id="discount">
                                <strong>Discount:</strong><br>
                                <input type='text' name='discount' id='discount-input' value='<?= $order["Discount"] ?>' />
                            </p>
                            <p id='delivery-fee'>
                                <strong>Delivery Fee:</strong><br>
                                <input type='text' name='deliveryFee' id='deliveryFee' value='<?= $order["DeliveryFee"] ?>' />
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p id="order-date">
                                <strong>Order Date:</strong><br>
                                <input type='date' name='orderDate' id='orderDate' value='<?= $order["OrderDate"] ?>' />
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p id="status">
                                <strong>Status:</strong><br>
                                <input type='text' name='status' id='status-input' value='<?= $order["Status"] ?>' disabled /><br />
                                <small class='text-danger'>*Status should be managed at <a href='http://localhost/IPass/app/controllers/OrderController.php?action=viewOrder&orderID=<?= $order['OrderID'] ?>'>View Order</a></small>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p id="billing-details">
                                <strong>Billing Detail:</strong><br>
                                <input type='text' name='deliveryAddress' id='deliveryAddress' value='<?= $order["DeliveryAddress"] ?>' />
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p id="payment-method">
                                <strong>Payment Method:</strong><br>
                                <input type='text' name='paymentMethod' id='paymentMethod' value='<?= $order["PaymentType"] ?>' />
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
                <div class="modal-footer">
                    <button onclick='saveOrder()' type='button' class='btn btn-success'>Save</button>
                    <button onclick="backToOrderListPage()" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function backToOrderListPage() {
        window.location.href='http://localhost/IPass/app/controllers/OrderController.php?action=listOrders';
    }
    
    function saveOrder() {
        let orderID = '<?= $order["OrderID"] ?>';
        
        const purchasedAmount = document.querySelector('#purchasedAmount').value.trim();
        const discount = document.querySelector('#discount-input').value.trim();
        const deliveryFee = document.querySelector('#deliveryFee').value.trim();
        const orderDate = document.querySelector('#orderDate').value.trim();
        const address = document.querySelector('#deliveryAddress').value.trim();
        const paymentMethod = document.querySelector('#paymentMethod').value.trim();
        
        if (!name) {
            showError("Please enter your name");
            return false;
        }
        
        if (!lastName) {
            showError("Please enter your last name");
            return false;
        }
        
        if (!address) {
            showError("Please enter your address");
            return false;
        }
        
        if (!city) {
            showError("Please enter your city");
            return false;
        }
        
        if (!state) {
            showError("Please enter your state");
            return false;
        }
        
        const zipcodePattern = /^[0-9]{5}$/;
        if (!zipcode) {
            showError("Please enter your zip code");
            return false;
        } else if (!zipcodePattern.test(zipcode)) {
            showError("Invalid zip code. Please enter again");
            return false;
        }
        
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email) {
            showError("Please enter your email");
            return false;
        } else if (!emailPattern.test(email)) {
            showError("Invalid email. Please enter again");
            return false;
        }
        
        const phonePattern = /^01[0-9]{8,9}$/;
        if (!phone) {
            showError("Please enter your phone number");
            return false;
        } else if (!phonePattern.test(phone)) {
            showError("Invalid phone number. Please enter again");
            return false;
        }
    
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "OrderController.php?action=saveOrder", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('.modal-body.notification').innerText = xhr.responseText;
                $('#notificationModal').modal('show');
            }
        };

        xhr.send(JSON.stringify({ orderID }));
    }
</script>

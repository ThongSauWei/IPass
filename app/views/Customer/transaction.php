
<?php
include_once __DIR__ . '/header.php';
?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Your Transactions
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>

    <section id="cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php if (!empty($orderList)): ?>
                            <tbody>
                                <?php foreach ($orderList as $order): ?>
                                <tr>
                                    <td><?= $counter ?></td>
                                    <td>
                                        <?= $order["OrderID"] ?>
                                    </td>
                                    <td>
                                        <?= $order["OrderDate"] ?>
                                    </td>
                                    <td>
                                        <?= number_format($order["PurchasedAmt"] - $order["Discount"] + $order["DeliveryFee"], 2) ?>
                                    </td>
                                    <td>
                                        <?= $order["Status"] ?>
                                    </td>
                                    <td>
                                        <button id="btn-<?= $order['OrderID'] ?>" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#detailModal" data-order-id="<?= $order['OrderID'] ?>">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                <?php $counter++ ?>
                                <?php endforeach; ?>
                            </tbody>
                            <?php endif; ?>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#" data-page="previous">Previous</a></li>
                            <?php for ($i = 1; $i <= $pageNum; $i++): ?>
                            <li class="page-item"><a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                            <li class="page-item"><a class="page-link" href="#" data-page="next">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p id="billing-details">
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p id="payment-method">
                            </p>
                            <p id="payment-date">
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p>
                                <strong>Your Order:</strong>
                            </p>
                            <div class="table-responsive">
                                <table class="table" id="order-details">
                                    <thead>
                                        <tr>
                                            <th>Products</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="cancel-order" type="button" class="btn btn-outline-danger" onclick="cancelOrder()">Cancel Order</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
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
<script>
    
    var currentPage;
    
    document.addEventListener('DOMContentLoaded', function() {
    const paginationLinks = document.querySelectorAll('.page-link');
    
    currentPage = 1;

    paginationLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            // Get the clicked page number from data-page attribute
            let page = this.getAttribute('data-page');
            console.log(currentPage);
            // Handle "Previous" and "Next"
            if (page === 'previous') {
                if (currentPage == 1) {
                    page = currentPage;
                } else {
                    page = Number(currentPage) - 1;
                }
            } else if (page === 'next') {
                if (currentPage == <?= $pageNum ?>) {
                    page = currentPage;
                } else {
                    page = Number(currentPage) + 1;
                }
            }
            
            // Send the page number to the server
            fetchPageData(page);
        });
    });
});

// Function to send the page number to the server and fetch data
function fetchPageData(pageNumber) {
    currentPage = pageNumber;
    
    
            
    console.log(currentPage);
    
    fetch(`../controllers/TransactionController.php?action=getPaginatedOrders&page=${pageNumber}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        // Handle the received data (update the page content)
        console.log("Data for page ", pageNumber, data);
        updatePageContent(data); // A function that updates the page content with the new data
    })
    .catch(error => console.error('Error fetching page data:', error));
}

// Example function to update page content (you can modify this according to your needs)
function updatePageContent(data) {
    // Update the page with the received data (e.g., replace the list of items)
    const tbody = document.querySelector('.table tbody');
            tbody.innerHTML = ''; // Clear the table body

            data.orderList.forEach((order, index) => {
                const row = `
                    <tr>
                        <td>${(currentPage - 1) * data.perPage + index + 1}</td>
                        <td>${order.OrderID}</td>
                        <td>${order.OrderDate}</td>
                        <td>${(Number(order.PurchasedAmt) - Number(order.Discount) + Number(order.DeliveryFee)).toFixed(2)}</td>
                        <td>${order.Status}</td>
                        <td>
                            <button id="btn-${order.OrderID}" type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#detailModal" data-order-id="${order.OrderID}">
                                Detail
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
}

document.addEventListener('DOMContentLoaded', function() {
    const paginationContainer = document.querySelector('.table-responsive'); // or another parent element
    
    paginationContainer.addEventListener('click', function(event) {
        if (event.target && event.target.matches('[data-toggle="modal"]')) {
            const orderID = event.target.getAttribute('data-order-id');
            fetchOrderDetails(orderID);
        }
    });
});

function fetchOrderDetails(orderID) {
    fetch(`../controllers/TransactionController.php?action=getOrderDetails&orderID=${orderID}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        
        const { orderData, orderDetailsData } = data;
        // Populate the modal with the fetched details
        populateModal(orderData, orderDetailsData);
    })
    .catch(error => console.error('Error fetching order details:', error));
}

function populateModal(orderData, orderDetailsData) {
    const modalBody = document.querySelector('.modal-body');
    const address = orderData.DeliveryAddress.split(',');
    document.querySelector('#exampleModalLabel').textContent = 'Order ID : ' + orderData.OrderID;
    modalBody.querySelector('#billing-details').innerHTML = '<strong>Billing Detail:</strong><br>';
    
    address.forEach(element => {
        modalBody.querySelector('#billing-details').innerHTML += element + "<br>";
    });
    
    modalBody.querySelector('#payment-method').innerHTML = '<strong>Payment Method:</strong><br>' + orderData.PaymentType + '<br>';
    modalBody.querySelector('#payment-date').innerHTML = '<strong>Payment Date</strong><br>' + orderData.OrderDate + '<br>';
    
    const orderDetailsTableBody = modalBody.querySelector('#order-details tbody');
    orderDetailsTableBody.innerHTML = '';
    
    orderDetailsData.forEach(item => {
        const row = `
            <tr>
                <td>${item.ProductName} x${item.Quantity}</td>
                <td class="text-right">RM ${(Number(item.Quantity) * Number(item.UnitPrice) - Number(item.Discount)).toFixed(2)}</td>
            </tr>
        `;
        orderDetailsTableBody.insertAdjacentHTML('beforeend', row);
    });
    
    const totals = `
        <tr>
            <td><strong>Cart Subtotal</strong></td>
            <td class="text-right">RM ${(Number(orderData.PurchasedAmt) - Number(orderData.Discount)).toFixed(2)}</td>
        </tr>
        <tr>
            <td><strong>Shipping</strong></td>
            <td class="text-right">RM ${Number(orderData.DeliveryFee).toFixed(2)}</td>
        </tr>
        <tr>
            <td><strong>ORDER TOTAL</strong></td>
            <td class="text-right"><strong>RM ${(Number(orderData.PurchasedAmt) - Number(orderData.Discount) + Number(orderData.DeliveryFee)).toFixed(2)}</strong></td>
        </tr>
    `;
    orderDetailsTableBody.insertAdjacentHTML('beforeend', totals);
    
    const btn = document.querySelector('#cancel-order');
    btn.setAttribute('data-order-id', `${orderData.OrderID}`);
    
}

function cancelOrder() {
    const btn = document.querySelector('#cancel-order');
    let orderID = btn.getAttribute('data-order-id');
    
    let xhr = new XMLHttpRequest();
        xhr.open("POST", "TransactionController.php?action=cancelOrder", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.querySelector('.modal-body.notification').innerText = xhr.responseText;
                $('#notificationModal').modal('show');
            }
        };

        xhr.send(JSON.stringify(orderID));
}


</script>
<?php
include_once __DIR__ . '/footer.php';
?>
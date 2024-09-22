
<?php
include_once __DIR__ . '/header.php';
?> 
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Your Cart
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
                                    <th width="10%"></th>
                                    <th>Products</th>
                                    <th>Price</th>
                                    <th width="15%">Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                                <tbody>
                                    <?php if (!empty($cartItems)): ?>
                                    <?php foreach ($cartItems as $key => $item): ?>
                                        <tr id="row-<?= $item['ProductID'] ?>">
                                            <td>
                                                <img src="<?= ROOT ?>/assets/img/ProductImage/<?= $item["ProductImage"] ?>" width="60">
                                            </td>
                                            <td>
                                                <?= $item["ProductName"] ?> <br>
                                                <small><?= $cartItems[$key]["Weight"] ?>g</small>
                                            </td>
                                            <td id="price-<?= $item['ProductID'] ?>">
                                                <?= isset($item["PromotionPrice"])? "<del>RM " . $item["Price"] . "</del> <span style='color:rgb(233, 30, 99);'>RM " . $item["PromotionPrice"] . "</span>" : "RM " . $item["Price"] ?>
                                            </td>
                                            <td>
                                                <input onchange="calculateSubtotal(this, '<?= $item['ProductID'] ?>')" class="vertical-spin" product-id="<?= $item['ProductID'] ?>" type="text" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="<?= $item['Quantity'] ?>" name="vertical-spin">
                                            </td>
                                            <td id="subtotal-<?= $item['ProductID'] ?>" class="subtotal">

                                            </td>
                                            <td>
                                                <a onclick="removeCartItem('<?= $item['ProductID'] ?>')" href="javasript:void" class="text-danger"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
        <!--                                        <tr>
                                            <td>
                                                <img src="../../../public/assets/img/meats.jpg" width="60">
                                            </td>
                                            <td>
                                                Sirloin<br>
                                                <small>1000g</small>
                                            </td>
                                            <td>
                                                Rp 120.000
                                            </td>
                                            <td>
                                                <input class="vertical-spin" type="text" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="" name="vertical-spin">
                                            </td>
                                            <td>
                                                Rp 120.000
                                            </td>
                                            <td>
                                                <a href="javasript:void" class="text-danger"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="../../../public/assets/img/vegetables.jpg" width="60">
                                            </td>
                                            <td>
                                                Mix Vegetables<br>
                                                <small>1000g</small>
                                            </td>
                                            <td>
                                                Rp 30.000
                                            </td>
                                            <td>
                                                <input class="vertical-spin" type="text" data-bts-button-down-class="btn btn-primary" data-bts-button-up-class="btn btn-primary" value="" name="vertical-spin">
                                            </td>
                                            <td>
                                                Rp 30.000
                                            </td>
                                            <td>
                                                <a href="javasript:void" class="text-danger"><i class="fa fa-times"></i></a>
                                            </td>
                                                </tr>-->
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan='6' class='text-center'>No cart items found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                        </table>
                    </div>
                </div>
                <div class="col">
                    <a href="../views/Customer/shop.php" class="btn btn-default">Continue Shopping</a>
                </div>
                <div class="col text-right">
                    <div class="input-group w-50 float-right">
                        <input class="form-control" placeholder="Coupon Code" type="text">
                        <div class="input-group-append">
                            <button class="btn btn-default" type="button">Apply</button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <h6 id="total" class="mt-3"></h6>
                    <button onclick="window.location.href='../controllers/CheckoutController.php?action=showPage'" class="btn btn-lg btn-primary" <?= empty($cartItems)?"disabled":"" ?>>Checkout <i class="fa fa-long-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function calculateSubtotal(element, productID) {
        let quantity = element.value;
        let priceTD = document.querySelector('#price-' + productID);
        let priceElement = priceTD.querySelector('span');
        
        var price;
        if (priceElement) {
            price = priceElement.textContent.replace(/[^\d.-]/g, '');
        } else {
            price = priceTD.textContent.replace(/[^\d.-]/g, '');
        }

        let subtotal = price * quantity;

        document.querySelector('#subtotal-' + productID).textContent = 'RM ' + subtotal.toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;

        document.querySelectorAll('.subtotal').forEach(function (element) {
            let subtotal = parseFloat(element.textContent.replace(/[^\d.-]/g, ''));
            total += subtotal;
        });

        document.querySelector('#total').textContent = 'Total: RM ' + total.toLocaleString('en-MY', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function removeCartItem(productID) {
        let row = document.querySelector('#row-' + productID);

        row.remove();

        calculateTotal();

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "CartController.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.send("action=removeCartItem&productID=" + productID);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Cart Item removed");
            }
        };
    }

    window.addEventListener('beforeunload', function (event) {
        let cartItems = [];

        document.querySelectorAll('input[name="vertical-spin"]').forEach(function (element) {
            let productID = element.getAttribute('product-id');
            let quantity = element.value;

            cartItems.push({quantity: quantity, productID: productID});
        });

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "CartController.php?action=updateCart", true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.send(JSON.stringify(cartItems));
    });

    window.onload = function () {
        document.querySelectorAll('input[name="vertical-spin"]').forEach(function (element) {
            let productID = element.getAttribute('product-id');
            calculateSubtotal(element, productID);
        });

        calculateTotal();
    };
</script>

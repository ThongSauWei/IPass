
<?php
include_once 'header.php';
?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Checkout
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div>
        </div>
    </div>

    <section id="checkout">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-7">
                    <h5 class="mb-3">BILLING DETAILS</h5>
                    <!-- Bill Detail of the Page -->
                    <form action="#" class="bill-detail">
                        <fieldset>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Name" type="text">
                                </div>
                                <div class="col">
                                    <input class="form-control" placeholder="Last Name" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Company Name" type="text">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Address"></textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Town / City" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="State / Country" type="text">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Postcode / Zip" type="text">
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Email Address" type="email">
                                </div>
                                <div class="col">
                                    <input class="form-control" placeholder="Phone Number" type="tel">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox"> Ship to a different address?
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Order Notes"></textarea>
                            </div>
                        </fieldset>
                    </form>
                    <!-- Bill Detail of the Page end -->
                </div>
                <div class="col-xs-12 col-sm-5">
                    <div class="holder">
                        <h5 class="mb-3">YOUR ORDER</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Products</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <?php if (!empty($cartItems)): ?>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td>
                                                    <?= $item["ProductID"] ?> x<?= $item["Quantity"] ?>
                                                </td>
                                                <td class="text-right">
                                                    RM <span class="subtotal"><?= number_format($item["Price"] * $item["Quantity"], 2) ?></span>
                                                </td>
                                            </tr>
        <!--                                            <tr>
                                                <td>
                                                    Sirloin x1
                                                </td>
                                                <td class="text-right">
                                                    Rp 120.000
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Mix Vegetables x1
                                                </td>
                                                <td class="text-right">
                                                    Rp 30.000
                                                </td>
                                            </tr>-->
                                        <?php endforeach; ?>
                                    </tbody>
                                <?php endif; ?>
                                <tfooter>
                                    <tr>
                                        <td>
                                            <strong>Cart Subtotal</strong>
                                        </td>
                                        <td class="text-right">
                                            RM <?= number_format($subtotal, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Shipping</strong>
                                        </td>
                                        <td class="text-right">
                                            RM <?= number_format($deliveryFee, 2) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>ORDER TOTAL</strong>
                                        </td>
                                        <td class="text-right">
                                            <strong>RM <?= number_format($subtotal + $deliveryFee) ?></strong>
                                        </td>
                                    </tr>
                                </tfooter>
                            </table>
                        </div>
                    </div>
                    <p class="text-right mt-3">
                        <input checked="" type="checkbox"> I’ve read &amp; accept the <a href="#">terms &amp; conditions</a>
                    </p>
<!--                    <a href="#" class="btn btn-primary float-right">PROCEED TO CHECKOUT <i class="fa fa-check"></i></a>
                    <div class="clearfix">-->
                    <!--                    <div id="paypal-button-container"></div>-->
                    <form id="payment-form">
                        <div id="payment-element">
                            
                        </div>
                        <button id="submit" class="btn btn-primary float-right" style="margin-top: 10px;">CHECKOUT <i class="fa fa-check"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<!--<script src="https://www.paypal.com/sdk/js?client-id=AVdHnaMMrATHoOSsE-2Ci4__PPN_eeufjmyrd7KKmXBeCzUifSfcH5BKECix6nob4u2vW5flPZ50Jluv&currency=MYR"></script>  PayPal SDK -->
<script src="https://js.stripe.com/v3/"></script>
<script>

//    paypal.Buttons({
//        // Set up the transaction
//        createOrder: function (data, actions) {
//            return fetch('../controllers/CheckoutController.php?action=createOrder', {
//                method: 'post',
//                headers: {
//                    'content-type': 'application/json'
//                }
//            }).then(function (res) {
//                if (!res.ok) {
//                    throw new Error('Network response was not ok');
//                }
//                return res.json(); // Get the order ID from the server
//            }).then(function (orderData) {
//                return orderData.id; // Use the order ID to create the transaction
//            }).catch(function (error) {
//                console.error('There was a problem with the fetch operation:', error);
//            });
//        },
//
//        // Finalize the transaction after the user approves the payment
//        onApprove: function (data, actions) {
//            return fetch('../controllers/CheckoutController.php?action=captureOrder', {
//                method: 'post',
//                headers: {
//                    'content-type': 'application/json'
//                },
//                body: JSON.stringify({
//                    orderID: data.orderID
//                })
//            }).then(function (res) {
//                if (!res.ok) {
//                    throw new Error('Network response was not ok');
//                }
//                return res.json(); // Process the capture result
//            }).then(function (orderData) {
//                // Show a success message to the buyer
//                alert('Transaction completed by ' + orderData.payer.name.given_name);
//            }).catch(function (error) {
//                console.error('There was a problem with the fetch operation:', error);
//            });
//        }
//    }).render('#paypal-button-container'); // Display the PayPal button
    const stripe = Stripe('pk_test_51Q04vAGYxtnf60CL0uLszABpTGtOK8U4u7AqcOZfL1CJPLaVm7FbT1E8Ic65iias1R7Wuo3l4WtXEvl37SNbOhFH008FIfopVf');
    
    const totalAmount = <?= ($subtotal + $deliveryFee) * 100 ?>;
    
    let element;
    
    initialise();
    
    document.querySelector("#payment-form").addEventListener("submit", handleSubmit);
    
    async function initialise() {
        const { clientSecret, paymentIntentID } = await fetch("../web/create-payment-intent.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ totalAmount }),
        }).then((r) => r.json());
        
        localStorage.setItem('paymentIntentID', paymentIntentID);
        
        elements = stripe.elements({ clientSecret });
        
        const paymentElementOptions = {
            layout: "tabs",
        };
        
        const paymentElement = elements.create("payment", paymentElementOptions);
        paymentElement.mount("#payment-element");
    }
    
    async function handleSubmit(e) {
        e.preventDefault();
        
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                return_url: "https://localhost/IPass/public/index.php",
            },
        });
    }
</script>
<?php
include_once 'footer.php';
?>

<?php
include_once __DIR__ . '/header.php';
?>

<div class="container" style="margin-bottom: 100px; margin-top:100px;">
    <div class="row">
        <div class="col-xs-12 col-md-8 offset-md-2">
            <div class="thank-you-box text-center mt-5">
                <h1 class="display-4">Thank You, <?= htmlspecialchars($username) ?>!</h1>
                <p class="lead">Your order has been successfully placed.</p>
                <p>Order ID: <strong>#<?= htmlspecialchars($orderID) ?></strong></p>
                <p>Total Amount: <strong>RM <?= number_format($totalAmount, 2) ?></strong></p>
                <p class="mt-4">We appreciate your business. Your order is now being processed and you can view it in your transaction page later.</p>
                <p>If you have any questions, feel free to <a href="http://localhost/IPass/app/views/Customer/contact.php">contact us</a>.</p>
                
                <a href="http://localhost/IPass/app/views/Customer/homepage.view.php" class="btn btn-primary mt-3">Return to Home</a>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'footer.php';
?>


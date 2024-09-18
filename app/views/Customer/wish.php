<?php
include_once 'header.php';
require_once __DIR__ . '/../../controllers/WishlistController.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Create an instance of the WishlistController
$wishlistController = new WishlistController();
$productController = new ProductController();

// Fetch the wishlist products for the customer (currently set to 'C0001')
// Replace this with the actual customer ID later
//echo "User ID: " . htmlspecialchars($userID);
//get customer id
$customerID = $productController->getCustomerIDByUserID($userID);

//echo "Cust ID: " . htmlspecialchars($customerID);

$wishlistProducts = $wishlistController->showWishlist($customerID);
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Wishlist
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
                                    <th width="18%">Products</th>
                                    <th width="12%">Price</th>
                                    <th width="15%">Quantity</th>
                                    <th width="10%">Subtotal</th>
                                    <th width="10%">Status</th>
                                    <th width="14%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($wishlistProducts)): ?>
                                    <?php foreach ($wishlistProducts as $product): ?>
                                        <tr class="<?= !$product['Availability'] ? 'out-of-stock' : '' ?>">
                                            <td>
                                                <img src="../../../public/assets/img/ProductImage/<?= htmlspecialchars($product['ProductImage']) ?>" width="60">
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($product['ProductName']) ?>
                                            </td>
                                            <td>
                                                RM <?= number_format($product['Price'], 2) ?>
                                            </td>

                                            <td>
                                                <form class="wishlist-form" method="POST" action="../../controllers/WishlistController.php">
                                                    <input type="hidden" name="action" value="updateWishlistQuantity">
                                                    <input type="hidden" name="wishlistId" value="<?= htmlspecialchars($product['WishlistID']) ?>">
                                                    <input type="hidden" name="productId" value="<?= htmlspecialchars($product['ProductID']) ?>">
                                                    <input class="vertical-spin quantity-input" type="number" name="quantity" value="<?= htmlspecialchars($product['Quantity']) ?>">
                                                </form>
                                            </td>
                                            <td id="subtotal-<?= htmlspecialchars($product['ProductID']) ?>">
                                                RM <?= number_format($product['Price'] * $product['Quantity'], 2) ?>
                                            </td>

                                            <td class="<?= !$product['Availability'] ? 'text-danger' : '' ?>">
                                                <?= $product['Availability'] ? 'In Stock' : 'Out of Stock' ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="text-danger delete-item" data-wishlistid="<?= htmlspecialchars($product['WishlistID']) ?>" data-productid="<?= htmlspecialchars($product['ProductID']) ?>"><i class="fa fa-times"></i></a>
                                                <?php if ($product['Availability']): ?>
                                                    <a href="javascript:void(0)" class="btn btn-default btn-add-to-cart" 
                                                       data-productid="<?= htmlspecialchars($product['ProductID']) ?>" 
                                                       data-quantity="<?= htmlspecialchars($product['Quantity']) ?>" 
                                                       data-wishlistid="<?= htmlspecialchars($product['WishlistID']) ?>" 
                                                       data-custid="<?= htmlspecialchars($customerID) ?>" 
                                                       style="margin-left: 8px;">Add to Cart</a>
                                                <?php endif; ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No items in your wishlist.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.quantity-input').on('change', function () {
            var quantity = parseInt(this.value, 10);
            var form = $(this).closest('form');
            var wishlistId = form.find('input[name="wishlistId"]').val();
            var productId = form.find('input[name="productId"]').val();

            if (quantity === 0) {
                if (confirm('The quantity is set to 0. Do you want to delete this item from your wishlist? This action cannot be undone.')) {
                    $.post('../../controllers/WishlistController.php', {
                        action: 'deleteWishlistItem',
                        wishlistId: wishlistId,
                        productId: productId
                    }, function (response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            window.location.reload(); // Reload the page to reflect changes
                        } else {
                            alert(data.message);
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert('An error occurred: ' + textStatus);
                    });
                } else {
                    // Reset the quantity input to a default value (e.g., 1) if the user does not confirm deletion
                    this.value = 1;
                }
            } else {
//                alert('test3');
                $.ajax({
                    url: '../../controllers/WishlistController.php',
                    type: 'POST',
                    data: {
                        action: 'updateWishlistQuantity',
                        wishlistId: wishlistId,
                        productId: productId,
                        quantity: quantity
                    },
                    success: function (response) {
                        var data = JSON.parse(response);
                        if (data.success) {
//                            alert('test4');
                            // Update subtotal without reloading
                            var subtotalCell = $('#subtotal-' + productId);
                            var previousQuantity = data.previousQuantity || 1; // Ensure there's a default value
                            var previousSubtotal = parseFloat(subtotalCell.text().replace('RM ', ''));
                            var newSubtotal = (previousSubtotal * quantity) / previousQuantity;
                            subtotalCell.text('RM ' + newSubtotal.toFixed(2));
//                            alert('test5');
                            window.location.reload();
                            
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('An error occurred: ' + textStatus);
                    }
                });
            }
        });

        $('.delete-item').on('click', function () {
            var wishlistId = $(this).data('wishlistid');
            var productId = $(this).data('productid');

            if (confirm('Are you sure you want to delete this item from your wishlist? This action cannot be undone.')) {
                $.post('../../controllers/WishlistController.php', {
                    action: 'deleteWishlistItem',
                    wishlistId: wishlistId,
                    productId: productId
                }, function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        window.location.reload(); // Reload the page to reflect changes
                    } else {
                        alert(data.message);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    alert('An error occurred: ' + textStatus);
                });
            }
        });

        $('.btn-add-to-cart').on('click', function () {
            var productId = $(this).data('productid');
            var quantity = $(this).data('quantity');
            var wishlistId = $(this).data('wishlistid'); // Get wishlistId from data attribute
            var custId = $(this).data('custid');

            $.post('../../controllers/WishlistController.php', {
                action: 'addToCart',
                productId: productId,
                quantity: quantity,
                wishlistId: wishlistId,
                custId: custId
            }, function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    alert('Product added to cart successfully!');
                    window.location.reload(); // Reload to update the wishlist view
                } else {
                    alert(data.message);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert('An error occurred: ' + textStatus);
            });
        });
    });
</script>


<style>
    .out-of-stock {
        background-color: #f0f0f0; /* Grey color for out of stock rows */
    }
    .out-of-stock .text-danger {
        color: red; /* Red color for "Out of Stock" text */
    }
</style>

<?php
include_once __DIR__ . '/footer.php';
?>

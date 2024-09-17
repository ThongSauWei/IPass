<?php
// Include the ProductController
require_once __DIR__ . '/../../../controllers/ProductController.php';

// Create an instance of the ProductController
$productController = new ProductController();

// Get the productID from the URL
$productID = isset($_GET['productID']) ? $_GET['productID'] : '';

// Fetch the product details using the controller
$product = $productController->getProductById($productID);

// Fetch all categories using the controller
$categories = $productController->getCategories();

// Handle if product is not found
if (!$product) {
    echo "Product not found.";
    exit;
}

// If the product array contains data, extract the first element
$product = $product[0];

include_once __DIR__ . '/../header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product - <?php echo htmlspecialchars($product['ProductID']); ?></h6>
        </div>
        <div class="card-body">
            <form id="editProductForm">
                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product['ProductID']); ?>">

                <!-- Existing Image Preview -->
                <div class="mb-3 text-center">
                    <label for="existingImage" class="form-label">Product Image</label><br>
                    <img src="../../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Product Image" width="290">
                    <input type="hidden" name="existingProductImage" value="<?php echo htmlspecialchars($product['ProductImage']); ?>">
                </div>

                <!-- Product Name -->
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['ProductName']); ?>" disabled>
                </div>

                <!-- Product Description -->
                <div class="mb-3">
                    <label for="productDesc" class="form-label">Product Description</label>
                    <textarea class="form-control" id="productDesc" name="productDesc" disabled><?php echo htmlspecialchars($product['ProductDesc']); ?></textarea>
                </div>

                <!-- Category Dropdown -->
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <!--<select class="form-control" id="category" name="category" disabled>-->
                    <!--<option value="" disabled>Select Category</option>-->
                    <?php foreach ($categories as $category): ?>

                        <?php if ($product['Category'] === $category['Category']) { ?>
                            <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($category['Category']); ?>" disabled>
                        <?php } endforeach; ?>
                    <!--<option value="Other">Other</option>-->
                    <!--</select>-->
                    <input type="hidden" id="finalCategory<?php echo htmlspecialchars($product['ProductID']); ?>" name="category" value="<?php echo htmlspecialchars($product['Category']); ?>">
                </div>


                <div class="row">
                    <!-- Price (MYR) -->
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price (MYR)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo number_format((float) $product['Price'], 2, '.', ''); ?>" disabled>
                    </div>

                    <!-- Weight (KG) -->
                    <div class="col-md-6 mb-3">
                        <label for="weight" class="form-label">Weight (KG)</label>
                        <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($product['Weight']); ?>" disabled>
                    </div>
                </div>

                <!-- Availability -->
                <div class="mb-3">
                    <label for="availability" class="form-label">Availability</label>
                    <?php if ($product['Availability'] == 1) { ?>
                        <input type="text" class="form-control" id="productName" name="productName" value="Available" disabled>
                    <?php } if ($product['Availability'] == 0) { ?>
                        <input type="text" class="form-control" id="productName" name="productName" value="Unavailable" disabled>
                    <?php } ?>
                </div>

            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
include_once __DIR__ . '/../footer.php';
?>
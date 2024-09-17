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
            <h6 class="m-0 font-weight-bold text-primary">Edit Product - <?php echo htmlspecialchars($product['ProductID']); ?></h6>
        </div>
        <div class="card-body">
            <form id="editProductForm" action="../../../controllers/ProductController.php?action=updateProduct" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product['ProductID']); ?>">

                <!-- Existing Image Preview -->
                <div class="mb-3 text-center">
                    <label for="existingImage" class="form-label">Existing Product Image</label><br>
                    <img src="../../../../public/assets/img/ProductImage/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="Product Image" width="290">
                    <input type="hidden" name="existingProductImage" value="<?php echo htmlspecialchars($product['ProductImage']); ?>">
                </div>

                <!-- Product Name -->
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName" value="<?php echo htmlspecialchars($product['ProductName']); ?>" required>
                </div>

                <!-- Product Description -->
                <div class="mb-3">
                    <label for="productDesc" class="form-label">Product Description</label>
                    <textarea class="form-control" id="productDesc" name="productDesc" required><?php echo htmlspecialchars($product['ProductDesc']); ?></textarea>
                </div>

                <!-- Category Dropdown -->
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" onchange="handleCategoryChangeEdit(this)" required>
                        <option value="" disabled>Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['Category']); ?>" <?php echo ($product['Category'] === $category['Category']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['Category']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                    <input type="hidden" id="finalCategory<?php echo htmlspecialchars($product['ProductID']); ?>" name="category" value="<?php echo htmlspecialchars($product['Category']); ?>">
                </div>

                <!-- Custom Category (only shown if "Other" is selected) -->
                <div class="mb-3" id="customCategoryDiv<?php echo htmlspecialchars($product['ProductID']); ?>" style="display: none;">
                    <label for="customCategory" class="form-label">Enter Custom Category</label>
                    <input type="text" class="form-control" id="customCategory<?php echo htmlspecialchars($product['ProductID']); ?>" name="customCategory" placeholder="Enter new category">
                </div>

                <div class="row">
                    <!-- Price (MYR) -->
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price (MYR)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo number_format((float) $product['Price'], 2, '.', ''); ?>" required>
                    </div>

                    <!-- Weight (KG) -->
                    <div class="col-md-6 mb-3">
                        <label for="weight" class="form-label">Weight (KG)</label>
                        <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo htmlspecialchars($product['Weight']); ?>" required>
                    </div>
                </div>


                <div class="row">
                    <!-- Availability -->
                    <div class="col-md-6 mb-3">
                        <label for="availability" class="form-label">Availability</label>
                        <select class="form-control" id="availability" name="availability" required>
                            <option value="1" <?php echo ($product['Availability'] == 1) ? 'selected' : ''; ?>>Available</option>
                            <option value="0" <?php echo ($product['Availability'] == 0) ? 'selected' : ''; ?>>Not Available</option>
                        </select>
                    </div>

                    <!-- Upload New Image -->
                    <div class="col-md-6 mb-3">
                        <label for="productImage" class="form-label">Upload New Image (optional)</label>
                        <input type="file" class="form-control" id="productImage" name="ProductImage">
                    </div>
                </div>

                <!-- Modal Footer with Buttons -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
// Handle category change for edit product modal
    function handleCategoryChangeEdit(select) {
        const productId = select.closest('form').querySelector('input[name="productID"]').value;
        const customCategoryDiv = document.getElementById('customCategoryDiv' + productId);
        const customCategoryInput = document.getElementById('customCategory' + productId);
        const finalCategoryInput = document.getElementById('finalCategory' + productId);

        if (select.value === 'Other') {
            customCategoryDiv.style.display = 'block';
            customCategoryInput.required = true;
            finalCategoryInput.value = customCategoryInput.value; // Ensure final category value is updated
        } else {
            customCategoryDiv.style.display = 'none';
            customCategoryInput.required = false;
            customCategoryInput.value = '';
            finalCategoryInput.value = select.value; // Set hidden field to selected option value
        }
    }

// Add event listener to update hidden field when custom category input changes
    document.querySelectorAll('input[name="customCategory"]').forEach(input => {
        input.addEventListener('input', () => {
            const productId = input.id.replace('customCategory', '');
            const finalCategoryInput = document.getElementById('finalCategory' + productId);
            finalCategoryInput.value = input.value;
        });
    });

</script>

<?php
include_once __DIR__ . '/../footer.php';
?>
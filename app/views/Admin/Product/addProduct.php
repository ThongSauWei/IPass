<?php
// Include the ProductController
require_once __DIR__ . '/../../../controllers/ProductController.php';

// Create an instance of the ProductController
$productController = new ProductController();

// Fetch all categories using the controller
$categories = $productController->getCategories();

include_once __DIR__ . '/../header.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add Product</h6>
        </div>
        <div class="card-body">
            <form id="addProductForm" action="../../../controllers/ProductController.php?action=addProduct" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="ProductName" placeholder="Product Name" required>
                </div>
                <div class="mb-3">
                    <label for="productDesc" class="form-label">Product Description</label>
                    <textarea class="form-control" id="productDesc" name="ProductDesc" placeholder="Product Description"></textarea>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="categoryAdd" name="Category" onchange="handleCategoryChange(this)" required>
                        <option value="" selected>Select Category</option>
                        <!-- Add your dynamic PHP categories here -->
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['Category']); ?>">
                                <?php echo htmlspecialchars($category['Category']); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                    <input type="hidden" id="finalCategoryAdd" name="Category">
                </div>
                <!-- Custom Category (only shown if "Other" is selected) -->
                <div class="mb-3" id="customCategoryDivAdd" style="display: none;">
                    <label for="customCategoryAdd" class="form-label">Enter Custom Category</label>
                    <input type="text" class="form-control" id="customCategoryAdd" name="customCategory" placeholder="Enter new category">
                </div>

                <div class="row">
                    <!-- Price Field -->
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="Price" step="0.01" placeholder="Price" required>
                    </div>

                    <!-- Weight Field -->
                    <div class="col-md-6 mb-3">
                        <label for="weight" class="form-label">Weight (KG)</label>
                        <input type="number" class="form-control" id="weight" name="Weight" step="0.01" placeholder="Weight (kg)" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="productImage" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="ProductImage">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="availability" class="form-label">Availability</label>
                        <select class="form-control" id="availability" name="Availability">
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<script>
// Handle category change for add product modal
    function handleCategoryChange(selectElement) {
        const selectedCategory = selectElement.value;
        const customCategoryDiv = document.getElementById('customCategoryDivAdd');
        const customCategoryInput = document.getElementById('customCategoryAdd');
        const finalCategoryInput = document.getElementById('finalCategoryAdd');

        if (selectedCategory === 'Other') {
            customCategoryDiv.style.display = 'block';
            finalCategoryInput.value = customCategoryInput.value; // Preserve custom category value
        } else {
            customCategoryDiv.style.display = 'none';
            customCategoryInput.value = ''; // Clear custom category input
            finalCategoryInput.value = selectedCategory; // Set selected category
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
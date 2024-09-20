<?php
include_once __DIR__ . '/../../../../app/views/Admin/header.php';
?>

<style>
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }
</style>

<?php
require_once __DIR__ . '/../../../controllers/ProductController.php';
require_once __DIR__ . '/XSLTransformation.php';

$productController = new ProductController();



// Get categories for dropdown (for the filter form)
$categories = $productController->getCategoriesArray();

$products = $productController->getAllProducts();
// Generate the XML file for available products
$xmlFilePath = __DIR__ . '/productReport.xml';
$xslFilePath = __DIR__ . '/productReport.xsl';

// Create a new SimpleXMLElement
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="productReport.xsl"?>
<Report/>'); // Change to <Report> to match the XSLT structure

// Add the current date and time
//$xml->addChild('GeneratedDateTime', date('Y-m-d\TH:i:s'));

// Add only available products
foreach ($products as $product) {
//    if ($product['Availability'] == 1) { // Uncomment this line to filter by availability
        $productXML = $xml->addChild('Product');
        $productXML->addChild('ProductID', htmlspecialchars($product['ProductID']));
        $productXML->addChild('ProductName', htmlspecialchars($product['ProductName']));
        $productXML->addChild('Category', htmlspecialchars($product['Category']));
        $productXML->addChild('Price', htmlspecialchars($product['Price']));
        $productXML->addChild('Weight', htmlspecialchars($product['Weight']));
        $productXML->addChild('Availability', htmlspecialchars($product['Availability']));
//    }
}

// Save XML file
$xml->asXML($xmlFilePath);

// Get current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Initialize the XSLT transformation
$xslt = new XSLTransformation();
$transformedHtml = $xslt->transformWithXsl($xmlFilePath, $xslFilePath, $currentDateTime);


$htmlFilePath = __DIR__ . '/productReport.php';
// Check if there was an error with the transformation
if (strpos($transformedHtml, 'Error') === false) {
    file_put_contents($htmlFilePath, $transformedHtml); // Output the transformed HTML (Product Report)
} else {
    echo '<div class="alert alert-danger">' . htmlspecialchars($transformedHtml) . '</div>';
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Product Management</h1>
<!--    <p class="mb-4">DataTables is a third-party plugin that is used to generate the demo table below.
        For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p>-->

    <!-- Display status or error messages -->
    <?php
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        $status = $_GET['status'] ?? '';
        $message = $_GET['message'] ?? '';

        if ($action === 'add') {
            if ($status === 'success') {
                echo '<div class="alert alert-success">Product added successfully!</div>';
            } elseif ($status === 'fail') {
                echo '<div class="alert alert-danger">Failed to add the product. Please try again.</div>';
            } elseif ($status === 'file_error') {
                echo '<div class="alert alert-danger">File upload error: ' . htmlspecialchars($message) . '</div>';
            } elseif ($status === 'invalid_request') {
                echo '<div class="alert alert-danger">Invalid request.</div>';
            }
        } elseif ($action === 'update') {
            if ($status === 'success') {
                echo '<div class="alert alert-success">Product updated successfully!</div>';
            } elseif ($status === 'fail') {
                echo '<div class="alert alert-danger">Failed to update the product. Please try again.</div>';
            } elseif ($status === 'exception') {
                echo '<div class="alert alert-danger">Sorry, there was an error updating the product.</div>';
            } elseif ($status === 'file_error') {
                echo '<div class="alert alert-danger">File upload error: ' . htmlspecialchars($message) . '</div>';
            } elseif ($status === 'invalid_request') {
                echo '<div class="alert alert-danger">Invalid request.</div>';
            }
        } elseif ($action === 'delete') {
            if ($status === 'success') {
                echo '<div class="alert alert-success">Product deleted successfully!</div>';
            } elseif ($status === 'fail') {
                echo '<div class="alert alert-danger">Failed to delete the product. Please try again.</div>';
            }
        }
    }
    ?>

    <!-- add product -->
    <a href="addProduct.php" class="btn btn-success mb-3">
        Add Product
    </a>

    <!-- Print Button in the UI -->
    <a href="javascript:void(0);" class="btn btn-info" onclick="printReport()" style="margin-top: -16px; margin-left: 6px;">Report PDF</a>


    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Weight</th>
                            <th>Availability</th>
                            <th width='20%'>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Weight</th>
                            <th>Availability</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr class="clickable-row">
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';"><?php echo htmlspecialchars($product['ProductID']); ?></td>
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';">
                                        <?php echo htmlspecialchars($product['ProductName']); ?>
                                    </td>
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';">
                                        <img src="../../../../public/assets/img/ProductImage/<?= htmlspecialchars($product['ProductImage']) ?>" width="90" style="margin-top:3px;">
                                    </td>
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';"><?php echo htmlspecialchars($product['Category']); ?></td>
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';"><?php echo htmlspecialchars($product['Price']); ?></td>
                                    <td onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';"><?php echo htmlspecialchars($product['Weight']); ?></td>
                                    <td 
                                        onclick="window.location.href = 'viewProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>';"
                                        style="cursor: pointer; color: <?php echo $product['Availability'] == 1 ? 'green' : 'red'; ?>;">
                                            <?php echo $product['Availability'] == 1 ? 'Available' : 'Unavailable'; ?>
                                    </td>

                                    <td>
                                        <!-- edit product -->
                                        <a href="editProduct.php?productID=<?php echo htmlspecialchars($product['ProductID']); ?>" class="btn btn-primary">
                                            EDIT
                                        </a>

                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#DEL<?php echo htmlspecialchars($product['ProductID']); ?>" style="margin-left:4px;">
                                            DELETE
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="DEL<?php echo htmlspecialchars($product['ProductID']); ?>" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Product: <?php echo htmlspecialchars($product['ProductID']); ?> - <?php echo htmlspecialchars($product['ProductName']); ?></h5>
                                                        <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="../../../controllers/ProductController.php?action=deleteProduct" method="POST">
                                                            <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product['ProductID']); ?>">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No products found.</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
    function printReport() {
        const reportWindow = window.open('<?php echo 'productReport.php'; ?>'); 
        reportWindow.onload = function () {
            reportWindow.print();
        };
    }
</script>
<?php
include_once __DIR__ . '/../../../../app/views/Admin/footer.php';
?>


<?php
include_once __DIR__ . '/../header.php';
?>

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Add Voucher Form -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Add New Voucher</h6>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="voucherCode">Voucher Code</label>
                        <input type="text" class="form-control" id="voucherCode" name="VoucherCode"
                               placeholder="Enter voucher code">
                    </div>
                    <div class="form-group">
                        <label for="voucherDescription">Description</label>
                        <textarea class="form-control" id="voucherDescription" name="Description" rows="3"
                                  placeholder="Enter description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="voucherType">Voucher Type</label>
                        <select class="form-control" name="VoucherType" id="voucherType">
                            <option>Percentage</option>
                            <option>Fixed Amount</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="discountAmount">Discount Amount</label>
                        <input type="number" class="form-control" id="discountAmount" name="Value"
                               placeholder="Enter discount amount">
                    </div>

                    <div class="form-group">
                        <label for="startDate">Start Date</label>
                        <input type="date" class="form-control" name="StartDate" id="startDate">
                    </div>
                    <div class="form-group">
                        <label for="endDate">End Date</label>
                        <input type="date" class="form-control" name="EndDate" id="endDate">
                    </div>

                    <!-- Checkbox for Points Required -->
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="pointsRequiredCheck"
                               onclick="togglePointsField()">
                        <label class="form-check-label" for="pointsRequiredCheck">Require Points for Redemption</label>
                    </div>


                    <div class="form-group" id="pointsField" style="display: none;">
                        <label for="pointsRequired">Points Required</label>
                        <input type="number" class="form-control" id="pointsRequired" name="PointRequired"
                               placeholder="Enter points required">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>


            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->


    <!-- Toggle Points Required field -->
    <script>
        function togglePointsField() {
            var checkBox = document.getElementById("pointsRequiredCheck");
            var pointsField = document.getElementById("pointsField");
            if (checkBox.checked == true) {
                pointsField.style.display = "block";
            } else {
                pointsField.style.display = "none";
            }
        }
    </script>


<?php
include_once __DIR__ . '/../footer.php';

?>
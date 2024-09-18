<?php
$configPath = dirname(__DIR__, 2) . '/core/config.php';
if (file_exists($configPath)) {
    include_once $configPath;
} else {
    echo 'Config file not found: ' . $configPath;
}
?>
<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Your Website 2020</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>



<script src="<?=ROOT?>/assets/vendor/jquery/jquery.min.js"></script>
<script src="<?=ROOT?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=ROOT?>/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?=ROOT?>/assets/js/sb-admin-2.min.js"></script>
</body>

</html>
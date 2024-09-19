
<?php
include_once __DIR__ . '/header.php';
?>



<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?= ROOT ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Register Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>

                <div class="card card-login mb-5">
                    <div class="card-body">



                        <!-- Register Form -->
                        <form class="form-horizontal" method="POST" action="/IPass/app/controllers/UserController.php?action=register">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="fullname" required="" placeholder="Full Name">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" required="" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="username" required="" placeholder="Username">
                                </div>
                            </div>    
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="phone" name="phone" required="" placeholder="Phone">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="date" name="birthday" required="" placeholder="Birthday">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <select class="form-control" required="" name="gender">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="m">Male</option>
                                        <option value="f">Female</option>
                                        <option value="o">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" required="" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="confirmPass" required="" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <input id="checkbox0" type="checkbox" name="terms" required="">
                                        <label for="checkbox0" class="mb-0">I Agree with <a href="terms.php" class="text-light">Terms & Conditions</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Display error messages as alerts -->
<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
    <script>
        let errors = <?php echo json_encode($_SESSION['error']); ?>;
        errors.forEach(function (error) {
            alert(error); // show each error using a JavaScript alert
        });
    </script>
    <?php unset($_SESSION['error']); // clear errors after displaying?>
<?php endif; ?>
    
<?php
include_once __DIR__ . '/footer.php';
?>


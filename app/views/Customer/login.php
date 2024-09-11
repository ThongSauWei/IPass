
<?php
include_once __DIR__ . '/header.php';
?>
<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Login Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>
                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" method="post" action="views/login.php">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="username" required placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 d-flex justify-content-between align-items-center">
                                    <div class="checkbox">
                                        <input id="checkbox0" type="checkbox" name="remember">
                                        <label for="checkbox0" class="mb-0"> Remember Me? </label>
                                    </div>
                                    <a href="views/forgot-password.php" class="text-light"><i class="fa fa-bell"></i> Forgot password?</a>
                                </div>
                            </div>
                            <?php if (isset($error)): ?>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger">
                                            <?php echo htmlspecialchars($error); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Log In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once __DIR__ . '/footer.php';
?>

<?php
    session_start();
    $title = "Login | GroceryStore";
    require_once('./layouts/header.php');
    require_once('./controllers/users.php');
    $result = userLogin('customer');
?>

        <!-- login -->
        <div class="container mt-5" style="min-height: 65vh;">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title text-center">Sign In</h6>
                        </div>
                            <div class="card-body px-5">
                                <?php
                                    if(isset($result['status'])){
                                        ?>
                                            <div class="alert alert-danger text-center"><?=$result['message'];?></div>
                                        <?php
                                    }
                                ?>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                                <div class="form-group pt-4">
                                    <input class="form-control" type="email" name="email" placeholder="Email Address" required=" ">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="password" name="password" placeholder="Password" required=" ">
                                </div>

                                <div class="form-group">
                                    <input class="btn btn-success" type="submit" name="login" value="Login">
                                </div>
                                </form>
                                <div class="cta"><a href="#">Forgot your password?</a></div>
                            </div>
                    </div>
                    </div>
            </div>
        </div>
    <!-- //login -->
<?php
    require_once('./layouts/footer.php');
?>
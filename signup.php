<?php
    require_once('./layouts/header.php');
    require_once('./controllers/users.php');
    $result = createUser();
?>
<!-- signup -->
    <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3 my-5">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="text-center">Create an account</h6>
                        </div>
                        <div class="card-body px-5">

                            <?php
                                if(isset($result['status']) && $result['status'] == true){
                                    ?>
                                        <div class="alert alert-success text-center">You have successfully registered<br> A verification link sent to your email, please verify your email.</div>
                                    <?php
                                }
                            ?>
    
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div class="form-group">
                                <?= isset($result['name'])&&$result['name']=='name'?"<span class='text-danger small'>".$result['message']."</span>":"";?>
                                <input class="form-control" type="text" name="name" placeholder="Full Name" required=" " value="<?=(isset($result['value']))?$result['value']['name']:"";?>">
                            </div>
    
                            <div class="form-group">
                                <?= isset($result['name'])&&$result['name']=='email'?"<span class='text-danger small'>".$result['message']."</span>":"";?>
                                <input class="form-control" type="email" name="email" placeholder="Email Address" required=" " value="<?=isset($result['value'])?$result['value']['email']:"";?>">
                            </div>
    
                            <div class="form-group">
                                <?= isset($result['name'])&&$result['name']=='phone'?"<span class='text-danger small'>".$result['message']."</span>":"";?>
                                <input class="form-control" type="text" name="phone" placeholder="Phone Number" required=" " value="<?=isset($result['value'])?$result['value']['phone']:"";?>">
                            </div>
    
                            <div class="form-group">
                                <?= isset($result['name'])&&$result['name']=='password'?"<span class='text-danger small'>".$result['message']."</span>":"";?>
                                <input class="form-control" type="password" name="password" placeholder="Password" required=" ">
                            </div>

                            <div class="form-group">
                                <?= isset($result['name'])&&$result['name']=='confirmPassword'?"<span class='text-danger small'>".$result['message']."</span>":"";?>
                                <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm password" required=" ">
                            </div>

                            <div class="form-group">
                                <input class="btn btn-success" type="submit" name= "submit" value="Register">
                            </div>
                            </form>
                            <div class="cta"><a href="#">Forgot your password?</a></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
				$(document).ready(function(){
				  $(".form-module .form").css({"display" : "block"})
				});
			</script>
    <!-- //signup -->
<?php
    require_once('./layouts/footer.php');
?>
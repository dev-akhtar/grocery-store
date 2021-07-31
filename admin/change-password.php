<?php
    $title = "Change Password | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/users.php');

    $updateResult = changePassword($_SESSION['userId']);
?>
    <div class="main-container">              
               <div class="row mt-4">
                   <div class="col-sm-8 offset-sm-2">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Change Password
                            </div>
                            <div class="card-body">
                                <div class="container">
                                <?php
                                    if(isset($updateResult['status']) && $updateResult['status'] == 'true'){
                                        ?>
                                            <div class="text-success text-center pt-3"><?=$updateResult['message'];?></div>
                                        <?php
                                    }elseif(isset($updateResult['status']) && $updateResult['status'] == 'false'){
                                        ?>
                                            <div class="text-danger text-center pt-3"><?=$updateResult['message'];?></div>
                                        <?php
                                    }
                            
                                ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div class="form-group pt-2">
                                <label for="Password">Password</label>
                                <input class="form-control" type="password" name="password" placeholder="Password" required=" ">
                            </div>
                
                            <div class="form-group pt-2">
                            <label for="Confirm Password">Confirm Password</label>
                                <input class="form-control" type="password" name="confirmPassword" placeholder="Confirm Password" required=" ">
                            </div>

                            <div class="form-group pb-4">
                                <input class="btn btn-success" type="submit" name="changePassword" value="Save">
                            </div>
                        </form>
                        </div>
                   </div>
               </div>
                            </div>
                        </div>

    </div>

<?php
    include_once('./layout/footer.php');
?>
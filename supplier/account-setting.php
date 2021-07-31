<?php
    $title = "Account Settings | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/users.php');

    $updateResult = updateUser($_SESSION['userId']);
    $getUser = getUserByUserId($_SESSION['userId']);
    if($getUser->num_rows>0){
        $userDetails = $getUser->fetch_array();
    }
?>
    <div class="main-container">              
               <div class="row mt-4">
                   <div class="col-sm-8 offset-sm-2">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Update Information
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
                                <label for="Name">Name</label>
                                <input class="form-control" type="text" name="name" placeholder="Full Name" required=" " value="<?= $userDetails['name']?>">
                            </div>
                
                            <div class="form-group pt-2">
                            <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" placeholder="Email address" required=" " value="<?= $userDetails['email']?>">
                            </div>

                            <div class="form-group pt-2">
                            <label for="price">Phone</label>
                                <input class="form-control" type="text" name="phone" placeholder="Phone" required=" " value="<?= $userDetails['phone']?>">
                            </div>

                            <div class="form-group pb-4">
                                <input class="btn btn-success" type="submit" name="editProfile" value="Save">
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
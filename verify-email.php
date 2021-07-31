<?php
    include_once('./layouts/header.php');
    include_once('./controllers/commonFunctions.php');
    include_once('./controllers/users.php');
    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $result = emailVerification($token);
    }
?>
<!-- products-breadcrumb -->
<div class="products-breadcrumb">
    <div class="container">
        <ul>
        </ul>
    </div>
</div>
<!-- //products-breadcrumb -->
<div class="container mt-5" style="margin-top:90px;margin-bottom:80px;font-size:16px;">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 text-center">
                <h3 style="margin-bottom:30px;">Email verification</h3>
                <?php
                    if(isset($result['status'])){
                        if($result['status'] == 'true'){
                            ?>
                                <div class="alert alert-success">
                                    Email verified successfully.<br>
                                    Please <a href="./login.php">Click here</a> to login.
                                </div>
                            <?php
                        }else{
                            ?>
                            <div class="alert alert-danger">
                                <?=$result['message'];?>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </div>

<?php
    include_once('./layouts/footer.php');
?>
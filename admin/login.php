<?php
    session_start();
    require_once(dirname(__DIR__).'/controllers/users.php');
    $result = userLogin('superAdmin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link rel="stylesheet" href="./layout/style.css">

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<title>Admin Dashboard | GroceryStore</title>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-sm-6 offset-md-3">
                <div class="card">
                    <div class="card-header bg-success text-light">
                        <h4 class="text-center">Create Supplier Account</h4>
                    </div>
                    <div class="card-body py-5">
                        <?php
                            if(isset($result['status'])){
                                ?>
                                    <div class="alert alert-danger text-center"><?=$result['message'];?></div>
                                <?php
                            }
                        ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <div class="form-group">
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
</body>
</html>
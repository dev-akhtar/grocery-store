<?php
	session_start();
	if(!isset($_SESSION['userId']) || !isset($_SESSION['userType']) == 'supplier'){
		echo "<script>location.href = './login.php'</script>";
	}
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

	<script src="https://cdn.tiny.cloud/1/7lqzmkpctldyj2tkfi5r3uteqri6nwlwjkrkae0vtw3iwumy/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	<script>
	tinymce.init({
		selector: 'textarea#editor',
		menubar: false
	});
	</script>

	<title><?= $title;?></title>
</head>
<body>
<?php include_once('./layout/sidebar.php');?>
<div class="container-fluid master-container">
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
	<div class="container-fluid">
		<span class="nav-text text-white font-weight-bold">
			Welcome, <?= isset($_SESSION['name']) ? $_SESSION['name'] : "" ?>
		</span>
		  <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
			<!-- <li class="nav-item active">
			  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			</li> -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-user"></i>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
				<a class="dropdown-item" href="./logout.php">Logout</a>
				</div>
			</li>
		  </ul>
		</div>
</nav>

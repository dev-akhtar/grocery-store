<?php
  include_once('./controllers/categories.php');
  $categoriesResult = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="./layouts/style.css">
	<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript" src="./layouts/app.js"></script>

	<title>GroceryStore</title>
</head>
<body>

<script>
  let addCartItem = (item)=>{
    item = JSON.parse(item);
    item.orderQuantity = 1;
    let orderQuantity = 1;
    let cartItem = JSON.stringify(item);
    let itemId = "cartItem" + item.id;
    if(localStorage.getItem(itemId)!=null){
      let existingItem = JSON.parse(localStorage.getItem(itemId));
      if(existingItem.orderQuantity < item.quantity)
      {
        existingItem.orderQuantity += 1;
        existingItem = JSON.stringify(existingItem);
        localStorage.setItem(itemId,existingItem);
      }else{
        alert("Not enough items in the stock");
      }
    }else{
      localStorage.setItem(itemId,cartItem);
    }
    setCartItems();
  }

  let removeCartItem = (itemId)=>{
    itemId = "cartItem" + itemId;
    if(localStorage.getItem(itemId)!=null){
      localStorage.removeItem(itemId);
      setCartItems();
    }
  }

  let setCartItems = ()=>{
    let totalPrice = 0;
    let flag = 0;
    let htmlData = "<tr><th colspan='2'>Product</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>";
    for(var i=0, len=localStorage.length; i<len; i++) {
        let key = localStorage.key(i);
        let regexp = /^cartItem([0-9]+)$/;
        if(key.match(regexp)){
          flag++;
          let item = JSON.parse(localStorage[key]);
          totalPrice += item.orderQuantity*item.price;
          htmlData += "<tr><td><img src='./supplier/images/"+ item.image +"' width='60px' alt=''></td> <td>" +item.title+"</td> <td style='max-width: 100px;'>&#8377;"+item.price+"</td><td style='max-width: 60px;'>"+item.orderQuantity+"</td><td>&#8377;"+(item.orderQuantity*item.price)+"</td><td style='max-width: 60px;'><button onclick='removeCartItem("+item.id+")' class='btn btn-sm btn-danger'><i class='fa fa-times'></i></button></td></tr>";
        }
    }
    if(flag == 0){
      $("#checkoutBtn").addClass("disabled");
      htmlData += "<tr><td colspan='6' class='pt-5'>Cart is empty<td></tr>";
    }else{
      $("#checkoutBtn").removeClass('disabled');
    }
    $("#cartTable").html(htmlData);
    $("#totalPrice").text(totalPrice);
    $("#cartItemsCount").text(flag);
    $("#cartItemsCount2").text(flag);
  }

  $(document).ready(()=>{
    setCartItems();
  })
</script>

<div class="overlay"></div>

<nav class="navbar navbar-expand-md navbar-light bg-light main-menu fixed-top" style="box-shadow:none">
  <div class="container">

    <button type="button" id="sidebarCollapse" class="btn btn-link d-block d-md-none">
                <i class="bx bx-menu icon-single"></i>
            </button>

    <a class="navbar-brand" href="./">
      <h4 class="font-weight-bold"><span class="text-success">Grocery</span><span class="text-dark">Store</span></h4>
    </a>

    <ul class="navbar-nav ml-auto d-block d-md-none">
      <li class="nav-item">
        <a class="btn btn-link" data-toggle="modal" data-target="#cartModal" href="#"><i class="bx bxs-cart icon-single"></i> <span id="cartItemsCount2" class="badge badge-danger">0</span></a>
      </li>
    </ul>

    <div class="collapse navbar-collapse">
      <form class="form-inline my-2 my-lg-0 mx-auto">
        <input class="form-control" type="search" placeholder="Search for products..." aria-label="Search">
        <button class="btn btn-success my-2 my-sm-0" type="submit"><i class="bx bx-search"></i></button>
      </form>

      <?php if(isset($_SESSION['userId'])){?>
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="btn btn-link mt-2" id = 'cartButton' data-toggle="modal" data-target="#cartModal" href="#"><i class="bx bxs-cart icon-single"></i> <span id="cartItemsCount" class="badge badge-danger"></span></a>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/fox.jpg" width="40" height="40" class="rounded-circle img-thumbnail">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="user/dashboard.php">Dashboard</a>
          <a class="dropdown-item" href="user/update-profile.php">Edit Profile</a>
          <a class="dropdown-item" href="logout.php">Log Out</a>
        </div>
      </li>
      </ul>
      <?php } else { ?>
        <ul class="navbar-nav">
        <li class="nav-item mr-3">
          <a class="btn btn-link" id = 'cartButton' data-toggle="modal" data-target="#cartModal" href="#"><i class="bx bxs-cart icon-single bx-lg"></i> <span id="cartItemsCount" class="badge badge-danger"></span></a>
        </li>
        <li class="nav-item mr-2">
          <a class="nav-link btn btn-dark text-white" href="signup.php">Signup</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-success text-white" href="login.php">Login</a>
        </li>
       </ul>
     <?php } ?>
    </div>

  </div>
</nav>

<nav class="navbar navbar-expand-md navbar-light bg-light sub-menu">
  <div class="container">
    <div class="collapse navbar-collapse" id="navbar">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            All Categories
                        </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <?php
                if($categoriesResult->num_rows > 0){
                    while($catRow = $categoriesResult->fetch_assoc()){
                        ?>                        
                          <a class="dropdown-item" href="category.php?cid=<?=$catRow['id'];?>"><?=$catRow['name'];?></a>
                        <?php
                    }
                }
            ?>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Offers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Wishlist</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="search-bar d-block d-md-none">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <form class="form-inline mb-3 mx-auto">
          <input class="form-control" type="search" placeholder="Search for products..." aria-label="Search">
          <button class="btn btn-success" type="submit"><i class="bx bx-search"></i></button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Sidebar -->
<nav id="sidebar">
  <div class="sidebar-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-10 pl-0">
          <a class="btn btn-success" href="#"><i class="bx bxs-user-circle mr-1"></i> Log In</a>
        </div>

        <div class="col-2 text-left">
          <button type="button" id="sidebarCollapseX" class="btn btn-link">
                            <i class="bx bx-x icon-single"></i>
                        </button>
        </div>
      </div>
    </div>
  </div>

  <ul class="list-unstyled components links">
    <li class="active">
      <a href="#"><i class="bx bx-home mr-3"></i> Home</a>
    </li>
    <li>
      <a href="#"><i class="bx bx-carousel mr-3"></i> Products</a>
    </li>
    <li>
      <a href="#"><i class="bx bx-book-open mr-3"></i> Schools</a>
    </li>
    <li>
      <a href="#"><i class="bx bx-crown mr-3"></i> Publishers</a>
    </li>
    <li>
      <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="bx bx-help-circle mr-3"></i>
                    Support</a>
      <ul class="collapse list-unstyled" id="pageSubmenu">
        <li>
          <a href="#">Delivery Information</a>
        </li>
        <li>
          <a href="#">Privacy Policy</a>
        </li>
        <li>
          <a href="#">Terms & Conditions</a>
        </li>
      </ul>
    </li>
    <li>
      <a href="#"><i class="bx bx-phone mr-3"></i> Contact</a>
    </li>
  </ul>

  <h6 class="text-uppercase mb-1">Categories</h6>
  <ul class="list-unstyled components mb-3">
    <li>
      <a href="#">Category 1</a>
    </li>
    <li>
      <a href="#">Category 1</a>
    </li>
    <li>
      <a href="#">Category 1</a>
    </li>
    <li>
      <a href="#">Category 1</a>
    </li>
    <li>
      <a href="#">Category 1</a>
    </li>
    <li>
      <a href="#">Category 1</a>
    </li>
  </ul>

  <ul class="social-icons">
    <li><a href="#" target="_blank" title=""><i class="bx bxl-facebook-square"></i></a></li>
    <li><a href="#" target="_blank" title=""><i class="bx bxl-twitter"></i></a></li>
    <li><a href="#" target="_blank" title=""><i class="bx bxl-linkedin"></i></a></li>
    <li><a href="#" target="_blank" title=""><i class="bx bxl-instagram"></i></a></li>
  </ul>

</nav>

<!-- ===================Cart modal===================== -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="cartModalLabel">Cart</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body pb-0">
        <table class="table" id="cartTable">
        </table>
            <div class="d-flex justify-content-end">
          <h5>Total: <span class="price text-success">&#8377;<span id="totalPrice">3200</span></span></h5>
        </div>
      </div>
      <div class="modal-footer">
        <a id="checkoutBtn" class="btn btn-success" href="./checkout.php">Checkout</a>
      </div>
    </div>
  </div>
</div>
<!--===============================End cart modal===============================-->

<!-- ==================Slider=================== -->

<?php
  $host =  $_SERVER['REQUEST_URI'];
  $splitStr = explode("/",$host);
  $filename = end($splitStr);
  if($filename != 'signup.php' && $filename != 'login.php'){
?>
<div id="carouselExampleIndicators" class="carousel slide border-bottom" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="./images/1.jpg" alt="First slide">
      <div class="carousel-caption d-none d-md-block">
          <h1 class="banner-text">MAKE YOUR<br>
            FOOD<br>
            WITH SPICY.
          </h1>
        <a class="btn btn-success px-5 mt-2" href="#">Shop Now</a>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="./images/2.jpg" alt="Second slide">
      <div class="carousel-caption d-none d-md-block">
          <h1 class="banner-text">MAKE YOUR<br>
            FOOD<br>
            WITH SPICY.
          </h1>
        <a class="btn btn-success px-5 mt-2" href="#">Shop Now</a>
      </div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="./images/3.jpg" alt="Third slide">
      <div class="carousel-caption d-none d-md-block">
          <h1 class="banner-text">SUPER SALE DHAMAKA<br>
            UPTO 50% OFF
          </h1>
        <a class="btn btn-success px-5 mt-2" href="#">Shop Now</a>
      </div>
    </div>
  </div>
</div>
<?php }?>

<!-- ==================End Slider=================== -->
<?php
session_start();
include_once('./layouts/header.php');
include_once('./controllers/products.php');
?>

<!-- ==================Beverages & drinks=================== -->
<div class="container mt-5 mb-5">

    <div class="row">
      <div class="col-sm-12">
        <!-- Section Title -->
        <div class="section-title text-center mb-5">
          <h4>Beverages & Drinks</h4>
        </div>
      </div>
    </div>

  <div class="row">

    <?php
      $beverageProducts = getAllProducts(null,7,4);
      if($beverageProducts->num_rows > 0){
          while($pRow = $beverageProducts->fetch_assoc()){
              ?>
              <div class="col-lg-3 col-md-6 mb-4">
                  <!-- Card -->
                  <div class="card h-100">
                      <a class="mx-auto" href="./product.php?pid=<?=$pRow['id'];?>">
                          <img class="card-img-top product-image mx-auto" src="./supplier/images/<?= $pRow['image'];?>" alt="<?= $pRow['title'];?>">
                      </a>

                      <div class="card-body text-center py-2">
                          <h6 class="mt-1"><?= $pRow['title'];?></h6>
                          <p class="small text-muted text-uppercase"><?= $pRow['categoryName'];?></p>
                          <hr>
                          <h6 class="mb-3">
                              <span class="text-danger mr-1">&#8377;<?= $pRow['price'];?></span>
                              <span class="text-grey"><s>&#8377;<?=floor(($pRow['price']+(0.15*$pRow['price'])))?></s></span>
                          </h6>
                          <button type="button" class="btn btn-success mr-1" onclick='addCartItem(`<?=json_encode($pRow);?>`)'>
                              <i class="fas fa-shopping-cart pr-2"></i>Add to cart
                          </button>
                          <button type="button" class="btn btn-danger px-3">
                              <i class="far fa-heart"></i>
                          </button>
                      </div>

                  </div>
                  <!-- Card -->
              </div>
            <?php
          }
      }else{
          echo "<div class='col-md-12 jumbotron ml-4 bg-white text-center'>Nothing to show</div>";
      }
  ?>

  </div>
</div>
<hr style="max-width: 90%;">
<!-- ==================Beverages & drinks=================== -->

<!-- ==================Fresh vegetables=================== -->
<div class="container mt-5 mb-5">

    <div class="row">
      <div class="col-sm-12">
        <!-- Section Title -->
        <div class="section-title text-center mb-5">
          <h4>Fresh Vegetables</h4>
        </div>
      </div>
    </div>

  <div class="row">

    <?php
      $beverageProducts = getAllProducts(null,10,4);
      if($beverageProducts->num_rows > 0){
          while($pRow = $beverageProducts->fetch_assoc()){
              ?>
              <div class="col-lg-3 col-md-6 mb-4">
                  <!-- Card -->
                  <div class="card h-100">
                      <a class="mx-auto" href="./product.php?pid=<?=$pRow['id'];?>">
                          <img class="card-img-top product-image mx-auto" src="./supplier/images/<?= $pRow['image'];?>" alt="<?= $pRow['title'];?>">
                      </a>

                      <div class="card-body text-center py-2">
                          <h6 class="mt-1"><?= $pRow['title'];?></h6>
                          <p class="small text-muted text-uppercase"><?= $pRow['categoryName'];?></p>
                          <hr>
                          <h6 class="mb-3">
                              <span class="text-danger mr-1">&#8377;<?= $pRow['price'];?></span>
                              <span class="text-grey"><s>&#8377;<?=floor(($pRow['price']+(0.15*$pRow['price'])))?></s></span>
                          </h6>
                          <button type="button" class="btn btn-success mr-1" onclick='addCartItem(`<?=json_encode($pRow);?>`)'>
                              <i class="fas fa-shopping-cart pr-2"></i>Add to cart
                          </button>
                          <button type="button" class="btn btn-danger px-3">
                              <i class="far fa-heart"></i>
                          </button>
                      </div>

                  </div>
                  <!-- Card -->
              </div>
            <?php
          }
      }else{
          echo "<div class='col-md-12 jumbotron ml-4 bg-white text-center'>Nothing to show</div>";
      }
  ?>

  </div>
</div>
<hr style="max-width: 90%;">
<!-- ==================Fresh foods & vegetables=================== -->

<?php
require_once('./layouts/footer.php');
?>
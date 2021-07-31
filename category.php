<?php
session_start();
include_once('./layouts/header.php');
include_once('./controllers/products.php');
if(isset($_GET['cid'])){
    $cid = $_GET['cid'];
    $allProducts = getAllProducts(null,$cid);
}else{
    $allProducts = getAllProducts(null,null);
}
?>

<!-- ==================Best offers=================== -->
<div class="container mt-5 mb-5">

    <div class="row">
        <div class="col-lg-3 px-0 mt-2">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    All Categories
                </div>
                <div class="card-body px-0 py-0">
                    <ul class="list-group list-group-flush">
                        <?php
                            if($categoriesResult->num_rows > 0){
                                mysqli_data_seek($categoriesResult,0);
                                while($catRow = $categoriesResult->fetch_assoc()){
                                    ?>
                                    <li class="list-group-item p-0"><a class="d-block text-secondary p-3 c-link" href="category.php?cid=<?=$catRow['id'];?>"><?=$catRow['name'];?></a></li>
                                    <?php
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <!-- Section Title -->
            <div class="section-title text-center mb-5">
                <h4>Category</h4>
            </div>

            <div class="row">

                <?php
                    if($allProducts->num_rows > 0){
                        while($pRow = $allProducts->fetch_assoc()){
                            ?>
                            <div class="col-lg-4 col-md-6 mb-4">
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
    </div>
</div>
<!-- ==================End best offsers=================== -->

<?php
require_once('./layouts/footer.php');
?>
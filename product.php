<?php
session_start();
include_once('./layouts/header.php');
include_once('./controllers/products.php');
if(isset($_GET['pid'])){
    $pid = $_GET['pid'];
    $productDetailResult = getProductById($pid);
    if($productDetailResult->num_rows > 0){
        $productDetail = $productDetailResult->fetch_array();
    }else{
        echo "<script>location.href='./'</script>";
    }
}else{
    echo "<script>location.href='./'</script>";
}
?>

<!-- ==================Best offers=================== -->
<div class="container mt-5 mb-5">

    <div class="row">
        <div class="col-lg-3 px-0 order-lg-1 order-md-2 my-lg-0 my-md-5">
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
        <div class="col-lg-9 order-lg-2 order-md-1">
            <!-- Section Title -->
            <!-- <div class="section-title text-center mb-5">
                <h4>Category</h4>
            </div> -->

            <div class="row align-items-center">
                <?php
                    if(isset($productDetail)){
                            ?>
                            <div class="col-lg-4 mb-lg-0 mb-md-5 mt-lg-0 mt-md-5 text-center">
                                <img class="mx-auto" src="./supplier/images/<?= $productDetail['image'];?>" alt="<?= $productDetail['title'];?>">
                            </div>
                            
                            <div class="col-lg-8">
                                <h4 class="mt-1"><?= $productDetail['title'];?></h4>
                                <p class="small text-muted text-uppercase"><?= $productDetail['categoryName'];?></p>
                                <hr>
                                <p><?=$productDetail['description'];?></p>
                                <h6 class="mb-3">
                                    <span class="text-danger mr-1">&#8377;<?= $productDetail['price'];?></span>
                                    <span class="text-grey"><s>&#8377;<?=floor(($productDetail['price']+(0.15*$productDetail['price'])))?></s></span>
                                </h6>
                                <button type="button" class="btn btn-success mr-1" onclick='addCartItem(`<?=json_encode($productDetail);?>`)'>
                                    <i class="fas fa-shopping-cart pr-2"></i>Add to cart
                                </button>
                                <a href="checkout.php" class="btn btn-dark px-3">Checkout</a>
                            </div>
                            <?php
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
<?php
    $title = "Products | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/categories.php');
    include_once(dirname(__DIR__).'/controllers/products.php');
    if(isset($_GET['pid'])){
        $deleteResult = deleteProduct($_GET['pid']);
    }
    $allProducts = getAllProducts($_SESSION['userId']);
?>

    <div class="main-container">
        <div class="row">
            <div class="col-sm-12 pt-3">
                <?php
                    if(isset($deleteResult['status']) && $deleteResult['status'] == 'true'){
                        ?>
                            <div class="text-success text-center pt-3"><?=$deleteResult['message'];?></div>
                        <?php
                    }elseif(isset($deleteResult['status']) && $deleteResult['status'] == 'false'){
                        ?>
                            <div class="text-danger text-center pt-3"><?=$deleteResult['message'];?></div>
                        <?php
                    }
                ?>
                <a class="btn btn-danger mt-2" href="./add-product.php">Add Product</a>
                <table class="table table-bordered mt-2">
                    <tr class="bg-dark text-light text-center">
                        <th>Product Title</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Preview</th>
                        <th colspan="2">Action</th>
                    </tr>
                    <?php
                        if($allProducts->num_rows > 0){
                            while($row = $allProducts->fetch_assoc()){
                                ?>
                                    <tr>
                                        <td><?= $row['title']; ?></td>
                                        <td><?= $row['price']; ?></td>
                                        <td><?= $row['categoryName']; ?></td>
                                        <td style="witdh:60px;"><?= $row['quantity']; ?></td>
                                        <td class="text-cener"><img src="./images/<?= $row['image']; ?>" width="80px" height="50px"/></td>
                                        <td style="width:40px;"><a href="./edit-product.php?pid=<?=$row['id'];?>"><i class="fas fa-pencil-alt"></i></a></td>
                                        <td style="width:40px;"><a class="text-danger" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?pid=<?= $row['id']; ?>"><i class="fas fa-trash-alt"></i></a></td>
                                    </tr>
                                <?php
                            }
                        }else{
                            echo "<tr><td class='text-center py-5' colspan='6'>No record found</td></tr>";
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>

<?php
    include_once('./layout/footer.php');
?>
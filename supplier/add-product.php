<?php
    $title = "Add Products | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/categories.php');
    include_once(dirname(__DIR__).'/controllers/products.php');
    $result = addProduct();
    $updateResult = editCategory();
    if(isset($_GET['id'])){
        $deleteResult = deleteCategory();
    }
    $allCategories = getAllCategories();
    $allMeasureTypes = getAllMeasureTypes();
?>
    <div class="main-container">              
               <div class="row mt-4">
                   <div class="col-sm-8 offset-sm-2">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Add Product
                            </div>
                            <div class="card-body">

                                <div class="container">
                                <?php
                                    if(isset($result['status']) && $result['status'] == 'true'){
                                        ?>
                                            <div class="text-success text-center pt-3"><?=$result['message'];?></div>
                                        <?php
                                    }elseif(isset($updateResult['status']) && $updateResult['status'] == 'true'){
                                        ?>
                                            <div class="text-success text-center pt-3"><?=$updateResult['message'];?></div>
                                        <?php
                                    }elseif(isset($deleteResult['status']) && $deleteResult['status'] == 'true'){
                                        ?>
                                            <div class="text-success text-center pt-3"><?=$deleteResult['message'];?></div>
                                        <?php
                                    }elseif(isset($deleteResult['status']) && $deleteResult['status'] == 'false'){
                                        ?>
                                            <div class="text-danger text-center pt-3"><?=$deleteResult['message'];?></div>
                                        <?php
                                    }
                        
                                if(isset($result['status']) && $result['status'] == 'false'){
                                    ?>
                                        <div class="alert alert-danger text-center"><?=$result['message'];?></div>
                                    <?php
                                }
                            ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="supplierId">
                            <div class="form-group pt-4">
                                <input class="form-control" type="text" name="title" placeholder="Product title" required=" ">
                            </div>
                
                            <div class="form-group pt-4">
                                <input class="form-control" type="number" name="quantity" placeholder="quantity" required=" ">
                            </div>

                            <div class="form-group pt-4">
                                <input class="form-control" type="number" name="price" placeholder="Price in Rupees" required=" ">
                            </div>

                            <div class="form-group pt-4">
                                <select class="form-control" name="product_scale" id="product_scale">
                                    <option hidden>Choose scale</option>
                                    <?php 
                                        if($allMeasureTypes->num_rows > 0){
                                            while($row = $allMeasureTypes->fetch_assoc()){
                                                ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['name'];?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                
                            <div class="form-group pt-4">
                                <select class="form-control" name="category" id="category">
                                    <option hidden>Categories</option>
                                    <?php 
                                        if($allCategories->num_rows > 0){
                                            while($row = $allCategories->fetch_assoc()){
                                                ?>
                                                    <option value="<?= $row['id']; ?>"><?= $row['name'];?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <input type="file" name="image" class="form-control">
                            </div>
                
                            <div class="form-group">
                                <textarea name="description" id="editor"></textarea>
                            </div>
                
                            <div class="form-group pb-4">
                                <input class="btn btn-success" type="submit" name="addProduct" value="Save">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
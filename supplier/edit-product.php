<?php
    $title = "Update Product Details | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/categories.php');
    include_once(dirname(__DIR__).'/controllers/products.php');
    if(isset($_GET['pid'])){
        $productId = $_GET['pid'];
    }
    $updateResult = editProduct();
    $getProduct = getProductById($productId);
    if($getProduct->num_rows>0){
        $productDetail = $getProduct->fetch_array();
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
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?pid=<?=$productDetail['id']?>" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="productId" value="<?= $productId?>">
                            <div class="form-group pt-2">
                                <label for="product title">Product Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Product title" required=" " value="<?= $productDetail['title']?>">
                            </div>
                
                            <div class="form-group pt-2">
                            <label for="quantity">Quantity</label>
                                <input class="form-control" type="number" name="quantity" placeholder="quantity" required=" " value="<?= $productDetail['quantity']?>">
                            </div>

                            <div class="form-group pt-2">
                            <label for="price">Price</label>
                                <input class="form-control" type="number" name="price" placeholder="Price in Rupees" required=" " value="<?= $productDetail['price']?>">
                            </div>

                            <div class="form-group pt-2">
                            <label for="Measurement Scale">Measurement Scale</label>
                                <select class="form-control" name="product_scale" id="product_scale">
                                    <option hidden>Choose scale</option>
                                    <?php 
                                        if($allMeasureTypes->num_rows > 0){
                                            while($row = $allMeasureTypes->fetch_assoc()){
                                                ?>
                                                    <option <?= $row['id']==$productDetail['measureType']?'selected':''?> value="<?= $row['id']; ?>"><?= $row['name'];?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                
                            <div class="form-group pt-2">
                                <label for="category">Category</label>
                                <select class="form-control" name="category" id="category">
                                    <option hidden>Choose category</option>
                                    <?php 
                                        if($allCategories->num_rows > 0){
                                            while($row = $allCategories->fetch_assoc()){
                                                ?>
                                                    <option <?= $row['id']==$productDetail['category']?'selected':''?> value="<?= $row['id']; ?>"><?= $row['name'];?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                
                            <div class="form-group">
                                <select class="form-control" name="status" id="status">
                                    <option <?= $productDetail['isActive']==1?'selected':''?> value="1">Enable</option>
                                    <option <?= $productDetail['isActive']==0?'selected':''?> value="0">Disable</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="image">Upload image</label>
                                <input type="file" name="image" class="form-control">
                            </div>
                
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="editor">
                                    <?= $productDetail['description']?>
                                </textarea>
                            </div>
                
                            <div class="form-group pb-4">
                                <input class="btn btn-success" type="submit" name="editProduct" value="Save">
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
<?php
    $title = "Categories | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/categories.php');
    $result = addCategory();
    $updateResult = editCategory();
    if(isset($_GET['id'])){
        $deleteResult = deleteCategory();
    }
    $allCategories = getAllCategories();
    if(isset($result['status']) && $result['status'] == 'false'){
        echo "<script> $(document).ready(function(){ $('#categoryForm').modal('show');});</script>";
    }elseif(isset($updateResult['status']) && $updateResult['status'] == 'false'){
        ?>
        <script>
            $(document).ready(function(){ 
                let id = "<?=$updateResult['value']['categoryId']?>";
                let name = "<?=$updateResult['value']['categoryName']?>";
                $('#categoryId').val(id);
                $('#categoryName').val(name);
                $('#editCategoryForm').modal('show');
            });
        </script>
    <?php
    }
?>
    <script>
        function addIdToEditForm(id,name){
            $('#categoryId').val(id);
            $('#categoryName').val(name);
        }
        $('#editButton').click((e)=>{
            e.preventDefault();
        })
    </script>
    <div class="main-container">
        <div class="row">
            <div class="col-sm-12 pt-3">
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
                ?>
                <button class="btn btn-danger mt-2" data-toggle="modal" data-target="#categoryForm">Add New Category</button>
                <table class="table table-bordered mt-2">
                    <tr class="bg-dark text-light">
                        <th style="width:40px;">ID</th>
                        <th>Name</th>
                        <th colspan="2">Action</th>
                    </tr>
                    <?php 
                        if($allCategories->num_rows > 0){
                            while($row = $allCategories->fetch_assoc()){
                                ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= $row['name']; ?></td>
                                        <td style="width:40px;"><a href="#" id = 'editButton'class="transparent-btn" data-toggle="modal" data-target="#editCategoryForm" onclick="addIdToEditForm(<?= $row['id'];?>,`<?= strval($row['name']);?>`)"><i class="fas fa-pencil-alt"></i></a></td>
                                        <td style="width:40px;"><a class="text-danger" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?id=<?= $row['id']; ?>"><i class="fas fa-trash-alt"></i></a></td>
                                    </tr>
                                <?php
                            }
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>

<!-- ===================Add category model===================== -->
<div class="modal fade" id="categoryForm" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Add new category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <?php
                if(isset($result['status']) && $result['status'] == 'false'){
                    ?>
                        <div class="alert alert-danger text-center"><?=$result['message'];?></div>
                    <?php
                }
            ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <div class="form-group pt-4">
                <input class="form-control" type="text" name="name" placeholder="Category" required=" ">
            </div>

            <div class="form-group pb-4">
                <input class="btn btn-success" type="submit" name="submit" value="Save">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ===================Edit category model===================== -->
<div class="modal fade" id="editCategoryForm" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <?php
                if(isset($updateResult['status']) && $updateResult['status'] == 'false'){
                    ?>
                        <div class="alert alert-danger text-center"><?=$updateResult['message'];?></div>
                    <?php
                }
            ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <input type="hidden" name="categoryId" id="categoryId" value="">
            <div class="form-group pt-4">
                <input class="form-control" type="text" name="categoryName" id="categoryName" value="" placeholder="Category" required=" ">
            </div>

            <div class="form-group pb-4">
                <input class="btn btn-success" type="submit" name="editCategory" value="Save">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--==============================================================-->

<?php
    include_once('./layout/footer.php');
?>
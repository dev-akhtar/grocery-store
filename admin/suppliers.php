<?php
    $title = "Manage Suppliers | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/supplier.php');
    $updateResult = editSupplier();
    if(isset($_GET['sid']) && !isset($_GET['action'])){
        $deleteResult = deleteSupplier();
    }elseif(isset($_GET['sid']) && isset($_GET['action'])){
        $deleteResult = reviewSupplier();
    }
    $allSuppliers = getAllSuppliers('Active');
    $pendingSupplier = getAllSuppliers('Pending');
    $rejectedSupplier = getAllSuppliers('Rejected');
    if(isset($result['status']) && $result['status'] == 'false'){
        echo "<script> $(document).ready(function(){ $('#categoryForm').modal('show');});</script>";
    }elseif(isset($updateResult['status']) && $updateResult['status'] == 'false'){
        ?>
        <script>
            $(document).ready(function(){ 
                let id = "<?=$updateResult['value']['id']?>";
                let name = "<?=$updateResult['value']['name']?>";
                let company = "<?=$updateResult['value']['company']?>";
                let phone = "<?=$updateResult['value']['phone']?>";
                $('#id').val(id);
                $('#name').val(name);
                $('#company').val(company);
                $('#phone').val(phone);
                $('#categoryForm').modal('show');
            });
        </script>
    <?php
    }
?>
    <script>
        function addIdToEditForm(id,name,company,phone){
            $('#id').val(id);
            $('#name').val(name);
            $('#company').val(company);
            $("#phone").val(phone);
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
                                
                <ul class="nav nav-pills mb-2 mt-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-active-tab" data-toggle="pill" href="#pills-active" role="tab" aria-controls="pills-active" aria-selected="true">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-pending-tab" data-toggle="pill" href="#pills-pending" role="tab" aria-controls="pills-pending" aria-selected="false">Pending</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-rejected-tab" data-toggle="pill" href="#pills-rejected" role="tab" aria-controls="pills-rejected" aria-selected="false">Rejected</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-active" role="tabpanel" aria-labelledby="pills-active-tab">
                    <table class="table table-bordered">
                        <tr class="bg-dark text-light text-center">
                            <th style="width:40px;">ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                        <?php 
                            if($allSuppliers->num_rows > 0){
                                while($row = $allSuppliers->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['company_name']; ?></td>
                                            <td><?= $row['status']; ?></td>
                                            <td style="width:40px;"><a href="#" id = 'editButton'class="transparent-btn" data-toggle="modal" data-target="#categoryForm" onclick="addIdToEditForm(`<?= $row['supplier_id'];?>`,`<?= $row['name'];?>`,`<?= $row['company_name'];?>`,`<?= $row['phone'];?>`)"><i class="fas fa-pencil-alt"></i></a></td>
                                            <td style="width:40px;"><a class="text-danger" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?sid=<?= $row['supplier_id']; ?>"><i class="fas fa-trash-alt"></i></a></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                echo "<tr><td class='text-center py-5' colspan='6'>No record found</td></tr>";
                            }
                        ?>
                    </table>
                </div>
                <div class="tab-pane fade" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab">
                <table class="table table-bordered">
                        <tr class="bg-dark text-light text-center">
                            <th style="width:40px;">ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th colspan="2">Action</th>
                        </tr>
                        <?php 
                            if($pendingSupplier->num_rows > 0){
                                while($row = $pendingSupplier->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['company_name']; ?></td>
                                            <td><?= $row['status']; ?></td>
                                            <td style="width:40px;"><a class="btn btn-success" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?sid=<?= $row['supplier_id']; ?>&action=approve">Approve</a></td>
                                            <td style="width:40px;"><a class="btn btn-danger text-white" href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?sid=<?= $row['supplier_id']; ?>&action=reject">Reject</a></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                echo "<tr><td class='text-center py-5' colspan='6'>No record found</td></tr>";
                            }
                        ?>
                    </table>
                </div>
                <div class="tab-pane fade" id="pills-rejected" role="tabpanel" aria-labelledby="pills-rejected-tab">
                    <table class="table table-bordered">
                        <tr class="bg-dark text-light text-center">
                            <th style="width:40px;">ID</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Applied Date</th>
                        </tr>
                        <?php 
                            if($rejectedSupplier->num_rows > 0){
                                while($row = $rejectedSupplier->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['name']; ?></td>
                                            <td><?= $row['company_name']; ?></td>
                                            <td><?= $row['status']; ?></td>
                                            <td><?= $row['createdAt']; ?></td>
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
        </div>
    </div>

<!-- ===================Add category model===================== -->
<div class="modal fade" id="categoryForm" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Update Supplier</h5>
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
            <input type="hidden" name="id" id="id" value="">
            <div class="form-group pt-4">
                <input class="form-control" type="text" name="name" id="name" placeholder="Full Name" required=" ">
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="company" id="company" placeholder="Company Name" required=" ">
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="phone" id="phone" placeholder="Phone" required=" ">
            </div>

            <div class="form-group pb-4">
                <input class="btn btn-success" type="submit" name="editSupplier" value="Save">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
    include_once('./layout/footer.php');
?>
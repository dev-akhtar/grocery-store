<?php
    $title = "Product Orders | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/supplier.php');
    include_once(dirname(__DIR__).'/controllers/products.php');
    include_once(dirname(__DIR__).'/controllers/orders.php');
    $updateResult = updateOrderStatus();
    $getUser = getUserByUserId($_SESSION['userId']);
    $userDetails = $getUser->fetch_array();
    $placedOrders = getOrders($userDetails['supplier_id'],'placed');
    $canceledOrders = getOrders($userDetails['supplier_id'],'canceled');
    $deliveredOrders = getOrders($userDetails['supplier_id'],'delivered');
    if(isset($updateResult['status']) && $updateResult['status' == 'false']){
        ?>
            <script>
                $(document).ready(function(){ 
                    let id = "<?=$updateResult['value']['statusId']?>";
                    let status = "<?=$updateResult['value']['status']?>";
                    let statusDescription = "<?=$updateResult['value']['description']?>";
                    $('#statusId').val(id);
                    $('option[value=' + status + ']').attr('selected',true);
                    $('#statusDescription').val(statusDescription);
                    $('#updateOrderStatus').modal('show');
                });
            </script>
        <?php
    }
?>
    <script>
        function insertDataToEditForm(id,status,description){
            let theValue = status;
            $('#statusId').val(id);
            $('option[value=' + theValue + ']').attr('selected',true);
            $('#statusDescription').val(description);
        }
    </script>
    <div class="main-container">
        <div class="row">
            <div class="col-sm-12">
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
                        <a class="nav-link active" id="pills-active-tab" data-toggle="pill" href="#pills-active" role="tab" aria-controls="pills-active" aria-selected="true">Placed Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-pending-tab" data-toggle="pill" href="#pills-pending" role="tab" aria-controls="pills-pending" aria-selected="false">Delivered Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-rejected-tab" data-toggle="pill" href="#pills-rejected" role="tab" aria-controls="pills-rejected" aria-selected="false">canceled Orders</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-active" role="tabpanel" aria-labelledby="pills-active-tab">
                    <table class="table table-bordered table-hover">
                        <tr class="bg-dark text-light text-center">
                            <th>Product Title</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Total Price</th>
                            <th>Customer Name</th>
                            <th>status</th>
                            <th>Date</th>
                        </tr>
                        <?php 
                            if($placedOrders->num_rows > 0){
                                while($prow = $placedOrders->fetch_assoc()){
                                    ?>
                                        <tr onclick="insertDataToEditForm(`<?=$prow['statusId']?>`,`<?=$prow['status']?>`,`<?=$prow['description']?>`)" data-toggle="modal" data-target="#updateOrderStatus">
                                            <td><?= $prow['productTitle']; ?></td>
                                            <td><?= $prow['quantity']; ?></td>
                                            <td><?= $prow['categoryName']; ?></td>
                                            <td><?= $prow['totalPrice']; ?></td>
                                            <td><?= $prow['name']; ?></td>
                                            <td>
                                                <?php
                                                    switch($prow['status']){
                                                        case 'placed' : echo "Order Placed";
                                                            break;
                                                        case 'bagged' : echo "Item Packed";
                                                            break;
                                                        case 'shipped' : echo "Order shipped";
                                                            break;
                                                        case 'outfordelivery' : echo "Out for delivery";
                                                            break;
                                                        case 'attemted' : echo "Attempted for delivery";
                                                            break;
                                                        default : echo 'No status';
                                                            break;
                                                    }
                                                ?>
                                            </td>
                                            <td><?= $prow['createdAt']; ?></td>
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
                            <th>Product Title</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Total Price</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                        </tr>
                        <?php 
                            if($deliveredOrders->num_rows > 0){
                                while($row = $deliveredOrders->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['productTitle']; ?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td><?= $row['categoryName']; ?></td>
                                            <td><?= $row['totalPrice']; ?></td>
                                            <td><?= $row['name']; ?></td>
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
                <div class="tab-pane fade" id="pills-rejected" role="tabpanel" aria-labelledby="pills-rejected-tab">
                <table class="table table-bordered">
                        <tr class="bg-dark text-light text-center">
                            <th>Product Title</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Total Price</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                        </tr>
                        <?php 
                            if($canceledOrders->num_rows > 0){
                                while($row = $canceledOrders->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['productTitle']; ?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td><?= $row['categoryName']; ?></td>
                                            <td><?= $row['totalPrice']; ?></td>
                                            <td><?= $row['name']; ?></td>
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

<!-- ===================Update order status model===================== -->
<div class="modal fade" id="updateOrderStatus" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Update order status</h5>
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
            <input type="hidden" name="statusId" id="statusId" value="">
            <div class="form-group pt-4">
                <select class="form-control" name="status" id="status">
                    <option value="placed">Order Placed</option>
                    <option value="bagged">Item Bagged</option>
                    <option value="shipped">Order Shipped</option>
                    <option value="outfordelivery">Out for delivery</option>
                    <option value="delivered">Order delivered</option>
                    <option value="attemted">Attempted to deliver</option>
                    <option value="canceled">Order Canceled</option>
                </select>
            </div>

            <div class="form-group">
                <textarea class="form-control" name="statusDescription" id="statusDescription" class="statusDescription" rows="8" required></textarea>
            </div>

            <div class="form-group pb-4">
                <input class="btn btn-success" type="submit" name="updateOrderStatus" value="Update Status">
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
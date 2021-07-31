<?php
    include_once('./layouts/header.php');
    include_once(dirname(__DIR__).'/controllers/orders.php');
    if(isset($_GET['oid']) && $_GET['action'] == 'cancel'){
        $cancelOrderResult = cancelOrder();
    }
    $placedOrders = getOrdersByUserId($_SESSION['id'],'placed');
    $canceledOrders = getOrdersByUserId($_SESSION['id'],'canceled');
    $deliveredOrders = getOrdersByUserId($_SESSION['id'],'delivered');
?>

<!-- ==================Best offers=================== -->
<div class="container mt-5 mb-5">

    <div class="row">
        <div class="col-lg-3 px-0 mt-2">
            <div class="card">
                <div class="card-header bg-success text-white text-center">
                    Dashboard
                </div>
                <div class="card-body px-0 py-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item p-0">
                            <a class="d-block text-secondary p-3 c-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="list-group-item p-0">
                            <a class="d-block text-secondary p-3 c-link" href="update-profile.php">Update Profile</a>
                        </li>
                        <li class="list-group-item p-0">
                            <a class="d-block text-secondary p-3 c-link" href="change-password.php">Change password</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9">

            <?php
                if(isset($cancelOrderResult['status']) && $cancelOrderResult['status'] = 'false'){
                    ?>
                    <div class="alert alert-danger"><?=$cancelOrderResult['message'];?></div>
                    <?php
                } elseif(isset($cancelOrderResult['status']) && $cancelOrderResult['status'] = 'true'){
                    ?>
                    <div class="alert alert-success"><?=$cancelOrderResult['message'];?></div>
                    <?php
                }
            ?>

            <!-- =====================Nav tabs=================== -->
            <ul class="nav nav-pills mb-2 mt-2" id="pills-tab" role="tablist">
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
                            <th>Total Price</th>
                            <th>status</th>
                            <th>Updated Date</th>
                            <th>Placed Date</th>
                            <th>Action</th>
                        </tr>
                        <?php 
                            if($placedOrders->num_rows > 0){
                                while($prow = $placedOrders->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $prow['productTitle']; ?></td>
                                            <td><?= $prow['quantity']; ?></td>
                                            <td><?= $prow['totalPrice']; ?></td>
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
                                            <td><?= $prow['statusUpdatDate']; ?></td>
                                            <td><?= $prow['createdAt']; ?></td>
                                            <td><a class="text-danger" href="dashboard.php/?oid=<?=$prow['id'];?>&action=cancel">Cancel</a></td>
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
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Updated Date</th>
                            <th>Placed on</th>
                        </tr>
                        <?php 
                            if($deliveredOrders->num_rows > 0){
                                while($row = $deliveredOrders->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $row['productTitle']; ?></td>
                                            <td><?= $row['quantity']; ?></td>
                                            <td><?= $row['totalPrice']; ?></td>
                                            <td>Delivered</td>
                                            <td><?= $row['statusUpdatDate']; ?></td>
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
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Updated Date</th>
                            <th>Placed on</th>
                        </tr>
                        <?php 
                            if($canceledOrders->num_rows > 0){
                                while($row = $canceledOrders->fetch_assoc()){
                                    ?>
                                        <tr>
                                            <td><?= $prow['productTitle']; ?></td>
                                            <td><?= $prow['quantity']; ?></td>
                                            <td><?= $prow['totalPrice']; ?></td>
                                            <td>Delivered</td>
                                            <td><?= $prow['statusUpdatDate']; ?></td>
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
                </div>
            <!-- =====================End Nav tabs================ -->
        </div>
    </div>
</div>
<!-- ==================End best offsers=================== -->

<?php
    include_once('./layouts/footer.php');
?>
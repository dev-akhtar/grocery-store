<?php
    $title = "Admin Dashboard | GroceryStore";
    include_once('./layout/header.php');
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');

    $stats = adminStats();
?>
    <div class="main-container">
        <div class="row">
            <div class="col-sm-12 pt-3">
                
                <div class="container">
                <div class="row">
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2><?= $stats['totalUsers'];?></h2>
                                </div>
                                <div class="card-footer tile1 text-white">
                                    REGISTERED USERS
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                <h2><?= $stats['totalSuppliers'];?></h2>
                                </div>
                                <div class="card-footer tile2 text-white">
                                    ACTIVE SUPPLIERS
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                <h2><?= $stats['totalProducts'];?></h2>
                                </div>
                                <div class="card-footer tile3 text-white">
                                    LISTED PRODUCTS
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2><?= $stats['totalOrders'];?></h2>
                                </div>
                                <div class="card-footer tile1 text-white">
                                    TOTAL ORDERS
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                <h2><?= $stats['deliveredOrders'];?></h2>
                                </div>
                                <div class="card-footer tile2 text-white">
                                    ORDERS DELIVERED
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="card text-center">
                                <div class="card-body">
                                <h2><?= $stats['canceledOrders'];?></h2>
                                </div>
                                <div class="card-footer tile3 text-white">
                                    ORDERS CANCELED
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

<?php
    include_once('./layout/footer.php');
?>
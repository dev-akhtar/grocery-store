<?php
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    include_once(dirname(__DIR__).'/controllers/products.php');
    include_once(dirname(__DIR__).'/config.php');
    
    function getOrders($supplierId,$status = null){
        global $conn;
        $sql = "SELECT co.id,co.order_id as orderId,co.product_id as productId,co.user_id as userId,co.quantity,co.total_price as totalPrice,co.created_at as createdAt,co.updated_at as updatedAt,p.product_title as productTitle,p.price as price,p.category,pc.name as categoryName,u.email,u.name,u.phone,os.id as statusId,os.status,os.description,os.updated_at as statusUpdatDate FROM customer_orders co LEFT JOIN products p ON co.product_id = p.id LEFT JOIN users u ON co.user_id = u.id LEFT JOIN product_categories pc on p.category = pc.id LEFT JOIN order_status os ON co.order_id = os.order_id WHERE p.supplier_id = '$supplierId'";
        if($status != null){
            if($status == 'delivered'){
                $sql .= " AND os.status ='delivered'";
            }elseif($status == 'canceled'){
                $sql .= " AND os.status ='canceled'";
            }else{
                $sql .= " AND os.status != 'canceled' AND os.status != 'delivered'";
            }
        }
        $result = $conn->query($sql);
        return $result;
    }

    function getOrdersByUserId($userId,$status = null){
        global $conn;
        $sql = "SELECT co.id,co.order_id as orderId,co.product_id as productId,co.user_id as userId,co.quantity,co.total_price as totalPrice,co.created_at as createdAt,co.updated_at as updatedAt,p.product_title as productTitle,p.price as price,p.category,pc.name as categoryName,os.id as statusId,os.status,os.description,os.updated_at as statusUpdatDate FROM customer_orders co LEFT JOIN products p ON co.product_id = p.id LEFT JOIN users u ON co.user_id = u.id LEFT JOIN product_categories pc on p.category = pc.id LEFT JOIN order_status os ON co.order_id = os.order_id WHERE co.user_id = '$userId'";
        if($status != null){
            if($status == 'delivered'){
                $sql .= " AND os.status ='delivered'";
            }elseif($status == 'canceled'){
                $sql .= " AND os.status ='canceled'";
            }else{
                $sql .= " AND os.status != 'canceled' AND os.status != 'delivered'";
            }
        }
        $result = $conn->query($sql);
        return $result;
    }

    function updateOrderStatus(){
        global $conn;
        if(isset($_POST['updateOrderStatus'])){
            $statusId = trimData($_POST['statusId']);
            $status = trimData($_POST['status']);
            $description = trimData($_POST['statusDescription']);
            
            $updateSql = "UPDATE order_status SET status = '$status',description = '$description' WHERE id = '$statusId'";
            if($conn->query($updateSql) === 'FALSE'){
                return ['status'=>'false','message'=>$conn->error];
            }else{
                return ['status'=>'true','message'=>'Order status updated successfully'];
            }
        }
    }

    function cancelOrder(){
        global $conn;
        if(isset($_GET['oid'])){
            $orderId = trimData($_POST['oid']);
            $userId = $_SESSION['id'];
            $getOrder = getOrdersByUserId($userId,'placed');
            if($getOrder->num_rows < 1){
                return ['status'=>'false','message'=>"Order not found"];                
            }
            $orderDetails = $getOrder->fetch_array();
            $mainId = $orderDetails['orderId'];
            $updateSql = "UPDATE order_status SET status = 'canceled' WHERE id = '$orderId' AND order_id = '$mainId'";
            if($conn->query($updateSql) === 'FALSE'){
                return ['status'=>'false','message'=>$conn->error];
            }else{
                return ['status'=>'true','message'=>'Your order cancelled successfully'];
            }
        }
    }

    function placeOrder(){
        try{

            global $conn;
            if(isset($_POST['placeOrder'])){
                $address = trimData($_POST['address']);
                $pincode = trimData($_POST['pincode']);
                $state = trimData($_POST['state']);
                $paymentMode = trimData($_POST['paymentMode']);
                $products = $_POST['products'];
                $products = json_decode($products);
                $userId = $_SESSION['userId'];
                $fullAddress = $address." ".$pincode." ".$state." India";
                $orderId = md5(uniqid($userId));
    
                if(!validateData($address,'string',5,50)){
                    $error = ["status"=>'false',"message" => "Please enter valid address"];
                    return $error;
                }

                if(!validateData($paymentMode,'number')){
                    $error = ["status"=>'false',"message" => "Invalid payment mode"];
                    return $error;
                }

                if(!validateData($state,'string',3,50)){
                    $error = ["status"=>'false',"message" => "Please enter valid state"];
                    return $error;
                }

                if(!validateData($pincode,'number')){
                    $error = ["status"=>'false',"message" => "Please enter valid pincode"];
                    return $error;
                }

                $checkUser = getUserByUserId(($userId));
                if($checkUser->num_rows < 1){
                    return ['status'=>'false','message'=>'Invalid user'];
                }
                $userDetail = $checkUser->fetch_array();
                $userNumId = $userDetail['id'];
                $productIdArray = [];

                $error = 0;
                $totalPrice = 0;
                $sql = "INSERT INTO customer_orders(order_id,product_id,user_id,quantity,total_price)";
                $sqlValues = "";
                $flag = 0;
                $orderIdArray = [];
                foreach($products as $element){
                    $checkProduct = getProductById(($element->id));
                    if($checkProduct->num_rows > 0){
                        $productResult = $checkProduct->fetch_array();
                        if($productResult['quantity'] < $element->qty){
                            $error = 1;
                            break;
                        }else{
                            $orderId = $orderId.$flag;
                            $productPrice = $productResult['price']*$element->qty;
                            $totalPrice += $productPrice;
                            $productId = $element->id;
                            array_push($productIdArray,$productId);
                            $qty = $element->qty;
                            $sqlValues .= $sqlValues== ""?" VALUES('$orderId','$productId','$userId','$qty','$productPrice')":",('$orderId','$productId','$userNumId','$qty','$productPrice')";
                            array_push($orderIdArray,$orderId);
                            $flag++;
                        }
                    }
                }
                $sql .= $sqlValues;

                if($error != 0){
                    return ['status'=>'false','message'=>'Order could not be placed, please try again'];
                }
                
                $conn->query('START TRANSACTION');
                $orderResult = $conn->query($sql);
                $sqlValues = "";
                $orderStatusSql = "INSERT INTO order_status(order_id)";
                foreach($orderIdArray as $oid){                
                    $sqlValues .= $sqlValues == ""?" VALUES('$orderId')":", ('$orderId')";
                }
                $orderStatusSql .= $sqlValues;
                $orderStatusResult = $conn->query($orderStatusSql);
                
                $sqlValues = "";
                $orderAddressSql = "INSERT INTO order_address_lookup(order_id,address)";
                foreach($orderIdArray as $oid){                
                    $sqlValues .= $sqlValues == ""?" VALUES('$orderId','$fullAddress')":", ('$orderId','$fullAddress')";
                }
                $orderAddressSql .= $sqlValues;
                $conn->query($orderAddressSql);
    
                $sqlValues = "";
                $orderPaymentSql = "INSERT INTO order_payment_lookup(order_id,payment_mode,price)";
                foreach($orderIdArray as $oid){                
                    $sqlValues .= $sqlValues == ""?" VALUES('$orderId','$paymentMode','$totalPrice')":", ('$orderId','$paymentMode','$totalPrice')";
                }
                $orderPaymentSql .= $sqlValues;
                $conn->query($orderPaymentSql);

                $ids = join("','",$productIdArray);
                $updateProductSql = "UPDATE products SET quantity = quantity-1 WHERE id IN('$ids')";
                $updateResult = $conn->query($updateProductSql);
                $productIdArray = explode(',',$ids);
                foreach($productIdArray as $pid){
                    $pid = (int)$pid;
                    $getProduct = getProductById(($pid)) or die($conn->error);
                    if($getProduct->num_rows > 0){
                        $productDetail = $getProduct->fetch_array();
                        if((int)$productDetail['quantity'] < 1){
                            $changeProductStatus = $conn->query("UPDATE products SET is_active = 0 WHERE id = $pid") or die($conn->error);
                            if(!$changeProductStatus){
                                return ['status'=>'false','message'=>$conn->error];
                            }
                        }
                    }
                }
                $conn->query('COMMIT');
                
                if($updateResult){
                    foreach($products as $element){
                    ?>
                    <script>
                        itemId = "cartItem"+"<?=$element->id?>";
                        if(localStorage.getItem(itemId)){
                            localStorage.removeItem(itemId);
                        }
                    </script>
                    <?php
                    }
                    echo "<script>location.href='order-success.php'</script>";
                }
            }
        }
        catch(Exception $ex){
            $conn->query('ROLLBACK');
            return ['status'=>'false','messsage'=>$ex];
        }
    }
?>
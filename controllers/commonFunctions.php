<?php
    require_once(dirname(__DIR__).'/config.php');
    function validateData($data,$dataType,$min=null,$max=null){
        $response = [];
        if($dataType == 'string'){
            $regExp = "/^[a-zA-Z0-9\-\_\(\)\*\,\&\; ]+$/";
            $len = strlen($data);
            if($len < $min || $len > $max){
                return false;
            }else if(!preg_match($regExp,$data)){
                return false;
            }
        } elseif($dataType == 'number'){
            $regExp = "/^[1-9]\d*$/";
            if(!preg_match($regExp,$data)){
                return false;
            }
        } elseif($dataType == 'email'){
            $regExp = "/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/";
            if(!preg_match($regExp,$data)){
                return false;
            }
        }
        return true;
    }

    function trimData($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function getUserByEmailId($email){
        global $conn;
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        return $result;
    }

    function getUserByUserId($userId){
        global $conn;
        $sql = "SELECT * FROM users WHERE user_id = '$userId'";
        $result = $conn->query($sql);
        return $result;
    }

    function getSupplierByCompanyName($name){
        global $conn;
        $sql = "SELECT * FROM supplier WHERE LCASE(company_name) = LCASE('$name')";
        $result = $conn->query($sql);
        return $result;
    }

    function emailNoficication($to,$templateId,$name = null,$token = null){
        include_once dirname(__DIR__).'/phpMailer/PHPMailerAutoload.php';
        global $mailInfo,$conn;
        $mailBody = "";
        $mail = new PHPMailer;

        //Fetch template from database
        $sql = "SELECT mt.mail_body,md.header,md.footer,mt.subject FROM master_mail_template mt LEFT JOIN master_mail_design md on mt.mail_design = md.id WHERE mt.id = '$templateId'";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_array();
            $mailBody .= $row['header'].$row['mail_body'].$row['footer'];
            $mailBody = str_replace('$subject',$row['subject'],$mailBody);
            if($name != null){
                $mailBody = str_replace('$name',$name,$mailBody);
            }
            if($token != null){
                $mailBody = str_replace('$token',$token,$mailBody);
            }
        }

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $mailInfo['host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $mailInfo['username'];                 // SMTP username
        $mail->Password = $mailInfo['password'];                           // SMTP password
        $mail->SMTPSecure = $mailInfo['security'];                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $mailInfo['port'];                                    // TCP port to connect to

        $mail->setFrom($mailInfo['username'], 'Grocery Store');
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo($mailInfo['username'], 'Support');
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $row['subject'];
        $mail->Body    = $mailBody;

        if(!$mail->send()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    function createToken($stringArray){
        global $token;
        $tempData = implode(",",$stringArray);
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = $token['key'];
        
        $encryptedData = openssl_encrypt($tempData, $ciphering,$encryption_key, $options, $encryption_iv);
        return $encryptedData;
    }

    function decryptToken($encryptedToken){
        global $token;
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $decryptionKey = 'my_security_key';
    
        $decryptedToken=openssl_decrypt($encryptedToken,$ciphering,$decryptionKey,$options,$encryption_iv);
        $dataArray = explode(",",$decryptedToken);
        return $dataArray;
    }

    function getAllMeasureTypes(){
        global $conn;
        $sql = "SELECT * FROM master_measure_types";
        $result = $conn->query($sql);
        return $result;
    }

    function orderStats($userId){
        global $conn;
        $getUser = getUserByUserId($userId);
        if($getUser->num_rows < 1){
            return ['status'=>'false','message'=>'Something went wrong'];
        }
        $userDetails = $getUser->fetch_array();
        $supplierId = $userDetails['supplier_id'];

        $totalSql = "SELECT * FROM products WHERE supplier_id = '$supplierId'";
        $productResult = $conn->query($totalSql);
        $totalProducts = $productResult->num_rows;

        $activeSql = "SELECT * FROM products WHERE supplier_id = '$supplierId' AND is_active = '1'";
        $activeResult = $conn->query($activeSql);
        $activeProducts = $activeResult->num_rows;

        $inactiveSql = "SELECT * FROM products WHERE supplier_id = '$supplierId' AND is_active = '0'";
        $inactiveResult = $conn->query($inactiveSql);
        $inactiveProducts = $inactiveResult->num_rows;

        $totalOrderSql = "SELECT * FROM customer_orders co LEFT JOIN products p ON co.product_id = p.id WHERE p.supplier_id = '$supplierId'";
        $orderResult = $conn->query($totalOrderSql);
        $totalOrders = $orderResult->num_rows;

        $deliveredOrderSql = "SELECT * FROM customer_orders co LEFT JOIN products p ON co.product_id = p.id LEFT JOIN order_status os ON co.order_id = os.order_id WHERE p.supplier_id = '$supplierId' AND os.status = 'delivered'";
        $deliveredOrderResult = $conn->query($deliveredOrderSql);
        $deliveredOrders = $deliveredOrderResult->num_rows;

        $canceledOrderSql = "SELECT * FROM customer_orders co LEFT JOIN products p ON co.product_id = p.id LEFT JOIN order_status os ON co.order_id = os.order_id WHERE p.supplier_id = '$supplierId' AND os.status = 'canceled'";
        $canceledOrderResult = $conn->query($canceledOrderSql);
        $canceledOrders = $canceledOrderResult->num_rows;

        return ["totalProducts"=>$totalProducts,"activeProducts"=>$activeProducts,"inactiveProducts"=>$inactiveProducts,"totalOrders"=>$totalOrders,"deliveredOrders"=>$deliveredOrders,"canceledOrders"=>$canceledOrders];
    }

    function adminStats(){
        global $conn;
        
        $totalUserSql = "SELECT * FROM users WHERE user_type = 'customer'";
        $totalUserResult = $conn->query($totalUserSql);
        $totalUsers = $totalUserResult->num_rows;

        $totalSupplierSql = "SELECT * FROM users WHERE user_type = 'supplier'";
        $totalSupplierResult = $conn->query($totalSupplierSql);
        $totalSuppliers = $totalSupplierResult->num_rows;

        $totalProductsSql = "SELECT * FROM products WHERE is_active = '1'";
        $totalProductsResult = $conn->query($totalProductsSql);
        $totalProducts = $totalProductsResult->num_rows;

        $totalOrderSql = "SELECT * FROM customer_orders";
        $totalOrdersResult = $conn->query($totalOrderSql);
        $totalOrders = $totalOrdersResult->num_rows;

        $deliveredOrderSql = "SELECT * FROM customer_orders co LEFT JOIN order_status os ON co.order_id = os.order_id WHERE os.status = 'delivered'";
        $deliveredOrdersResult = $conn->query($deliveredOrderSql);
        $deliveredOrders = $deliveredOrdersResult->num_rows;

        $canceledOrderSql = "SELECT * FROM customer_orders co LEFT JOIN order_status os ON co.order_id = os.order_id WHERE os.status = 'canceled'";
        $canceledOrdersResult = $conn->query($canceledOrderSql);
        $canceledOrders = $canceledOrdersResult->num_rows;

        return ["totalUsers"=>$totalUsers,"totalSuppliers"=>$totalSuppliers,"totalProducts"=>$totalProducts,"totalOrders"=>$totalOrders,"deliveredOrders"=>$deliveredOrders,"canceledOrders"=>$canceledOrders];
    }

    function getPaymentModes($id = null){
            global $conn;
            $sql = "SELECT * FROM master_payment_modes";
            if($id != null){
                $sql .= " WHERE id = '$id'";
            }
            $result = $conn->query($sql);
            return $result;
    }
    
?>
<?php
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    include_once(dirname(__DIR__).'/config.php');

    function createUser($userType='customer'){
        if(isset($_POST['submit'])){
            global $conn;
            $name = trimData($_POST['name']);
            $email = trimData($_POST['email']);
            $phone = trimData($_POST['phone']);
            $password = trimData($_POST['password']);
            $confirmPassword = trimData($_POST['confirmPassword']);
            if(!validateData($name,'string',3,30)){
                $error = ["name"=>'name',"message" => "Please enter valid name",'value'=>$_POST];
                return $error;
            }
            if(!validateData($email,'email')){
                $error = ["name"=>'email',"message" => "Please enter valid email address",'value'=>$_POST];
                return $error;
            }
            if(!validateData($phone,'string',10,10)){
                $error = ["name"=>'phone',"message" => "Please enter valid phone number",'value'=>$_POST];
                return $error;
            }
            if(strlen($password) < 5){
                $error = ["name"=>'password',"message" => "Password should be minimum 5 characters",'value'=>$_POST];
                return $error;
            }
            if($password != $confirmPassword){
                $error = ["name"=>'confirmPassword',"message" => "Confirm password should be same as password",'value'=>$_POST];
                return $error;
            }

            $password = md5($password);
            $userId = md5(uniqid($email));

            //Check if email already exist
            $result = getUserByEmailId($email);
            if($result->num_rows > 0){
                $error = ["name"=>'email',"message" => "User with this email already registered.",'value'=>$_POST];
                return $error;
            }

            //Check if user id exist
            $result = getUserByEmailId($userId);
            if($result->num_rows > 0){
                $error = ["name"=>'userId',"message" => "Something went wrong, Please try again",'value'=>$_POST];
                return $error;
            }

            if($userType == 'supplier'){
                $companyName = trimData($_POST['companyName']);
                if(!validateData($companyName,'string',3,30)){
                    $error = ["name"=>'companyName',"message" => "Please enter valid company name",'value'=>$_POST];
                    return $error;
                }
                $supplierId = md5(uniqid(($companyName)));
                $result = getSupplierByCompanyName($companyName);
                if($result->num_rows > 0){
                    $error = ["name"=>'companyName',"message" => "Company is already registered.",'value'=>$_POST];
                    return $error;
                }
                $supplierSql = "INSERT INTO supplier(supplier_id,company_name) VALUES('$supplierId','$companyName')";
                if ($conn->query($supplierSql) === FALSE){
                    $error = ["name"=>"dbError","message"=>$conn->error];
                    return $error;
                  }   
            }
            
            if($userType == 'supplier'){
                $sql = "INSERT INTO users(user_id,supplier_id,name,email,phone,password,user_type,is_verified) VALUES('$userId','$supplierId','$name','$email','$phone','$password','$userType','0')";
            } else {
                $sql = "INSERT INTO users(user_id,name,email,phone,password,user_type,is_verified) VALUES('$userId','$name','$email','$phone','$password','$userType','0')";
            }

            if ($conn->query($sql) === TRUE) {
                $token = createToken([$userId,$name,$email]);
                emailNoficication($email,1,$name,$token);
                return ["status"=>'true'];
              } else {
                $error = ["name"=>"dbError","message"=>$conn->error];
              }
        }
        return true;
    }

    function updateUser($userId){
        if(isset($_POST['editProfile'])){
            global $conn;
            $name = trimData($_POST['name']);
            $email = trimData($_POST['email']);
            $phone = trimData($_POST['phone']);
            if(!validateData($name,'string',3,30)){
                $error = ["status" =>"false","name"=>'name',"message" => "Please enter valid name",'value'=>$_POST];
                return $error;
            }
            if(!validateData($email,'email')){
                $error = ["status" =>"false","name"=>'email',"message" => "Please enter valid email address",'value'=>$_POST];
                return $error;
            }
            if(!validateData($phone,'string',10,10)){
                $error = ["status" =>"false","name"=>'phone',"message" => "Please enter valid phone number",'value'=>$_POST];
                return $error;
            }

            //Check if email already exist
            $result = getUserByEmailId($email);
            if($result->num_rows > 0){
                $userDetail = $result->fetch_array();
                if($userDetail['user_id'] != $userId){
                    $error = ["status" =>"false","name"=>'email',"message" => "User with this email already registered.",'value'=>$_POST];
                    return $error;
                }
            }

            //Check if user id exist
            $checkUserId = getUserByUserId($userId);
            if($checkUserId->num_rows < 1){
                $error = ["status" =>"false","name"=>'userId',"message" => "Something went wrong, Please try again",'value'=>$_POST];
                return $error;
            }
            
            $sql = "UPDATE users SET name = '$name',email = '$email',phone = '$phone' WHERE user_id = '$userId'";

            if ($conn->query($sql) === TRUE) {
                return ["status"=>'true','message'=>"Profile updated successfully"];
              } else {
                $error = ["status"=>"false","name"=>"dbError","message"=>$conn->error];
              }
        }
        return true;
    }

    function userLogin($userType){
        if(isset($_POST['login'])){
            global $conn;
            $email = trimData($_POST['email']);
            $password = md5(trimData($_POST['password']));
            $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($sql);

            if($result->num_rows > 0){
                $row = $result->fetch_array();
                if($row['is_verified'] != 1){
                    return ['status'=>'false','message'=>'Your email is not verified, Please verify your email.'];
                }
                if($row['user_type'] != $userType){
                    return ['status'=>'false','message'=>'Invalid credentials'];
                }
                $_SESSION['email'] = $row['email'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['userType'] = $row['user_type'];
                $_SESSION['userId'] = $row['user_id'];
                $_SESSION['id'] = $row['id'];
                if($row['user_type'] == 'customer'){
                    echo "<script>location.href = './index.php'</script>";
                } else {
                    echo "<script>location.href = './dashboard.php'</script>";
                }
            }else{
                return ['status'=>'false','message'=>'Invalid credentials'];
            }
        }
    }

    function emailVerification($token){
        global $conn;
        $data = decryptToken($token);
        $checkUser = getUserByUserId($data['0']);
        if($checkUser->num_rows>0){
            $row = $checkUser->fetch_array();
            if($row['is_verified'] == 0){
                $sql = "UPDATE users SET is_verified = '1' WHERE user_id = '$data[0]'";
                if($conn->query($sql) == TRUE){
                    return ['status'=>'true','message'=>'Email verified successfully'];
                }else{
                    return ['status'=>'false','message'=>$conn->error];
                }
            }
            else{
                return ['status'=>'false','message'=>'This email is already verified'];
            }
        }else{
            return ['status'=>'false','message'=>'Unauthorized access denied'];
        }
    }

    function changePassword($userId){
        if(isset($_POST['changePassword'])){
            global $conn;
            $password = trimData($_POST['password']);
            $confirmPassword = trimData($_POST['confirmPassword']);
    
            if(strlen($password) < 5){
                $error = ["status" =>"false","name"=>'password',"message" => "Password should be minimum 5 characters"];
                return $error;
            }
            if($password != $confirmPassword){
                $error = ["status" =>"false","name"=>'confirmPassword',"message" => "Confirm password must be same as password"];
                return $error;
            }

            //Check if user id exist
            $checkUserId = getUserByUserId($userId);
            if($checkUserId->num_rows < 1){
                $error = ["status" =>"false","name"=>'userId',"message" => "Something went wrong, Please try again",'value'=>$_POST];
                return $error;
            }

            $password = md5($password);
            $sql = "UPDATE users SET password = '$password' WHERE user_id = '$userId'";

            if ($conn->query($sql) === TRUE) {
                return ["status"=>'true','message'=>"Password changed successfully"];
              } else {
                $error = ["status"=>"false","name"=>"dbError","message"=>$conn->error];
              }
        }
        return true;
    }

    
?>
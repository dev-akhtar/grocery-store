<?php
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    include_once(dirname(__DIR__).'/config.php');

    function getAllSuppliers($status){
        global $conn;
        $sql ="SELECT s.id,u.name,s.supplier_id,s.company_name,u.phone,s.status,DATE_FORMAT(s.created_at,'%d-%m-%Y') as createdAt,s.updated_at as updatedAt FROM supplier s LEFT JOIN users u ON s.supplier_id = u.supplier_id WHERE u.user_type = 'supplier' AND s.status = '$status'";
        $result = $conn->query($sql);
        return $result;
    }

    function editSupplier(){
        if(isset($_POST['editSupplier'])){
            global $conn;
            $supplierId = trimData($_POST['id']);
            $name = trimData($_POST['name']);
            $company = trimData($_POST['company']);
            $phone = trimData($_POST['phone']);

            if(!validateData($name,'string',3,30)){
                $error = ["status"=>'false',"message" => "Please enter valid name",'value'=>$_POST];
                return $error;
            }

            if(!validateData($company,'string',3,30)){
                $error = ["status"=>'false',"message" => "Please enter valid company name",'value'=>$_POST];
                return $error;
            }

            if(!validateData($phone,'phone',10,10)){
                $error = ["status"=>'false',"message" => "Please enter valid phone number",'value'=>$_POST];
                return $error;
            }

            $checkSupplierId = getSupplierById($supplierId);
            if($checkSupplierId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Supplier does not exist",'value'=>$_POST];
                return $error;
            }

            $checkCompanyName = getSupplierByCompanyName($company);
            if($checkCompanyName->num_rows > 0){
                $supplierDetail = $checkCompanyName->fetch_array();
                if($supplierDetail['supplier_id'] != $supplierId)
                {
                    $error = ["status"=>'false',"message" => "Company is already registered.",'value'=>$_POST];
                    return $error;
                }
            }

            $supplierSql = "UPDATE supplier SET company_name = '$company' WHERE supplier_id = '$supplierId'";
            $userSql = "UPDATE users SET name = '$name',phone = '$phone' WHERE supplier_id = '$supplierId' AND user_type = 'supplier'";

            if ($conn->query($supplierSql) === FALSE || $conn->query($userSql) === FALSE){
                $error = ["status"=>"false","name"=>'editCategory',"message"=>$conn->error,'value'=>$_POST];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Supplier updated successfully'];
            }
        }
    }

    function deleteSupplier(){
        if(isset($_GET['sid'])){
            global $conn;
            $sid = trimData($_GET['sid']);

            $checkId = getSupplierById($sid);
            if($checkId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Supplier does not exist"];
                return $error;
            }

            $deleteSql = "DELETE FROM supplier WHERE supplier_id = '$sid'";
            if ($conn->query($deleteSql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Supplier deleted successfully'];
            }
        }
    }

    function reviewSupplier(){
        if(isset($_GET['sid']) && isset($_GET['action'])){
            global $conn;
            $sid = trimData($_GET['sid']);
            $status = "";
            if($_GET['action'] == 'approve'){
                $status = "Active";
            }elseif($_GET['action'] == 'reject'){
                $status = "Rejected";
            }
            $checkId = getSupplierById($sid);
            if($checkId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Supplier does not exist"];
                return $error;
            }
            $supplierDetail = $checkId->fetch_array();
            if($supplierDetail['status'] != 'Pending'){
                $error = ["status"=>'false',"message" => "Supplier is already active or rejected"];
                return $error;
            }

            $changeSql = "UPDATE supplier SET status = '$status' WHERE supplier_id = '$sid'";
            if ($conn->query($changeSql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Supplier status updated successfully'];
            }
        }
    }

    function getSupplierById($supplierId){
        global $conn;
        $sql ="SELECT s.id,u.name,s.supplier_id,s.company_name,u.phone,s.status,s.created_at as createdAT,s.updated_at as updatedAt FROM supplier s LEFT JOIN users u ON s.supplier_id = u.supplier_id WHERE u.user_type = 'supplier' AND s.supplier_id = '$supplierId'";
        $result = $conn->query($sql);
        return $result;
    }
?>
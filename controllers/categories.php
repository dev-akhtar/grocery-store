<?php
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    include_once(dirname(__DIR__).'/config.php');
    
    function addCategory(){
        if(isset($_POST['submit'])){
            global $conn;
            $name = trimData($_POST['name']);

            if(!validateData($name,'string',3,30)){
                $error = ["status"=>"false","name"=>'name',"message" => "Please enter valid name",'value'=>$_POST];
                return $error;
            }
            $checkExistingCategory = getCategoryByIdName(null,$name);
            if($checkExistingCategory->num_rows > 0){
                $error = ["status"=>'false',"message" => "Category is already exist"];
                return $error;
            }
            $categorySql = "INSERT INTO product_categories(name) VALUES('$name')";
            if ($conn->query($categorySql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Category added successfully'];
            }
        }
    }

    function editCategory(){
        if(isset($_POST['editCategory'])){
            global $conn;
            $id = trimData($_POST['categoryId']);
            $name = trimData($_POST['categoryName']);

            if(!validateData($name,'string',3,30)){
                $error = ["status"=>'false',"message" => "Please enter valid name",'value'=>$_POST];
                return $error;
            }
            if(!validateData($id,'number')){
                $error = ["status"=>'false',"message" => "Invalid category id",'value'=>$_POST];
                return $error;
            }

            $checkId = getCategoryByIdName($id,null);
            if($checkId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist",'value'=>$_POST];
                return $error;
            }

            $checkExistingCategory = getCategoryByIdName(null,$name);
            $allRows = $checkExistingCategory->fetch_array();
            if($checkExistingCategory->num_rows > 0 && $allRows['id'] != $id){
                $error = ["status"=>'false',"message" => "Category is already exist",'value'=>$_POST];
                return $error;
            }
            $categorySql = "UPDATE product_categories SET name = '$name' WHERE id = '$id'";
            if ($conn->query($categorySql) === FALSE){
                $error = ["status"=>"false","name"=>'editCategory',"message"=>$conn->error,'value'=>$_POST];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Category updated successfully'];
            }
        }
    }

    function getCategoryByIdName($id = null,$name = null){
        global $conn;
        $sql = $id!=null?"SELECT * FROM product_categories WHERE id = '$id'":"SELECT * FROM product_categories WHERE LCASE(name) = LCASE('$name')";
        $result = $conn->query($sql);
        return $result;
    }

    function getAllCategories(){
        global $conn;
        $sql ="SELECT * FROM product_categories";
        $result = $conn->query($sql);
        return $result;
    }

    function deleteCategory(){
        if(isset($_GET['id'])){
            global $conn;
            $id = trimData($_GET['id']);

            if(!validateData($id,'number')){
                $error = ["status"=>'false',"message" => "Invalid category id"];
                return $error;
            }

            $checkId = getCategoryByIdName($id,null);
            if($checkId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist"];
                return $error;
            }

            $deleteSql = "DELETE FROM product_categories WHERE id = '$id'";
            if ($conn->query($deleteSql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                return ['status'=>'true','message'=>'Category deleted successfully'];
            }
        }
    }
    
?>
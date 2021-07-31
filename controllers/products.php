<?php
    include_once(dirname(__DIR__).'/controllers/connection.php');
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    include_once(dirname(__DIR__).'/controllers/categories.php');
    include_once(dirname(__DIR__).'/config.php');

    function addProduct(){
        if(isset($_POST['addProduct'])){
            global $conn;
            $title = trimData($_POST['title']);
            $category = trimData($_POST['category']);
            $quantity = trimData($_POST['quantity']);
            $price = trimData($_POST['price']);
            $scale = trimData($_POST['product_scale']);
            $description = trimData($_POST['description']);
            $image = $_FILES['image']['name'];
            $exp = explode('.',$image);
            $ext = strtolower(end($exp));
            $newImageName = $_SESSION['userId'].'-'.rand().'.'.$ext;
            $uploadPath = dirname(__DIR__).'/supplier/images/'.$newImageName;
            $tmpName = $_FILES['image']['tmp_name'];

            if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png'){
                $error = ["status"=>'false',"name"=>'image',"message" => "Invalid image type",'value'=>$_POST];
                return $error;
            }

            if(!validateData($title,'string',5,150)){
                $error = ["status"=>'false',"name"=>'title',"message" => "Please enter valid title",'value'=>$_POST];
                return $error;
            }

            if(!validateData($category,'number')){
                $error = ["status"=>'false',"name"=>'category',"message" => "Please enter valid category",'value'=>$_POST];
                return $error;
            }
            
            if(!validateData($quantity,'number')){
                $error = ["status"=>'false',"name"=>'quantity',"message" => "Please enter valid quantity",'value'=>$_POST];
                return $error;
            }

            if($price < 1){
                $error = ["status"=>'false',"name"=>'price',"message" => "Product price should be greater than 0",'value'=>$_POST];
                return $error;
            }

            $checkExistingCategory = getCategoryByIdName($category,null);
            if($checkExistingCategory->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist"];
                return $error;
            }
            
            $userData = getUserByUserId($_SESSION['userId']);
            if($userData->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist"];
                return $error;
            }else{
                $row = $userData->fetch_array();
                if($row['user_type'] != 'supplier' && $row['user_type'] != 'superAdmin'){
                    $error = ["status"=>'false',"message" => "Unauthorized access"];
                    return $error;
                }
            }
            $supplierId = $row['supplier_id'];
            $productSql = "INSERT INTO products(supplier_id,product_title,description,quantity,category,image,price,measure_type) VALUES('$supplierId','$title','$description','$quantity','$category','$newImageName','$price','$scale')";
            if ($conn->query($productSql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                if(move_uploaded_file($tmpName,$uploadPath)){
                    return ['status'=>'true','message'=>'Product added successfully'];
                }
                else{
                    $error = ["status"=>"false","message"=>'Something went wrong'];
                    return $error;
                }
            }
        }
    }

    function editProduct(){
        if(isset($_POST['editProduct'])){
            global $conn;
            $id = trimData($_POST['productId']);
            $title = trimData($_POST['title']);
            $category = trimData($_POST['category']);
            $quantity = trimData($_POST['quantity']);
            $price = trimData($_POST['price']);
            $scale = trimData($_POST['product_scale']);
            $description = trimData($_POST['description']);
            $isActive = trimData($_POST['status']);
            if(!empty($_FILES['image']['name'])){
                $image = $_FILES['image']['name'];
                $exp = explode('.',$image);
                $ext = strtolower(end($exp));
                $newImageName = $_SESSION['userId'].'-'.rand().'.'.$ext;
                $uploadPath = dirname(__DIR__).'/supplier/images/'.$newImageName;
                $tmpName = $_FILES['image']['tmp_name'];
    
                if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png'){
                    $error = ["status"=>'false',"name"=>'image',"message" => "Invalid image type",'value'=>$_POST];
                    return $error;
                }
            }

            if(!validateData($title,'string',5,150)){
                $error = ["status"=>'false',"name"=>'title',"message" => "Please enter valid title",'value'=>$_POST];
                return $error;
            }

            if(!validateData($category,'number')){
                $error = ["status"=>'false',"name"=>'category',"message" => "Please enter valid category",'value'=>$_POST];
                return $error;
            }
            
            if(!validateData($quantity,'number')){
                $error = ["status"=>'false',"name"=>'quantity',"message" => "Please enter valid quantity",'value'=>$_POST];
                return $error;
            }

            if($price < 1){
                $error = ["status"=>'false',"name"=>'price',"message" => "Product price should be greater than 0",'value'=>$_POST];
                return $error;
            }

            $checkProductId = getProductById($id);
            if($checkProductId->num_rows < 1){
                $error = ["status"=>'false',"message" => "Product does not exist"];
                return $error;
            }

            $checkExistingCategory = getCategoryByIdName($category,null);
            if($checkExistingCategory->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist"];
                return $error;
            }
            
            $userData = getUserByUserId($_SESSION['userId']);
            if($userData->num_rows < 1){
                $error = ["status"=>'false',"message" => "Category does not exist"];
                return $error;
            }else{
                $row = $userData->fetch_array();
                if($row['user_type'] != 'supplier' && $row['user_type'] != 'superAdmin'){
                    $error = ["status"=>'false',"message" => "Unauthorized access"];
                    return $error;
                }
            }

            $supplierId = $row['supplier_id'];

            if(!empty($_FILES['image']['name'])){
            $productDetail = $checkProductId->fetch_array();
            unlink(dirname(__DIR__).'/supplier/images/'.$productDetail['image']);
            $productSql = "UPDATE products SET product_title = '$title',description = '$description',quantity = '$quantity',category = '$category',image = '$newImageName',price = '$price',measure_type = '$scale' WHERE id = '$id' AND supplier_id = '$supplierId'";
            }else{
                $productSql = "UPDATE products SET product_title = '$title',description = '$description',quantity = '$quantity',category = '$category',price = '$price',measure_type = '$scale',is_active = '$isActive' WHERE id = '$id' AND supplier_id = '$supplierId'";
            }

            if ($conn->query($productSql) === FALSE){
                $error = ["status"=>"false","message"=>$conn->error];
                return $error;
            }else{
                if(!empty($_FILES['image']['name'])){
                    if(move_uploaded_file($tmpName,$uploadPath)){
                        return ['status'=>'true','message'=>'Product added successfully'];
                    }
                    else{
                        $error = ["status"=>"false","message"=>'Something went wrong'];
                        return $error;
                    }
                }
                return ['status'=>'true','message'=>'Product updated successfully'];
            }
        }
    }

    function getAllProducts($userId = null,$categoryId = null,$limit = null){
        global $conn;
        $sql ="SELECT p.id,p.product_title as title,p.supplier_id as supplierId,p.category as category,pc.name as categoryName,p.quantity,p.description,p.image,p.price,p.measure_type as measureType,p.is_active as isActive,p.created_at as createdAt,p.updated_at as updatedAt,mmt.name as measureTypeName FROM products p LEFT JOIN product_categories pc ON p.category = pc.id LEFT JOIN master_measure_types mmt ON p.measure_type = mmt.id";
        if($userId != null){
            $userData = getUserByUserId($userId);
            if($userData->num_rows < 1){
                return ['status'=>'false','message'=>'Authorization denied'];
            }
            $row = $userData->fetch_array();
            $supplierId = $row['supplier_id'];
            $sql .= " WHERE p.supplier_id = '$supplierId'";
        }

        if($categoryId != null){
            $checkCategory = getCategoryByIdName($categoryId,null);
            if($checkCategory->num_rows >= 1){
                $sql .= " WHERE p.category = '$categoryId' AND p.is_active = 1 AND p.quantity >=1";
            }
        }

        if($categoryId == null && $userId == null){
            $sql .= " WHERE p.is_active = 1 AND p.quantity >= 1";
        }

        if($limit != null){
            $sql .= " LIMIT $limit";
        }

        $result = $conn->query($sql);
        return $result;
    }

    function getProductById($productId){
        global $conn;
        $sql ="SELECT p.id,p.product_title as title,p.supplier_id as supplierId,p.category as category,pc.name as categoryName,p.quantity,p.description,p.image,p.price,p.measure_type as measureType,mmt.name as measureTypeName,p.is_active as isActive FROM products p LEFT JOIN product_categories pc ON p.category = pc.id LEFT JOIN master_measure_types mmt ON p.measure_type = mmt.id WHERE p.id = '$productId'";
        $result = $conn->query($sql);
        return $result;
    }

    function deleteProduct($pid){
        global $conn;

        $checkProduct = getProductById($pid);
        if($checkProduct->num_rows < 1){
            return ['status'=>'false','message'=>'Product does not exist'];
        }

        $productDetail = $checkProduct->fetch_array();
        unlink(dirname(__DIR__).'/supplier/images/'.$productDetail['image']);

        $deleteSql = "DELETE FROM products WHERE id = '$pid'";
        if($conn->query($deleteSql) === 'FALSE'){
            return ['status'=>'false','message'=>$conn->error];
        }else{
            return ['status'=>'true','message'=>'Product deleted successfully'];
        }
    }
?>
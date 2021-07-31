<?php
    include_once(dirname(__DIR__).'/controllers/commonFunctions.php');
    $str = ['makgkp','test@123','mak@gmail.com'];
    $token = createToken($str);
    echo $token;
    $original = decryptToken($token);
?>
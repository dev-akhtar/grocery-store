<?php
    session_start();
    if(isset($_SESSION['userId'])){
        session_destroy();
    }
    echo "<script>location.href = './'</script>";
?>
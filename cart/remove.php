<?php

session_start();

/* ==========================================================
CHECK PRODUCT
========================================================== */

if(!isset($_GET['id'])){

    header("Location: ../cart.php");

    exit();

}

$product_id=(int)$_GET['id'];

/* ==========================================================
REMOVE PRODUCT
========================================================== */

if(isset($_SESSION['cart'][$product_id])){

    unset($_SESSION['cart'][$product_id]);

    $_SESSION['success']="Product removed from your cart.";

}else{

    $_SESSION['error']="Product not found.";

}

header("Location: ../cart.php");

exit();

?>
<?php

session_start();

include("../config/config.php");

/* ==========================================================
CHECK REQUEST
========================================================== */

if($_SERVER['REQUEST_METHOD'] != "POST"){

    header("Location: ../cart.php");

    exit();

}

/* ==========================================================
CHECK PRODUCT
========================================================== */

if(
!isset($_POST['product_id'])
||
!isset($_POST['quantity'])
){

    $_SESSION['error']="Invalid request.";

    header("Location: ../cart.php");

    exit();

}

$product_id=(int)$_POST['product_id'];

$quantity=(int)$_POST['quantity'];

if($quantity<1){

    $quantity=1;

}

/* ==========================================================
UPDATE CART
========================================================== */

if(isset($_SESSION['cart'][$product_id])){

    $stock=$_SESSION['cart'][$product_id]['stock'];

    if($quantity>$stock){

        $quantity=$stock;

    }

    $_SESSION['cart'][$product_id]['quantity']=$quantity;

    $_SESSION['success']="Cart updated successfully.";

}else{

    $_SESSION['error']="Product not found in cart.";

}

header("Location: ../cart.php");

exit();

?>
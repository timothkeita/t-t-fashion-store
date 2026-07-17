<?php

session_start();

include("../config/config.php");
include("../config/db.php");

/* ==========================================================
CHECK LOGIN
========================================================== */

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");
    exit();

}

/* ==========================================================
CHECK REQUEST
========================================================== */

if($_SERVER['REQUEST_METHOD'] != "POST"){

    header("Location: ../checkout.php");
    exit();

}

/* ==========================================================
CHECK CART
========================================================== */

if(
    !isset($_SESSION['cart'])
    ||
    count($_SESSION['cart']) == 0
){

    header("Location: ../cart.php");
    exit();

}

/* ==========================================================
CUSTOMER
========================================================== */

$customer_id = $_SESSION['customer_id'];

$phone = mysqli_real_escape_string($conn,$_POST['phone']);

$country = mysqli_real_escape_string($conn,$_POST['country']);

$city = mysqli_real_escape_string($conn,$_POST['city']);

$district = mysqli_real_escape_string($conn,$_POST['district']);

$province = mysqli_real_escape_string($conn,$_POST['province']);

$postal_code = mysqli_real_escape_string($conn,$_POST['postal_code']);

$address = mysqli_real_escape_string($conn,$_POST['address']);

$payment_method = mysqli_real_escape_string($conn,$_POST['payment_method']);

$payment_phone = mysqli_real_escape_string($conn,$_POST['payment_phone']);

$grand_total = (double)$_POST['grand_total'];

/* ==========================================================
GENERATE TRANSACTION ID
========================================================== */

$transaction_id = "TTFS-".date("Ymd")."-".strtoupper(substr(md5(uniqid()),0,8));

$status = "Pending";

/* ==========================================================
INSERT ORDER
========================================================== */

$orderQuery = mysqli_query($conn, "

INSERT INTO orders(

customer_id,

transaction_id,

total,

payment_method,

payment_phone,

payment_status,

order_status

)

VALUES(

'$customer_id',

'$transaction_id',

'$grand_total',

'$payment_method',

'$payment_phone',

'Pending',

'Pending'

)

");

if(!$orderQuery){

    die("Order Error : ".mysqli_error($conn));

}

/* ==========================================================
GET ORDER ID
========================================================== */

$order_id = mysqli_insert_id($conn);

/* ==========================================================
SAVE ORDER ITEMS
========================================================== */

foreach($_SESSION['cart'] as $item){

    $price = $item['price'];

    if($item['discount'] > 0){

        $price = $item['price'] - $item['discount'];

    }

    $subtotal = $price * $item['quantity'];

    mysqli_query($conn, "

    INSERT INTO order_items(

    order_id,

    product_id,

    price,

    quantity,

    subtotal

    )

    VALUES(

    '$order_id',

    '".$item['id']."',

    '$price',

    '".$item['quantity']."',

    '$subtotal'

    )

    ");

}

/* ==========================================================
UPDATE STOCK
========================================================== */

foreach($_SESSION['cart'] as $item){

    mysqli_query($conn, "

    UPDATE products

    SET stock = stock - ".$item['quantity']."

    WHERE id='".$item['id']."'

    ");

}

/* ==========================================================
CLEAR CART
========================================================== */

unset($_SESSION['cart']);

/* ==========================================================
SUCCESS
========================================================== */

$_SESSION['transaction_id'] = $transaction_id;

$_SESSION['order_id'] = $order_id;

header("Location: ../order_success.php");

exit();

?>
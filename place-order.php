<?php

session_start();

include("config/config.php");
include("config/db.php");

/* ==========================================
CHECK LOGIN
========================================== */

if(!isset($_SESSION['customer_id'])){

    header("Location:login.php");
    exit();

}

/* ==========================================
CHECK CART
========================================== */

if(
!isset($_SESSION['cart'])
||
count($_SESSION['cart'])==0
){

header("Location:cart/index.php");
exit();

}

$customer_id=$_SESSION['customer_id'];

$payment_method=mysqli_real_escape_string(
$conn,
$_POST['payment_method']
);

$phone=mysqli_real_escape_string(
$conn,
$_POST['phone']
);

$address=mysqli_real_escape_string(
$conn,
$_POST['address']
);

$city=mysqli_real_escape_string(
$conn,
$_POST['city']
);

$grand_total=(float)$_POST['grand_total'];

/* ==========================================
GENERATE TRANSACTION ID
========================================== */

$date=date("Ymd");

$result=mysqli_query($conn,"
SELECT id
FROM orders
ORDER BY id DESC
LIMIT 1
");

if(mysqli_num_rows($result)>0){

$row=mysqli_fetch_assoc($result);

$next=$row['id']+1;

}else{

$next=1;

}

$transaction_id="TT".$date.str_pad(
$next,
4,
"0",
STR_PAD_LEFT
);

/* ==========================================
START DATABASE TRANSACTION
========================================== */

mysqli_begin_transaction($conn);

try{
/* ==========================================
INSERT ORDER
========================================== */

$order=mysqli_query($conn,"
INSERT INTO orders(

customer_id,

transaction_id,

total,

payment_method,

payment_status,

order_status

)

VALUES(

'$customer_id',

'$transaction_id',

'$grand_total',

'$payment_method',

'Pending',

'Pending'

)
");

if(!$order){

throw new Exception(mysqli_error($conn));

}

$order_id=mysqli_insert_id($conn);

/* ==========================================
SAVE PRODUCTS
========================================== */

foreach($_SESSION['cart'] as $item){

$product_id=$item['id'];

$price=$item['price'];

if($item['discount']>0){

$price=$item['price']-$item['discount'];

}

$qty=$item['quantity'];

$subtotal=$price*$qty;

$itemQuery=mysqli_query($conn,"
INSERT INTO order_items(

order_id,

product_id,

price,

quantity,

subtotal

)

VALUES(

'$order_id',

'$product_id',

'$price',

'$qty',

'$subtotal'

)
");

if(!$itemQuery){

throw new Exception(mysqli_error($conn));

}

/* ==========================================
UPDATE STOCK
========================================== */

$stock=mysqli_query($conn,"
UPDATE products
SET stock=stock-$qty
WHERE id='$product_id'
");

if(!$stock){

throw new Exception(mysqli_error($conn));

}

}
/* ==========================================
COMMIT
========================================== */

mysqli_commit($conn);

/* ==========================================
CLEAR CART
========================================== */

unset($_SESSION['cart']);

/* ==========================================
SUCCESS
========================================== */

$_SESSION['transaction_id']=$transaction_id;

$_SESSION['order_id']=$order_id;

header("Location:order-success.php");

exit();

}catch(Exception $e){

mysqli_rollback($conn);

die($e->getMessage());

}

?>
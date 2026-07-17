<?php

session_start();

$pageTitle = "Order Successful";

include("config/db.php");
include("includes/header.php");
include("includes/navbar.php");

/* ==========================================
CHECK ORDER
========================================== */

if(
!isset($_SESSION['order_id'])
||
!isset($_SESSION['transaction_id'])
){

    header("Location:index.php");
    exit();

}

$order_id=$_SESSION['order_id'];
$transaction_id=$_SESSION['transaction_id'];

/* ==========================================
GET ORDER DETAILS
========================================== */

$orderQuery=mysqli_query($conn,"
SELECT *
FROM orders
WHERE id='$order_id'
LIMIT 1
");

$order=mysqli_fetch_assoc($orderQuery);

?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-9">

<div class="card shadow-lg border-0">

<div class="card-body p-5 text-center">

<div class="mb-4">

<i class="bi bi-check-circle-fill text-success"
style="font-size:90px;"></i>

</div>

<h1 class="fw-bold text-success">

Order Placed Successfully!

</h1>

<p class="lead text-muted">

Thank you for shopping with <strong>T & T Fashion Store</strong>.

Your order has been received successfully.

</p>

<hr>

<div class="row g-4 mt-3">

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Order Number

</h6>

<h3>

#<?php echo $order['id']; ?>

</h3>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Transaction ID

</h6>

<h5 class="text-primary">

<?php echo htmlspecialchars($order['transaction_id']); ?>

</h5>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Payment Method

</h6>

<h5>

<?php echo htmlspecialchars($order['payment_method']); ?>

</h5>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Mobile Money Number

</h6>

<h5>

<?php
echo !empty($order['payment_phone'])
? htmlspecialchars($order['payment_phone'])
: "N/A";
?>

</h5>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Order Status

</h6>

<span class="badge bg-warning fs-6">

<?php echo htmlspecialchars($order['order_status']); ?>

</span>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Payment Status

</h6>

<span class="badge bg-secondary fs-6">

<?php echo htmlspecialchars($order['payment_status']); ?>

</span>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Grand Total

</h6>

<h4 class="text-danger">

RWF <?php echo number_format($order['total']); ?>

</h4>

</div>

</div>

<div class="col-md-6">

<div class="border rounded p-3 h-100">

<h6 class="text-muted">

Estimated Delivery

</h6>

<h5 class="text-success">

2 - 4 Business Days

</h5>

</div>

</div>

</div>

<div class="alert alert-info mt-5">

<i class="bi bi-info-circle-fill"></i>

We will contact you shortly to confirm your order before delivery.

</div>

<div class="row mt-4">

<div class="col-md-6 mb-3">

<a
href="account/orders.php"
class="btn btn-dark btn-lg w-100">

<i class="bi bi-bag-check-fill"></i>

View My Orders

</a>

</div>

<div class="col-md-6 mb-3">

<a
href="shop.php"
class="btn btn-warning btn-lg w-100">

<i class="bi bi-bag-fill"></i>

Continue Shopping

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<?php

unset($_SESSION['order_id']);
unset($_SESSION['transaction_id']);

include("includes/footer.php");
include("includes/scripts.php");

?>
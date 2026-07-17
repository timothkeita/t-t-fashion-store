<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================================
CHECK ORDER ID
========================================== */

if(!isset($_GET['id'])){

    header("Location:index.php");

    exit();

}

$id=(int)$_GET['id'];

/* ==========================================
GET ORDER
========================================== */

$orderQuery=mysqli_query($conn,"
SELECT

o.*,

c.fullname,

c.email,

c.phone,

c.address

FROM orders o

LEFT JOIN customers c

ON c.id=o.customer_id

WHERE o.id='$id'

LIMIT 1
");

if(mysqli_num_rows($orderQuery)==0){

    header("Location:index.php");

    exit();

}

$order=mysqli_fetch_assoc($orderQuery);

/* ==========================================
GET ORDER ITEMS
========================================== */

$orderItems=mysqli_query($conn,"
SELECT

oi.*,

p.product_name

FROM order_items oi

LEFT JOIN products p

ON p.id=oi.product_id

WHERE oi.order_id='$id'
");

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Invoice

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{

background:#f4f6f9;

font-family:Arial,sans-serif;

}

.invoice{

background:#fff;

padding:40px;

max-width:900px;

margin:40px auto;

box-shadow:0 0 20px rgba(0,0,0,.1);

border-radius:10px;

}

.invoice-header{

display:flex;

justify-content:space-between;

align-items:center;

border-bottom:2px solid #eee;

padding-bottom:20px;

margin-bottom:30px;

}

.invoice-header img{

width:80px;

}

.table th{

background:#212529;

color:#fff;

}

.total-box{

font-size:22px;

font-weight:bold;

color:#dc3545;

}

@media print{

.no-print{

display:none;

}

body{

background:#fff;

}

.invoice{

box-shadow:none;

margin:0;

}

}

</style>

</head>

<body>

<div class="invoice">

<div class="invoice-header">

<div>

<img src="../../assets/images/logo.png">

<h2 class="mt-3">

T & T Fashion Store

</h2>

<p>

Kigali, Rwanda

</p>

</div>

<div class="text-end">

<h3>

SALES INVOICE

</h3>

<p>

Invoice #: INV-<?php echo str_pad($order['id'],5,"0",STR_PAD_LEFT); ?>

</p>

<p>

Date:

<?php

echo date(

"d F Y",

strtotime($order['created_at'])

);

?>

</p>

</div>

</div>

<div class="row mb-4">

<div class="col-md-6">

<h5>

Customer Information

</h5>

<p>

<strong>Name:</strong>

<?php echo htmlspecialchars($order['fullname']); ?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($order['email']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($order['phone']); ?>

</p>

<p>

<strong>Address:</strong>

<?php echo htmlspecialchars($order['address']); ?>

</p>

</div>

<div class="col-md-6 text-end">

<p>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($order['payment_method']); ?>

</p>

<p>

<strong>Payment Status:</strong>

<?php echo htmlspecialchars($order['payment_status']); ?>

</p>

<p>

<strong>Order Status:</strong>

<?php echo htmlspecialchars($order['order_status']); ?>

</p>

</div>

</div>

<table class="table table-bordered">

<thead>

<tr>

<th>Product</th>

<th width="120">Price</th>

<th width="120">Qty</th>

<th width="150">Subtotal</th>

</tr>

</thead>

<tbody>
<?php

$grandTotal = 0;

if(mysqli_num_rows($orderItems)>0){

while($item=mysqli_fetch_assoc($orderItems)){

$grandTotal += $item['subtotal'];

?>

<tr>

<td>

<?php echo htmlspecialchars($item['product_name']); ?>

</td>

<td>

RWF <?php echo number_format($item['price']); ?>

</td>

<td>

<?php echo $item['quantity']; ?>

</td>

<td>

RWF <?php echo number_format($item['subtotal']); ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="4" class="text-center">

No products found.

</td>

</tr>

<?php

}

?>

</tbody>

<tfoot>

<tr>

<th colspan="3" class="text-end">

Grand Total

</th>

<th class="total-box">

RWF <?php echo number_format($grandTotal); ?>

</th>

</tr>

</tfoot>

</table>

<hr>

<div class="row mt-4">

<div class="col-md-6">

<h5>

Thank You!

</h5>

<p>

Thank you for shopping with

<strong>

T & T Fashion Store

</strong>.

We appreciate your business and hope to serve you again.

</p>

</div>

<div class="col-md-6 text-end">

<p>

<strong>

Generated:

</strong>

<?php echo date("d F Y h:i A"); ?>

</p>

<p>

<strong>

Prepared By:

</strong>

Administrator

</p>

</div>

</div>

<hr>

<div class="text-center no-print">

<button

onclick="window.print();"

class="btn btn-primary">

<i class="bi bi-printer-fill"></i>

Print Invoice

</button>

<a

href="details.php?id=<?php echo $order['id']; ?>"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
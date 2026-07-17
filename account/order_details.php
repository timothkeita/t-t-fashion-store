<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");

    exit();

}

if(!isset($_GET['id'])){

    header("Location: orders.php");

    exit();

}

$pageTitle="Order Details";

include("../includes/header.php");
include("../includes/navbar.php");

$customer_id=$_SESSION['customer_id'];

$order_id=(int)$_GET['id'];

/* ==========================================
GET ORDER
========================================== */

$orderQuery=mysqli_query($conn,"

SELECT *

FROM orders

WHERE id='$order_id'

AND customer_id='$customer_id'

LIMIT 1

");

if(mysqli_num_rows($orderQuery)==0){

    header("Location: orders.php");

    exit();

}

$order=mysqli_fetch_assoc($orderQuery);

/* ==========================================
GET ORDER ITEMS
========================================== */

$itemQuery=mysqli_query($conn,"

SELECT

oi.*,

p.product_name,

p.image,

p.brand

FROM order_items oi

LEFT JOIN products p

ON p.id=oi.product_id

WHERE oi.order_id='$order_id'

");

?>
<div class="container py-5">

<div class="row">

<div class="col-lg-3">

<div class="card shadow-sm">

<div class="card-body">

<h4>

My Account

</h4>

<div class="list-group">

<a href="dashboard.php" class="list-group-item list-group-item-action">

Dashboard

</a>

<a href="orders.php" class="list-group-item list-group-item-action active">

My Orders

</a>

<a href="profile.php" class="list-group-item list-group-item-action">

Profile

</a>

<a href="support.php" class="list-group-item list-group-item-action">

Support

</a>

<a href="../logout.php" class="list-group-item list-group-item-action text-danger">

Logout

</a>

</div>

</div>

</div>

</div>

<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Order Details

</h4>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6">

<p>

<strong>

Order Number

</strong>

<br>

#<?php echo $order['id']; ?>

</p>

<p>

<strong>

Transaction ID

</strong>

<br>

<?php echo $order['transaction_id']; ?>

</p>

</div>

<div class="col-md-6">

<p>

<strong>

Payment Method

</strong>

<br>

<?php echo $order['payment_method']; ?>

</p>

<p>

<strong>

Payment Status

</strong>

<br>

<?php echo $order['payment_status']; ?>

</p>

</div>

</div>

<hr>
<div class="table-responsive">

<table class="table align-middle">

<thead class="table-light">

<tr>

<th>Product</th>

<th>Price</th>

<th>Qty</th>

<th>Total</th>

</tr>

</thead>

<tbody>

<?php

while($item=mysqli_fetch_assoc($itemQuery)){

?>

<tr>

<td>

<div class="d-flex align-items-center">

<img

src="../assets/images/products/<?php echo $item['image']; ?>"

style="width:70px;height:70px;object-fit:cover;border-radius:10px;"

class="me-3">

<div>

<strong>

<?php echo htmlspecialchars($item['product_name']); ?>

</strong>

<br>

<small class="text-muted">

<?php echo htmlspecialchars($item['brand']); ?>

</small>

</div>

</div>

</td>

<td>

RWF

<?php echo number_format($item['price']); ?>

</td>

<td>

<?php echo $item['quantity']; ?>

</td>

<td>

<strong>

RWF

<?php echo number_format($item['subtotal']); ?>

</strong>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<hr>
<table class="table">

<tr>

<th>

Order Status

</th>

<td>

<?php

$status=$order['order_status'];

if($status=="Pending"){

echo '<span class="badge bg-warning">Pending</span>';

}elseif($status=="Processing"){

echo '<span class="badge bg-primary">Processing</span>';

}elseif($status=="Delivered"){

echo '<span class="badge bg-success">Delivered</span>';

}else{

echo '<span class="badge bg-danger">'.$status.'</span>';

}

?>

</td>

</tr>

<tr>

<th>

Grand Total

</th>

<td>

<h3 class="text-danger">

RWF

<?php echo number_format($order['total']); ?>

</h3>

</td>

</tr>

</table>

<div class="d-flex gap-3 mt-4">

<a

href="orders.php"

class="btn btn-dark">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button

onclick="window.print()"

class="btn btn-primary">

<i class="bi bi-printer-fill"></i>

Print Invoice

</button>

<a

href="../shop.php"

class="btn btn-warning">

Continue Shopping

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================
CHECK ORDER
========================== */

if(!isset($_GET['id'])){

    header("Location:index.php");
    exit();

}

$order_id=(int)$_GET['id'];

/* ==========================
GET ORDER
========================== */

$orderQuery=mysqli_query($conn,"
SELECT

o.*,

c.fullname,

c.email,

c.phone,

c.address,

c.city,

c.country

FROM orders o

LEFT JOIN customers c

ON c.id=o.customer_id

WHERE o.id='$order_id'

LIMIT 1
");

if(mysqli_num_rows($orderQuery)==0){

die("Order not found.");

}

$order=mysqli_fetch_assoc($orderQuery);

/* ==========================
GET PRODUCTS
========================== */

$orderItems=mysqli_query($conn,"
SELECT

oi.*,

p.product_name,

p.image

FROM order_items oi

LEFT JOIN products p

ON p.id=oi.product_id

WHERE oi.order_id='$order_id'
");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Order Details

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>

<?php include("../../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../../includes/topbar.php"); ?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>

<i class="bi bi-receipt-cutoff text-primary"></i>

Order Details

</h2>

<a

href="index.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>
<div class="row">

<div class="col-lg-6">

<div class="card shadow-sm mb-4">

<div class="card-header bg-primary text-white">

Customer Information

</div>

<div class="card-body">

<p><strong>Name:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>

<p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>

<p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>

<p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>

<p><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>

<p><strong>Country:</strong> <?php echo htmlspecialchars($order['country']); ?></p>

</div>

</div>

</div>

<div class="col-lg-6">

<div class="card shadow-sm mb-4">

<div class="card-header bg-success text-white">

Order Information

</div>

<div class="card-body">

<p>

<strong>Transaction ID:</strong>

<span class="badge bg-dark">

<?php echo htmlspecialchars($order['transaction_id']); ?>

</span>

</p>

<p>

<strong>Payment Method:</strong>

<?php echo htmlspecialchars($order['payment_method']); ?>

</p>

<p>

<strong>Payment Status:</strong>

<span class="badge bg-warning text-dark">

<?php echo htmlspecialchars($order['payment_status']); ?>

</span>

</p>

<p>

<strong>Order Status:</strong>

<span class="badge bg-info">

<?php echo htmlspecialchars($order['order_status']); ?>

</span>

</p>

<p>

<strong>Order Date:</strong>

<?php echo date("d M Y h:i A",strtotime($order['created_at'])); ?>

</p>

</div>

</div>

</div>

</div>
<div class="card shadow-sm">

<div class="card-header bg-dark text-white">

Products Ordered

</div>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Image</th>

<th>Product</th>

<th>Price</th>

<th>Qty</th>

<th>Subtotal</th>

</tr>

</thead>

<tbody>

<?php

$total=0;

while($item=mysqli_fetch_assoc($orderItems)){

$total += $item['subtotal'];

?>

<tr>

<td>

<img

src="../../assets/images/products/<?php echo $item['image']; ?>"

style="width:70px;height:70px;object-fit:cover;border-radius:8px;">

</td>

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

<strong>

RWF <?php echo number_format($item['subtotal']); ?>

</strong>

</td>

</tr>

<?php

}

?>

</tbody>

<tfoot>

<tr>

<th colspan="4" class="text-end">

Grand Total

</th>

<th class="text-danger fs-5">

RWF <?php echo number_format($total); ?>

</th>

</tr>

</tfoot>

</table>

</div>

</div>

<div class="mt-4 d-flex gap-2">

<a

href="update-status.php?id=<?php echo $order['id']; ?>"

class="btn btn-success">

<i class="bi bi-pencil-square"></i>

Update Status

</a>

<a

href="invoice.php?id=<?php echo $order['id']; ?>"

class="btn btn-dark">

<i class="bi bi-printer-fill"></i>

Print Invoice

</a>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
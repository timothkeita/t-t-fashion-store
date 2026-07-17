<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

/* ==========================================
CHECK ADMIN LOGIN
========================================== */

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

/* ==========================================
CHECK CUSTOMER ID
========================================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$customer_id = (int) $_GET['id'];

/* ==========================================
GET CUSTOMER
========================================== */

$customerQuery = mysqli_query($conn, "
SELECT *
FROM customers
WHERE id = '$customer_id'
LIMIT 1
");

if (mysqli_num_rows($customerQuery) == 0) {
    header("Location: index.php");
    exit();
}

$customer = mysqli_fetch_assoc($customerQuery);

/* ==========================================
STATISTICS
========================================== */

$totalOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE customer_id='$customer_id'
"))['total'];

$totalSpent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE customer_id='$customer_id'
"))['total'];

$pendingOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE customer_id='$customer_id'
AND order_status='Pending'
"))['total'];

$completedOrders = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE customer_id='$customer_id'
AND order_status='Delivered'
"))['total'];

$pageTitle = "Customer Details";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

?>
<div class="container-fluid">

<div class="row">

<div class="col-lg-4">

<div class="content-card text-center">

<?php

$photo="../../assets/images/default-user.png";

if(!empty($customer['photo'])){

    $photo="../../assets/images/customers/".$customer['photo'];

}

?>

<img

src="<?php echo $photo; ?>"

style="width:170px;
height:170px;
border-radius:50%;
object-fit:cover;">

<h3 class="mt-4">

<?php echo htmlspecialchars($customer['fullname']); ?>

</h3>

<p class="text-muted">

<?php echo htmlspecialchars($customer['email']); ?>

</p>

<hr>

<p>

<strong>Phone</strong><br>

<?php echo htmlspecialchars($customer['phone']); ?>

</p>

<p>

<strong>Country</strong><br>

<?php echo htmlspecialchars($customer['country']); ?>

</p>

<p>

<strong>City</strong><br>

<?php echo htmlspecialchars($customer['city']); ?>

</p>

<p>

<strong>Address</strong><br>

<?php echo htmlspecialchars($customer['address']); ?>

</p>

<p>

<strong>Status</strong><br>

<?php

if($customer['status']=="Active"){

echo "<span class='badge bg-success'>Active</span>";

}else{

echo "<span class='badge bg-danger'>Inactive</span>";

}

?>

</p>

</div>

</div>
<div class="col-lg-8">

<div class="row">

<div class="col-md-6 mb-4">

<div class="dashboard-card bg-primary">

<h6>Total Orders</h6>

<h2>

<?php echo $totalOrders; ?>

</h2>

</div>

</div>

<div class="col-md-6 mb-4">

<div class="dashboard-card bg-success">

<h6>Total Spent</h6>

<h2>

RWF <?php echo number_format($totalSpent); ?>

</h2>

</div>

</div>

<div class="col-md-6 mb-4">

<div class="dashboard-card bg-warning">

<h6>Pending Orders</h6>

<h2>

<?php echo $pendingOrders; ?>

</h2>

</div>

</div>

<div class="col-md-6 mb-4">

<div class="dashboard-card bg-info">

<h6>Completed Orders</h6>

<h2>

<?php echo $completedOrders; ?>

</h2>

</div>

</div>

</div>
<div class="content-card mt-4">

<h4 class="mb-4">

<i class="bi bi-bag-check-fill"></i>

Order History

</h4>

<?php

$orderQuery=mysqli_query($conn,"

SELECT *

FROM orders

WHERE customer_id='$customer_id'

ORDER BY id DESC

");

if(mysqli_num_rows($orderQuery)>0){

?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Order #</th>

<th>Transaction ID</th>

<th>Total</th>

<th>Payment</th>

<th>Payment Phone</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php

while($order=mysqli_fetch_assoc($orderQuery)){

?>

<tr>

<td>

<strong>

#<?php echo $order['id']; ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($order['transaction_id']); ?>

</td>

<td>

<strong class="text-success">

RWF <?php echo number_format($order['total']); ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($order['payment_method']); ?>

</td>

<td>

<?php

echo !empty($order['payment_phone'])

? htmlspecialchars($order['payment_phone'])

: "-";

?>

</td>

<td>

<?php

if($order['payment_status']=="Paid"){

echo '<span class="badge bg-success">Paid</span>';

}elseif($order['payment_status']=="Pending"){

echo '<span class="badge bg-warning text-dark">Pending</span>';

}else{

echo '<span class="badge bg-danger">'.$order['payment_status'].'</span>';

}

?>

</td>

<td>

<?php

switch($order['order_status']){

case "Pending":

echo '<span class="badge bg-warning text-dark">Pending</span>';

break;

case "Processing":

echo '<span class="badge bg-info text-dark">Processing</span>';

break;

case "Shipped":

echo '<span class="badge bg-primary">Shipped</span>';

break;

case "Delivered":

echo '<span class="badge bg-success">Delivered</span>';

break;

case "Cancelled":

echo '<span class="badge bg-danger">Cancelled</span>';

break;

default:

echo '<span class="badge bg-secondary">'.$order['order_status'].'</span>';

}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($order['created_at'])); ?>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<?php

}else{

?>

<div class="alert alert-warning">

This customer has not placed any orders yet.

</div>

<?php

}

?>

</div>
<div class="mt-4">

<a

href="index.php"

class="btn btn-dark">

<i class="bi bi-arrow-left-circle-fill"></i>

Back To Customers

</a>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>

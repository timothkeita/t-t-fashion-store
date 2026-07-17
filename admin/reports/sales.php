<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle="Sales Report";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* =====================================
STATISTICS
===================================== */

$totalRevenue=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE payment_status='Paid'
"))['total'];

$totalOrders=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
"))['total'];

$paidOrders=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE payment_status='Paid'
"))['total'];

$pendingOrders=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
WHERE payment_status='Pending'
"))['total'];

/* =====================================
RECENT SALES
===================================== */

$sales=mysqli_query($conn,"
SELECT
orders.*,
customers.fullname
FROM orders
LEFT JOIN customers
ON customers.id=orders.customer_id
ORDER BY orders.created_at DESC
");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-cash-stack"></i>

Sales Report

</h2>

<p class="text-muted">

Monitor sales performance.

</p>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="dashboard-card bg-success">

<h6>Total Revenue</h6>

<h2>RWF <?php echo number_format($totalRevenue); ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-primary">

<h6>Total Orders</h6>

<h2><?php echo $totalOrders; ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-info">

<h6>Paid Orders</h6>

<h2><?php echo $paidOrders; ?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-warning">

<h6>Pending Orders</h6>

<h2><?php echo $pendingOrders; ?></h2>

</div>

</div>

</div>

<div class="content-card">

<h4 class="mb-4">

Recent Sales

</h4>

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Order</th>

<th>Customer</th>

<th>Total</th>

<th>Payment</th>

<th>Status</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($sales)>0){

while($row=mysqli_fetch_assoc($sales)){

?>

<tr>

<td>

#<?php echo $row['id'];?>

</td>

<td>

<?php echo htmlspecialchars($row['fullname']);?>

</td>

<td>

<strong>

RWF <?php echo number_format($row['total']);?>

</strong>

</td>

<td>

<?php

if($row['payment_status']=="Paid"){

echo '<span class="badge bg-success">Paid</span>';

}else{

echo '<span class="badge bg-warning text-dark">Pending</span>';

}

?>

</td>

<td>

<?php echo htmlspecialchars($row['order_status']);?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['created_at']));?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6">

No sales found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
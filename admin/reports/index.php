<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle="Reports";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* ==============================
STATISTICS
============================== */

$totalSales=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(total),0) total
FROM orders
WHERE payment_status='Paid'
"))['total'];

$totalOrders=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM orders
"))['total'];

$totalCustomers=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM customers
"))['total'];

$totalProducts=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
"))['total'];
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-bar-chart-fill"></i>

Business Reports

</h2>

<p class="text-muted">

Sales and business analytics.

</p>

</div>
<div class="row mb-4">

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-success">

<h6>Total Revenue</h6>

<h2>RWF <?php echo number_format($totalSales); ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-primary">

<h6>Total Orders</h6>

<h2><?php echo $totalOrders; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-warning">

<h6>Customers</h6>

<h2><?php echo $totalCustomers; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-danger">

<h6>Products</h6>

<h2><?php echo $totalProducts; ?></h2>

</div>

</div>

</div>
<div class="content-card">

<h4 class="mb-4">

<i class="bi bi-file-earmark-bar-graph-fill"></i>

Available Reports

</h4>

<div class="row">

<div class="col-md-4 mb-3">

<a href="sales.php" class="text-decoration-none">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<i class="bi bi-cash-stack display-4 text-success"></i>

<h5 class="mt-3">

Sales Report

</h5>

<p>

Daily, monthly and yearly revenue.

</p>

</div>

</div>

</a>

</div>

<div class="col-md-4 mb-3">

<a href="products.php" class="text-decoration-none">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<i class="bi bi-bag-fill display-4 text-primary"></i>

<h5 class="mt-3">

Product Report

</h5>

<p>

Best-selling products and stock levels.

</p>

</div>

</div>

</a>

</div>

<div class="col-md-4 mb-3">

<a href="customers.php" class="text-decoration-none">

<div class="card shadow-sm border-0">

<div class="card-body text-center">

<i class="bi bi-people-fill display-4 text-warning"></i>

<h5 class="mt-3">

Customer Report

</h5>

<p>

Customer registration and purchase history.

</p>

</div>

</div>

</a>

</div>

</div>

</div>
</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
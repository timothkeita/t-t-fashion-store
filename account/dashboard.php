<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");

    exit();

}

$pageTitle="My Dashboard";

include("../includes/header.php");
include("../includes/navbar.php");

$customer_id=$_SESSION['customer_id'];

/* ==========================================
CUSTOMER DETAILS
========================================== */

$customerQuery=mysqli_query($conn,"
SELECT *
FROM customers
WHERE id='$customer_id'
LIMIT 1
");

$customer=mysqli_fetch_assoc($customerQuery);

/* ==========================================
TOTAL ORDERS
========================================== */

$orderQuery=mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM orders
WHERE customer_id='$customer_id'
");

$orderData=mysqli_fetch_assoc($orderQuery);

/* ==========================================
TOTAL SPENT
========================================== */

$totalSpent=mysqli_query($conn,"
SELECT SUM(total) AS amount
FROM orders
WHERE customer_id='$customer_id'
");

$spent=mysqli_fetch_assoc($totalSpent);

?>
<div class="container py-5">

<div class="row">

<div class="col-lg-3">

<div class="card shadow-sm">

<div class="card-body text-center">

<i class="bi bi-person-circle display-1 text-primary"></i>

<h4 class="mt-3">

<?php echo htmlspecialchars($customer['fullname']); ?>

</h4>

<p class="text-muted">

<?php echo htmlspecialchars($customer['email']); ?>

</p>

<hr>

<div class="list-group">

<a
href="dashboard.php"
class="list-group-item list-group-item-action active">

<i class="bi bi-speedometer2"></i>

Dashboard

</a>

<a
href="orders.php"
class="list-group-item list-group-item-action">

<i class="bi bi-bag-check"></i>

My Orders

</a>

<a
href="profile.php"
class="list-group-item list-group-item-action">

<i class="bi bi-person"></i>

Profile

</a>

<a
href="support.php"
class="list-group-item list-group-item-action">

<i class="bi bi-chat-dots"></i>

Support

</a>

<a
href="../logout.php"
class="list-group-item list-group-item-action text-danger">

<i class="bi bi-box-arrow-right"></i>

Logout

</a>

</div>

</div>

</div>

</div>

<div class="col-lg-9">

<h2 class="fw-bold mb-4">

Welcome,

<?php echo htmlspecialchars($customer['fullname']); ?>

</h2>

<div class="row">
<div class="col-md-4 mb-4">

<div class="card border-0 shadow text-center">

<div class="card-body">

<i class="bi bi-bag-check-fill display-4 text-primary"></i>

<h2 class="mt-3">

<?php echo $orderData['total']; ?>

</h2>

<p>Total Orders</p>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card border-0 shadow text-center">

<div class="card-body">

<i class="bi bi-cash-stack display-4 text-success"></i>

<h2 class="mt-3">

RWF

<?php

echo number_format($spent['amount'] ?? 0);

?>

</h2>

<p>Total Spent</p>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card border-0 shadow text-center">

<div class="card-body">

<i class="bi bi-person-check-fill display-4 text-warning"></i>

<h2 class="mt-3">

Active

</h2>

<p>Account Status</p>

</div>

</div>

</div>

</div>
<div class="card shadow border-0 mt-4">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Recent Account Information

</h4>

</div>

<div class="card-body">

<table class="table">

<tr>

<th>Full Name</th>

<td>

<?php echo htmlspecialchars($customer['fullname']); ?>

</td>

</tr>

<tr>

<th>Email</th>

<td>

<?php echo htmlspecialchars($customer['email']); ?>

</td>

</tr>

<tr>

<th>Phone</th>

<td>

<?php echo htmlspecialchars($customer['phone']); ?>

</td>

</tr>

<tr>

<th>City</th>

<td>

<?php echo htmlspecialchars($customer['city']); ?>

</td>

</tr>

</table>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
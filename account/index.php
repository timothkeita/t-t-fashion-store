<?php

session_start();

include("../config/config.php");
include("../config/db.php");

/* ==========================================
CHECK LOGIN
========================================== */

if(!isset($_SESSION['customer_id'])){

    header("Location:../login.php");
    exit();

}

$customer_id=$_SESSION['customer_id'];

/* ==========================================
GET CUSTOMER
========================================== */

$customerQuery=mysqli_query($conn,"
SELECT *
FROM customers
WHERE id='$customer_id'
LIMIT 1
");

$customer=mysqli_fetch_assoc($customerQuery);

/* ==========================================
CUSTOMER STATISTICS
========================================== */

$totalOrders=mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_id='$customer_id'
"));

$pendingOrders=mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_id='$customer_id'
AND order_status='Pending'
"));

$completedOrders=mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_id='$customer_id'
AND order_status='Delivered'
"));

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

<div class="row">

<div class="col-lg-3">
<div class="card shadow border-0">

<div class="card-body text-center">

<?php

if(!empty($customer['photo'])){
    $photo = "../assets/images/customers/" . $customer['photo'];
}else{
    $photo = "../assets/images/customers/default.png";
}

?>

<img
src="<?php echo $photo; ?>"
class="rounded-circle mb-3 border shadow"
style="width:140px;height:140px;object-fit:cover;"
alt="Customer Photo">

<h4>

<?php echo htmlspecialchars($customer['fullname']); ?>

</h4>

<p class="text-muted">

<?php echo htmlspecialchars($customer['email']); ?>

</p>

<hr>

<div class="d-grid gap-2">

<a

href="profile.php"

class="btn btn-outline-primary">

<i class="bi bi-person-fill"></i>

My Profile

</a>

<a

href="orders.php"

class="btn btn-outline-success">

<i class="bi bi-bag-check-fill"></i>

My Orders

</a>

<a

href="../logout.php"

class="btn btn-outline-danger">

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

👋

</h2>

<div class="row">

<div class="col-md-4 mb-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-cart-fill display-4 text-primary"></i>

<h5 class="mt-3">

Total Orders

</h5>

<h2>

<?php echo $totalOrders; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-clock-history display-4 text-warning"></i>

<h5 class="mt-3">

Pending Orders

</h5>

<h2>

<?php echo $pendingOrders; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 text-center">

<div class="card-body">

<i class="bi bi-check-circle-fill display-4 text-success"></i>

<h5 class="mt-3">

Completed

</h5>

<h2>

<?php echo $completedOrders; ?>

</h2>

</div>

</div>

</div>

</div>
<div class="card shadow border-0 mt-4">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="bi bi-bag-check-fill"></i>

Recent Orders

</h4>

</div>

<div class="card-body">

<?php

$recentOrders=mysqli_query($conn,"
SELECT *
FROM orders
WHERE customer_id='$customer_id'
ORDER BY id DESC
LIMIT 5
");

if(mysqli_num_rows($recentOrders)>0){

?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Order #</th>

<th>Total</th>

<th>Payment</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

while($order=mysqli_fetch_assoc($recentOrders)){

?>

<tr>

<td>

<strong>

#<?php echo $order['id']; ?>

</strong>

</td>

<td>

RWF

<?php echo number_format($order['total']); ?>

</td>

<td>

<?php echo htmlspecialchars($order['payment_method']); ?>

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

<?php

echo date(

"d M Y",

strtotime($order['created_at'])

);

?>

</td>

<td>

<a

href="order-details.php?id=<?php echo $order['id']; ?>"

class="btn btn-sm btn-primary">

<i class="bi bi-eye-fill"></i>

View

</a>

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

<div class="text-center py-5">

<i class="bi bi-cart-x display-4 text-muted"></i>

<h5 class="mt-3">

No Orders Yet

</h5>

<p class="text-muted">

You haven't placed any orders yet.

</p>

<a

href="../shop.php"

class="btn btn-dark">

Start Shopping

</a>

</div>

<?php

}

?>

</div>

</div>

<div class="row mt-4">

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-person-vcard-fill"></i>

Account Information

</h5>

</div>

<div class="card-body">

<p>

<strong>Full Name:</strong>

<?php echo htmlspecialchars($customer['fullname']); ?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($customer['email']); ?>

</p>

<p>

<strong>Phone:</strong>

<?php echo htmlspecialchars($customer['phone']); ?>

</p>

<p>

<strong>City:</strong>

<?php echo htmlspecialchars($customer['city']); ?>

</p>

<p>

<strong>Country:</strong>

<?php echo htmlspecialchars($customer['country']); ?>

</p>

</div>

</div>

</div>

<div class="col-lg-6">

<div class="card shadow border-0">

<div class="card-header bg-success text-white">

<h5 class="mb-0">

<i class="bi bi-lightning-fill"></i>

Quick Actions

</h5>

</div>

<div class="card-body">

<div class="d-grid gap-3">

<a
href="../shop.php"
class="btn btn-outline-dark">

<i class="bi bi-bag-fill me-2"></i>

Continue Shopping

</a>

<a
href="../cart.php"
class="btn btn-outline-primary">

<i class="bi bi-cart-fill me-2"></i>

View Shopping Cart

</a>

<a
href="profile.php"
class="btn btn-outline-success">

<i class="bi bi-pencil-square me-2"></i>

Edit My Profile

</a>

<a
href="orders.php"
class="btn btn-outline-warning">

<i class="bi bi-bag-check-fill me-2"></i>

My Orders

</a>

<a
href="support.php"
class="btn btn-outline-info">

<i class="bi bi-chat-dots-fill me-2"></i>

Support Center

</a>

</div>

</div>

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
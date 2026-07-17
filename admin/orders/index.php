<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================================
ORDER STATISTICS
========================================== */

$totalOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
"));

$pendingOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE order_status='Pending'
"));

$processingOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE order_status='Processing'
"));

$shippedOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE order_status='Shipped'
"));

$deliveredOrders = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM orders
WHERE order_status='Delivered'
"));

$revenue = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total) AS totalRevenue
FROM orders
WHERE payment_status='Paid'
"));

$totalRevenue = $revenue['totalRevenue'];

if($totalRevenue==""){

$totalRevenue=0;

}

/* ==========================================
GET ORDERS
========================================== */

$orderQuery=mysqli_query($conn,"
SELECT

o.*,

c.fullname,

c.email,

c.phone

FROM orders o

LEFT JOIN customers c

ON c.id=o.customer_id

ORDER BY o.id DESC

");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"

content="width=device-width, initial-scale=1">

<title>

Orders

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet"

href="../../assets/css/dashboard.css">

</head>

<body>

<?php

$pageTitle = "Order Management";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold">

<i class="bi bi-bag-check-fill text-primary"></i>

Order Management

</h3>

<p class="text-muted">

Manage customer orders, verify payments and monitor deliveries.

</p>

</div>

</div>
<div class="row mb-4">

<div class="col-lg-2">

<div class="dashboard-card blue">

<div>

<h5>Total Orders</h5>

<h2>

<?php echo $totalOrders; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-cart-fill"></i>

</div>

</div>

</div>

<div class="col-lg-2">

<div class="dashboard-card yellow">

<div>

<h5>Pending</h5>

<h2>

<?php echo $pendingOrders; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-clock-fill"></i>

</div>

</div>

</div>

<div class="col-lg-2">

<div class="dashboard-card info">

<div>

<h5>Processing</h5>

<h2>

<?php echo $processingOrders; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-box-fill"></i>

</div>

</div>

</div>

<div class="col-lg-2">

<div class="dashboard-card purple">

<div>

<h5>Shipped</h5>

<h2>

<?php echo $shippedOrders; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-truck"></i>

</div>

</div>

</div>

<div class="col-lg-2">

<div class="dashboard-card green">

<div>

<h5>Delivered</h5>

<h2>

<?php echo $deliveredOrders; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-check-circle-fill"></i>

</div>

</div>

</div>

<div class="col-lg-2">

<div class="dashboard-card red">

<div>

<h5>Revenue</h5>

<h4>

RWF

<?php echo number_format($totalRevenue); ?>

</h4>

</div>

<div class="card-icon">

<i class="bi bi-cash-stack"></i>

</div>

</div>

</div>

</div>
<div class="content-card mb-4">

<div class="row">

<div class="col-md-10">

<input

type="text"

id="searchOrder"

class="form-control"

placeholder="Search by Customer Name, Phone or Order ID">

</div>

<div class="col-md-2">

<button

class="btn btn-primary w-100">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

</div>
<div class="content-card">

<h4 class="mb-3">

<i class="bi bi-list-ul"></i>

Customer Orders

</h4>

<div class="table-responsive">

<table

class="table table-hover align-middle"

id="ordersTable">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Payment</th>

<th>Total</th>

<th>Payment Status</th>

<th>Order Status</th>

<th>Date</th>

<th width="170">

Action

</th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($orderQuery)>0){

while($row=mysqli_fetch_assoc($orderQuery)){

?>

<tr>

<!-- Order ID -->

<td>

<strong>

#<?php echo $row['id']; ?>

</strong>

</td>

<!-- Customer -->

<td>

<strong>

<?php

echo htmlspecialchars($row['fullname']);

?>

</strong>

<br>

<small class="text-muted">

<?php echo htmlspecialchars($row['email']); ?>

</small>

<br>

<small>

<i class="bi bi-telephone-fill"></i>

<?php echo htmlspecialchars($row['phone']); ?>

</small>

</td>

<!-- Payment -->

<td>

<strong>

<?php echo htmlspecialchars($row['payment_method']); ?>

</strong>

</td>

<!-- Total -->

<td>

<strong class="text-danger">

RWF <?php echo number_format($row['total']); ?>

</strong>

</td>

<!-- Payment Status -->

<td>

<?php

switch($row['payment_status']){

case "Paid":

echo '<span class="badge bg-success">Paid</span>';

break;

case "Pending":

echo '<span class="badge bg-warning text-dark">Pending</span>';

break;

case "Failed":

echo '<span class="badge bg-danger">Failed</span>';

break;

default:

echo '<span class="badge bg-secondary">'.$row['payment_status'].'</span>';

}

?>

</td>

<!-- Order Status -->

<td>

<?php

switch($row['order_status']){

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

echo '<span class="badge bg-secondary">'.$row['order_status'].'</span>';

}

?>

</td>

<!-- Date -->

<td>

<?php

echo date(

"d M Y",

strtotime($row['created_at'])

);

?>

<br>

<small>

<?php

echo date(

"h:i A",

strtotime($row['created_at'])

);

?>

</small>

</td>

<!-- Actions -->

<td>

<a

href="details.php?id=<?php echo $row['id']; ?>"

class="btn btn-primary btn-sm"

title="View Order">

<i class="bi bi-eye-fill"></i>

</a>

<a

href="update-status.php?id=<?php echo $row['id']; ?>"

class="btn btn-success btn-sm"

title="Update Status">

<i class="bi bi-pencil-square"></i>

</a>

<a

href="invoice.php?id=<?php echo $row['id']; ?>"

class="btn btn-dark btn-sm"

title="Print Invoice"

target="_blank">

<i class="bi bi-printer-fill"></i>

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="8" class="text-center py-5">

<i class="bi bi-cart-x fs-1 text-muted"></i>

<h5 class="mt-3">

No Orders Found

</h5>

<p class="text-muted">

Customer orders will appear here after checkout.

</p>

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

<!-- Bootstrap JavaScript -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

<script>

/* ===========================================
LIVE SEARCH
=========================================== */

const searchOrder = document.getElementById("searchOrder");

searchOrder.addEventListener("keyup", function(){

    let filter = this.value.toLowerCase();

    let rows = document.querySelectorAll("#ordersTable tbody tr");

    rows.forEach(function(row){

        let text = row.innerText.toLowerCase();

        if(text.indexOf(filter) > -1){

            row.style.display="";

        }else{

            row.style.display="none";

        }

    });

});

</script>

</body>

</html>
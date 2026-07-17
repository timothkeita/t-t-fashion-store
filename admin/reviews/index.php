<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

/* ==========================================
CHECK ADMIN LOGIN
========================================== */

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle="Reviews";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* ==========================================
STATISTICS
========================================== */

$totalReviews=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reviews
"))['total'];

$approvedReviews=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reviews
WHERE status='Approved'
"))['total'];

$pendingReviews=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reviews
WHERE status='Pending'
"))['total'];

$rejectedReviews=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reviews
WHERE status='Rejected'
"))['total'];

/* ==========================================
GET ALL REVIEWS
========================================== */

$reviews=mysqli_query($conn,"
SELECT

reviews.*,

customers.fullname,

customers.email,

products.product_name

FROM reviews

LEFT JOIN customers
ON customers.id=reviews.customer_id

LEFT JOIN products
ON products.id=reviews.product_id

ORDER BY reviews.id DESC
");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-star-fill"></i>

Product Reviews

</h2>

<p class="text-muted">

Manage customer product reviews.

</p>

</div>
<div class="row mb-4">

    <div class="col-lg-3 mb-3">

        <div class="dashboard-card bg-primary">

            <h6>Total Reviews</h6>

            <h2><?php echo $totalReviews; ?></h2>

        </div>

    </div>

    <div class="col-lg-3 mb-3">

        <div class="dashboard-card bg-success">

            <h6>Approved</h6>

            <h2><?php echo $approvedReviews; ?></h2>

        </div>

    </div>

    <div class="col-lg-3 mb-3">

        <div class="dashboard-card bg-warning">

            <h6>Pending</h6>

            <h2><?php echo $pendingReviews; ?></h2>

        </div>

    </div>

    <div class="col-lg-3 mb-3">

        <div class="dashboard-card bg-danger">

            <h6>Rejected</h6>

            <h2><?php echo $rejectedReviews; ?></h2>

        </div>

    </div>

</div>
<div class="content-card">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4>

<i class="bi bi-star-fill"></i>

Customer Reviews

</h4>

<span class="badge bg-primary">

<?php echo $totalReviews; ?>

Reviews

</span>

</div>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Product</th>

<th>Rating</th>

<th>Status</th>

<th>Date</th>

<th class="text-center">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($reviews)>0){

while($row=mysqli_fetch_assoc($reviews)){

?>

<tr>

<td>

<strong>#<?php echo $row['id']; ?></strong>

</td>

<td>

<strong>

<?php echo htmlspecialchars($row['fullname']); ?>

</strong>

<br>

<small class="text-muted">

<?php echo htmlspecialchars($row['email']); ?>

</small>

</td>

<td>

<?php echo htmlspecialchars($row['product_name']); ?>

</td>

<td>

<?php

for($i=1;$i<=5;$i++){

echo ($i <= $row['rating']) ? "⭐" : "☆";

}

?>

</td>

<td>

<?php

switch($row['status']){

case "Approved":

echo '<span class="badge bg-success">Approved</span>';

break;

case "Rejected":

echo '<span class="badge bg-danger">Rejected</span>';

break;

default:

echo '<span class="badge bg-warning text-dark">Pending</span>';

}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['created_at'])); ?>

</td>

<td class="text-center">

<a

href="view.php?id=<?php echo $row['id']; ?>"

class="btn btn-primary btn-sm">

<i class="bi bi-eye-fill"></i>

View

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7">

<div class="alert alert-warning mb-0 text-center">

No reviews found.

</div>

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
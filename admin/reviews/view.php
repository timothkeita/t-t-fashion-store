<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id=(int)$_GET['id'];

$review=mysqli_query($conn,"
SELECT

reviews.*,

customers.fullname,

customers.email,

customers.phone,

products.product_name,

products.image

FROM reviews

LEFT JOIN customers
ON customers.id=reviews.customer_id

LEFT JOIN products
ON products.id=reviews.product_id

WHERE reviews.id='$id'

LIMIT 1
");

if(mysqli_num_rows($review)==0){

header("Location:index.php");

exit();

}

$data=mysqli_fetch_assoc($review);

/* ==========================
APPROVE REVIEW
========================== */

if(isset($_POST['approve'])){

mysqli_query($conn,"
UPDATE reviews
SET status='Approved'
WHERE id='$id'
");

header("Location:view.php?id=".$id);

exit();

}

/* ==========================
REJECT REVIEW
========================== */

if(isset($_POST['reject'])){

mysqli_query($conn,"
UPDATE reviews
SET status='Rejected'
WHERE id='$id'
");

header("Location:view.php?id=".$id);

exit();

}

$pageTitle="Review Details";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2>

<i class="bi bi-star-fill"></i>

Review Details

</h2>

</div>

<div class="content-card">

<div class="row">

<div class="col-md-3 text-center">

<img

src="../../assets/images/products/<?php echo $data['image'];?>"

class="img-fluid rounded"

style="max-height:220px;object-fit:cover;">

</div>

<div class="col-md-9">

<table class="table">

<tr>

<th width="180">

Customer

</th>

<td>

<?php echo $data['fullname'];?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?php echo $data['email'];?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?php echo $data['phone'];?>

</td>

</tr>

<tr>

<th>

Product

</th>

<td>

<?php echo $data['product_name'];?>

</td>

</tr>

<tr>

<th>

Rating

</th>

<td>
<?php

for($i=1;$i<=5;$i++){

echo ($i<=$data['rating']) ? "⭐" : "☆";

}

?>

</td>

</tr>

<tr>

<th>Status</th>

<td>

<?php

if($data['status']=="Approved"){

echo '<span class="badge bg-success">Approved</span>';

}elseif($data['status']=="Rejected"){

echo '<span class="badge bg-danger">Rejected</span>';

}else{

echo '<span class="badge bg-warning text-dark">Pending</span>';

}

?>

</td>

</tr>

<tr>

<th>Date</th>

<td>

<?php echo date("d F Y H:i",strtotime($data['created_at']));?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<h4>

Customer Review

</h4>

<div class="alert alert-light">

<?php echo nl2br(htmlspecialchars($data['review']));?>

</div>

<hr>

<form method="POST">

<button

name="approve"

class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Approve

</button>

<button

name="reject"

class="btn btn-danger">

<i class="bi bi-x-circle-fill"></i>

Reject

</button>

<a

href="index.php"

class="btn btn-secondary">

Back

</a>

</form>

</div>

</div>

<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>
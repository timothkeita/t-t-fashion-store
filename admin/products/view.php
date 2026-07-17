<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================================
CHECK PRODUCT ID
========================================== */

if(!isset($_GET['id'])){

    $_SESSION['message']="Invalid Product.";

    $_SESSION['type']="danger";

    header("Location:index.php");

    exit();

}

$id=(int)$_GET['id'];

/* ==========================================
GET PRODUCT
========================================== */

$productQuery=mysqli_query($conn,"
SELECT

p.*,

c.category_name

FROM products p

LEFT JOIN categories c

ON c.id=p.category_id

WHERE p.id='$id'

LIMIT 1
");

if(mysqli_num_rows($productQuery)==0){

    $_SESSION['message']="Product not found.";

    $_SESSION['type']="danger";

    header("Location:index.php");

    exit();

}

$product=mysqli_fetch_assoc($productQuery);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>

View Product

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../../assets/css/dashboard.css">

</head>

<body>

<?php include("../../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../../includes/topbar.php"); ?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>

<i class="bi bi-eye-fill text-primary"></i>

Product Details

</h3>

<p class="text-muted">

View Product Information

</p>

</div>

<div>

<a

href="edit.php?id=<?php echo $product['id']; ?>"

class="btn btn-warning">

<i class="bi bi-pencil-fill"></i>

Edit

</a>

<a

href="index.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

</div>
<div class="content-card">

<div class="row">

<div class="col-lg-4 text-center">

<img

src="../../assets/images/products/<?php echo $product['image']; ?>"

class="img-fluid rounded shadow"

style="max-height:350px;object-fit:cover;">

</div>

<div class="col-lg-8">

<table class="table table-bordered align-middle">

<tr>

<th width="220">

Product Name

</th>

<td>

<?php echo htmlspecialchars($product['product_name']); ?>

</td>

</tr>

<tr>

<th>

Category

</th>

<td>

<?php echo htmlspecialchars($product['category_name']); ?>

</td>

</tr>

<tr>

<th>

Brand

</th>

<td>

<?php echo htmlspecialchars($product['brand']); ?>

</td>

</tr>

<tr>

<th>

Price

</th>

<td>

<strong>

RWF <?php echo number_format($product['price']); ?>

</strong>

</td>

</tr>

<tr>

<th>

Discount

</th>

<td>

RWF <?php echo number_format($product['discount']); ?>

</td>

</tr>

<tr>

<th>

Final Price

</th>

<td>

<strong class="text-success">

RWF

<?php echo number_format($product['price']-$product['discount']); ?>

</strong>

</td>

</tr>

<tr>

<th>

Stock

</th>

<td>

<?php echo $product['stock']; ?>

</td>

</tr>
<tr>

<th>

Size

</th>

<td>

<?php echo htmlspecialchars($product['size']); ?>

</td>

</tr>

<tr>

<th>

Color

</th>

<td>

<?php echo htmlspecialchars($product['color']); ?>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php

if($product['status']=="Available"){

?>

<span class="badge bg-success">

Available

</span>

<?php

}else{

?>

<span class="badge bg-danger">

Out Of Stock

</span>

<?php

}

?>

</td>

</tr>

<tr>

<th>

Featured Product

</th>

<td>

<?php

if(isset($product['featured']) && $product['featured']=="Yes"){

?>

<span class="badge bg-warning text-dark">

<i class="bi bi-star-fill"></i>

Featured

</span>

<?php

}else{

?>

<span class="badge bg-secondary">

Normal

</span>

<?php

}

?>

</td>

</tr>

<tr>

<th>

Date Added

</th>

<td>

<?php

echo date(

"d F Y",

strtotime($product['created_at'])

);

?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<h4>

Description

</h4>

<div class="p-3 bg-light rounded">

<?php

if(!empty($product['description'])){

echo nl2br(htmlspecialchars($product['description']));

}else{

echo "<span class='text-muted'>No description available.</span>";

}

?>

</div>

<hr>

<div class="d-flex gap-2">

<a

href="edit.php?id=<?php echo $product['id']; ?>"

class="btn btn-warning">

<i class="bi bi-pencil-square"></i>

Edit Product

</a>

<a

href="index.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back to Products

</a>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
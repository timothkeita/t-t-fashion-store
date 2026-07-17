<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle="Product Report";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* ==========================
STATISTICS
========================== */

$totalProducts=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
"))['total'];

$availableProducts=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE stock>10
"))['total'];

$lowStock=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE stock BETWEEN 1 AND 10
"))['total'];

$outStock=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM products
WHERE stock=0
"))['total'];

$products=mysqli_query($conn,"
SELECT
products.*,
categories.category_name
FROM products
LEFT JOIN categories
ON categories.id=products.category_id
ORDER BY stock ASC
");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-bag-fill"></i>

Product Report

</h2>

<p class="text-muted">

Inventory and stock management.

</p>

</div>

<div class="row mb-4">

<div class="col-md-3">

<div class="dashboard-card bg-primary">

<h6>Total Products</h6>

<h2><?php echo $totalProducts;?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-success">

<h6>Available</h6>

<h2><?php echo $availableProducts;?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-warning">

<h6>Low Stock</h6>

<h2><?php echo $lowStock;?></h2>

</div>

</div>

<div class="col-md-3">

<div class="dashboard-card bg-danger">

<h6>Out Of Stock</h6>

<h2><?php echo $outStock;?></h2>

</div>

</div>

</div>

<div class="content-card">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Image</th>
<th>Product</th>
<th>Category</th>
<th>Brand</th>
<th>Price</th>
<th>Stock</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($products)){

?>

<tr>

<td>

<img

src="../../assets/images/products/<?php echo $row['image'];?>"

style="width:60px;height:60px;border-radius:10px;object-fit:cover;">

</td>

<td>

<?php echo $row['product_name'];?>

</td>

<td>

<?php echo $row['category_name'];?>

</td>

<td>

<?php echo $row['brand'];?>

</td>

<td>

RWF <?php echo number_format($row['price']);?>

</td>

<td>

<?php echo $row['stock'];?>

</td>

<td>

<?php

if($row['stock']==0){

echo '<span class="badge bg-danger">Out Of Stock</span>';

}elseif($row['stock']<=10){

echo '<span class="badge bg-warning text-dark">Low Stock</span>';

}else{

echo '<span class="badge bg-success">Available</span>';

}

?>

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
<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

if(!isset($_GET['id'])){

header("Location:index.php");

exit();

}

$id=(int)$_GET['id'];

$query=mysqli_query($conn,"
SELECT *
FROM categories
WHERE id='$id'
");

if(mysqli_num_rows($query)==0){

header("Location:index.php");

exit();

}

$category=mysqli_fetch_assoc($query);

$productCount=mysqli_num_rows(mysqli_query($conn,"
SELECT id
FROM products
WHERE category_id='$id'
"));

$products=mysqli_query($conn,"
SELECT *
FROM products
WHERE category_id='$id'
ORDER BY id DESC
");

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

View Category

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

<i class="bi bi-eye-fill text-info"></i>

View Category

</h3>

<p class="text-muted">

Category Information

</p>

</div>

<a
href="index.php"
class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>
<div class="content-card">

<div class="row">

<div class="col-md-4 text-center">

<img

src="../../assets/images/categories/<?php echo $category['image']; ?>"

style="

width:220px;

height:220px;

border-radius:20px;

object-fit:cover;

">

</div>

<div class="col-md-8">

<table class="table">

<tr>

<th width="180">

Category

</th>

<td>

<?php echo $category['category_name']; ?>

</td>

</tr>

<tr>

<th>

Status

</th>

<td>

<?php

if($category['status']=="Active"){

?>

<span class="badge bg-success">

Active

</span>

<?php

}else{

?>

<span class="badge bg-secondary">

Inactive

</span>

<?php

}

?>

</td>

</tr>

<tr>

<th>

Products

</th>

<td>

<?php echo $productCount; ?>

</td>

</tr>

<tr>

<th>

Created

</th>

<td>

<?php

echo date(

"d F Y",

strtotime($category['created_at'])

);

?>

</td>

</tr>

</table>

</div>

</div>

<hr>

<h5>

Description

</h5>

<p>

<?php

echo nl2br($category['description']);

?>

</p>

</div>
<div class="content-card">

<h4>

Products in this Category

</h4>

<div class="table-responsive">

<table class="table">

<thead>

<tr>

<th>

Image

</th>

<th>

Product

</th>

<th>

Price

</th>

<th>

Stock

</th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($products)>0){

while($product=mysqli_fetch_assoc($products)){

?>

<tr>

<td>

<td>

<img

src="../../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"

alt="<?php echo htmlspecialchars($product['product_name']); ?>"

style="

width:70px;

height:70px;

object-fit:cover;

border-radius:10px;

">

</td>

<td>

<?php echo $product['product_name']; ?>

</td>

<td>

RWF

<?php echo number_format($product['price']); ?>

</td>

<td>

<?php echo $product['stock']; ?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="4">

No Products Found

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

</div>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
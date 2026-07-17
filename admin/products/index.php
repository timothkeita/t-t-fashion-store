<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================================
PRODUCT STATISTICS
========================================== */

$totalProducts = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM products
"));

$availableProducts = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM products
WHERE status='Available'
"));

$outStock = mysqli_num_rows(mysqli_query($conn,"
SELECT *
FROM products
WHERE status='Out of Stock'
"));

$featuredProducts = 0;

$checkFeatured = mysqli_query($conn,"
SHOW COLUMNS
FROM products
LIKE 'featured'
");

if(mysqli_num_rows($checkFeatured)>0){

    $featuredProducts = mysqli_num_rows(mysqli_query($conn,"
    SELECT *
    FROM products
    WHERE featured='Yes'
    "));

}

/* ==========================================
GET CATEGORIES
========================================== */

$categoryQuery = mysqli_query($conn,"
SELECT *
FROM categories
ORDER BY category_name ASC
");

/* ==========================================
GET PRODUCTS
========================================== */

$productQuery = mysqli_query($conn,"
SELECT

p.*,

c.category_name

FROM products p

LEFT JOIN categories c

ON c.id = p.category_id

ORDER BY p.id DESC
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Product Management

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

<?php

$pageTitle = "Product Management";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold">

<i class="bi bi-bag-fill text-primary"></i>

Product Management

</h3>

<p class="text-muted">

Manage all fashion products

</p>

</div>

<a
href="add.php"
class="btn btn-primary">

<i class="bi bi-plus-circle-fill"></i>

Add Product

</a>

</div>
<?php

if(isset($_SESSION['message'])){

?>

<div class="alert alert-<?php echo $_SESSION['type']; ?> alert-dismissible fade show">

<?php

echo $_SESSION['message'];

unset($_SESSION['message']);

unset($_SESSION['type']);

?>

<button
class="btn-close"
data-bs-dismiss="alert">
</button>

</div>

<?php

}

?>
<div class="row mb-4">

<div class="col-lg-3">

<div class="dashboard-card blue">

<div>

<h5>Total Products</h5>

<h2>

<?php echo $totalProducts; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-bag-fill"></i>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card green">

<div>

<h5>Available</h5>

<h2>

<?php echo $availableProducts; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-check-circle-fill"></i>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card red">

<div>

<h5>Out Of Stock</h5>

<h2>

<?php echo $outStock; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-x-circle-fill"></i>

</div>

</div>

</div>

<div class="col-lg-3">

<div class="dashboard-card yellow">

<div>

<h5>Featured</h5>

<h2>

<?php echo $featuredProducts; ?>

</h2>

</div>

<div class="card-icon">

<i class="bi bi-star-fill"></i>

</div>

</div>

</div>

</div>
<div class="content-card">

<div class="row mb-4">

<div class="col-md-4">

<input

type="text"

id="searchProduct"

class="form-control"

placeholder="Search Product...">

</div>

<div class="col-md-3">

<select

id="categoryFilter"

class="form-select">

<option value="">

All Categories

</option>

<?php

while($cat=mysqli_fetch_assoc($categoryQuery)){

?>

<option>

<?php

echo $cat['category_name'];

?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-3">

<select

id="statusFilter"

class="form-select">

<option value="">

All Status

</option>

<option>

Available

</option>

<option>

Out of Stock

</option>

</select>

</div>

</div>
<div class="table-responsive">

<table

class="table table-hover align-middle"

id="productTable">

<thead>

<tr>

<th>

Product

</th>

<th>

Category

</th>

<th>

Price

</th>

<th>

Stock

</th>

<th>

Status

</th>

<th>

Featured

</th>

<th width="180">

Action

</th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($productQuery)>0){

while($row=mysqli_fetch_assoc($productQuery)){

?>

<tr>

<!-- Product -->

<td>

<div class="d-flex align-items-center">

<img

src="../../assets/images/products/<?php echo $row['image']; ?>"

alt="Product"

style="

width:70px;

height:70px;

object-fit:cover;

border-radius:12px;

margin-right:15px;

">

<div>

<strong>

<?php echo $row['product_name']; ?>

</strong>

<br>

<small class="text-muted">

Brand:

<?php echo !empty($row['brand']) ? $row['brand'] : 'N/A'; ?>

</small>

</div>

</div>

</td>

<!-- Category -->

<td>

<span class="badge bg-primary">

<?php echo $row['category_name']; ?>

</span>

</td>

<!-- Price -->

<td>

<?php

$finalPrice = $row['price'] - $row['discount'];

?>

<strong>

RWF <?php echo number_format($finalPrice); ?>

</strong>

<?php if($row['discount']>0){ ?>

<br>

<small class="text-danger">

Discount:

RWF <?php echo number_format($row['discount']); ?>

</small>

<?php } ?>

</td>

<!-- Stock -->

<td>

<?php

if($row['stock']==0){

?>

<span class="badge bg-danger">

Out

</span>

<?php

}elseif($row['stock']<=5){

?>

<span class="badge bg-warning text-dark">

<?php echo $row['stock']; ?>

Left

</span>

<?php

}else{

?>

<span class="badge bg-success">

<?php echo $row['stock']; ?>

In Stock

</span>

<?php

}

?>

</td>

<!-- Status -->

<td>

<?php

if($row['status']=="Available"){

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

<!-- Featured -->

<td>

<?php

if(isset($row['featured']) && $row['featured']=="Yes"){

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

<!-- Actions -->

<td>

<a

href="view.php?id=<?php echo $row['id']; ?>"

class="btn btn-info btn-sm"

title="View">

<i class="bi bi-eye-fill"></i>

</a>

<a

href="edit.php?id=<?php echo $row['id']; ?>"

class="btn btn-warning btn-sm"

title="Edit">

<i class="bi bi-pencil-fill"></i>

</a>

<button

class="btn btn-danger btn-sm"

data-bs-toggle="modal"

data-bs-target="#delete<?php echo $row['id']; ?>"

title="Delete">

<i class="bi bi-trash-fill"></i>

</button>

</td>

</tr>

<!-- Delete Modal -->

<div

class="modal fade"

id="delete<?php echo $row['id']; ?>"

tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header bg-danger text-white">

<h5 class="modal-title">

Delete Product

</h5>

<button

type="button"

class="btn-close btn-close-white"

data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<p>

Are you sure you want to delete

<strong>

<?php echo $row['product_name']; ?>

</strong>?

</p>

<p class="text-danger mb-0">

This action cannot be undone.

</p>

</div>

<div class="modal-footer">

<button

type="button"

class="btn btn-secondary"

data-bs-dismiss="modal">

Cancel

</button>

<a

href="delete.php?id=<?php echo $row['id']; ?>"

class="btn btn-danger">

Delete Product

</a>

</div>

</div>

</div>

</div>

<?php

}

}else{

?>

<tr>

<td colspan="7" class="text-center">

No products found.

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

<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

<script>

/* =========================================
LIVE SEARCH
========================================= */

const searchInput = document.getElementById("searchProduct");

searchInput.addEventListener("keyup", function(){

    let filter = this.value.toLowerCase();

    let rows = document.querySelectorAll("#productTable tbody tr");

    rows.forEach(function(row){

        let text = row.innerText.toLowerCase();

        row.style.display = text.includes(filter) ? "" : "none";

    });

});

/* =========================================
CATEGORY FILTER
========================================= */

const categoryFilter = document.getElementById("categoryFilter");

categoryFilter.addEventListener("change", filterProducts);

/* =========================================
STATUS FILTER
========================================= */

const statusFilter = document.getElementById("statusFilter");

statusFilter.addEventListener("change", filterProducts);

/* =========================================
FILTER FUNCTION
========================================= */

function filterProducts(){

    let category = categoryFilter.value.toLowerCase();

    let status = statusFilter.value.toLowerCase();

    let rows = document.querySelectorAll("#productTable tbody tr");

    rows.forEach(function(row){

        let text = row.innerText.toLowerCase();

        let categoryMatch = category === "" || text.includes(category);

        let statusMatch = status === "" || text.includes(status);

        if(categoryMatch && statusMatch){

            row.style.display="";

        }else{

            row.style.display="none";

        }

    });

}

</script>

</body>

</html>
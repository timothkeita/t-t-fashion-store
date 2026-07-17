<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* Get Categories */

$categories = mysqli_query($conn,"
SELECT c.*,
(
SELECT COUNT(*)
FROM products p
WHERE p.category_id=c.id
) AS total_products
FROM categories c
ORDER BY id DESC
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Categories</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>

<?php

$pageTitle = "Category Management";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

?>

<div class="container-fluid mt-4">

<!-- Header -->

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold">

<i class="bi bi-grid-fill text-primary"></i>

Category Management

</h3>

<p class="text-muted">

Manage all fashion categories

</p>

</div>

<a href="add.php" class="btn btn-primary">

<i class="bi bi-plus-circle"></i>

Add Category

</a>

</div>

<!-- Success Message -->

<?php if(isset($_SESSION['success'])){ ?>

<div class="alert alert-success alert-dismissible fade show" role="alert">

<?php
echo $_SESSION['success'];
unset($_SESSION['success']);
?>

<button type="button" class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php } ?>

<div class="content-card">

<div class="row mb-3">

<div class="col-md-4">

<input

type="text"

id="searchCategory"

class="form-control"

placeholder="Search category...">

</div>

</div>

<div class="table-responsive">

<table class="table table-hover align-middle" id="categoryTable">

<thead>

<tr>

<th>Image</th>

<th>Category</th>

<th>Description</th>

<th>Products</th>

<th>Status</th>

<th>Date</th>

<th width="170">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($categories)>0){

while($row=mysqli_fetch_assoc($categories)){

?>

<tr>

<td>

<img

src="../../assets/images/categories/<?php echo $row['image']; ?>"

style="width:60px;height:60px;border-radius:12px;object-fit:cover;">

</td>

<td>

<strong>

<?php echo $row['category_name']; ?>

</strong>

</td>

<td>

<?php echo $row['description']; ?>

</td>

<td>

<span class="badge bg-primary">

<?php echo $row['total_products']; ?>

Products

</span>

</td>

<td>

<?php

if($row['status']=="Active"){

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

<td>

<?php

echo date("d M Y",strtotime($row['created_at']));

?>

</td>

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

<i class="bi bi-pencil-square"></i>

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

id="delete<?php echo $row['id']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5>

Delete Category

</h5>

</div>

<div class="modal-body">

Delete

<strong>

<?php echo $row['category_name']; ?>

</strong>

?

</div>

<div class="modal-footer">

<button

class="btn btn-secondary"

data-bs-dismiss="modal">

Cancel

</button>

<a

href="delete.php?id=<?php echo $row['id']; ?>"

class="btn btn-danger">

Delete

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

No Categories Found

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

<script>

document.getElementById("searchCategory").addEventListener("keyup",function(){

let value=this.value.toLowerCase();

let rows=document.querySelectorAll("#categoryTable tbody tr");

rows.forEach(function(row){

row.style.display=row.innerText.toLowerCase().includes(value)?"":"none";

});

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
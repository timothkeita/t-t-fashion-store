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
SELECT *
FROM products
WHERE id='$id'
LIMIT 1
");

if(mysqli_num_rows($productQuery)==0){

    $_SESSION['message']="Product not found.";

    $_SESSION['type']="danger";

    header("Location:index.php");

    exit();

}

$product=mysqli_fetch_assoc($productQuery);

/* ==========================================
LOAD CATEGORIES
========================================== */

$categories=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name ASC
");

$message="";

$type="";

/* ==========================================
UPDATE PRODUCT
========================================== */

if(isset($_POST['update'])){

$product_name=mysqli_real_escape_string($conn,trim($_POST['product_name']));

$category_id=(int)$_POST['category_id'];

$brand=mysqli_real_escape_string($conn,trim($_POST['brand']));

$description=mysqli_real_escape_string($conn,trim($_POST['description']));

$price=(float)$_POST['price'];

$discount=(float)$_POST['discount'];

$stock=(int)$_POST['stock'];

$size=mysqli_real_escape_string($conn,$_POST['size']);

$color=mysqli_real_escape_string($conn,$_POST['color']);

$status=$_POST['status'];

$featured=$_POST['featured'];

$image=$product['image'];

/* Duplicate Check */

$check=mysqli_query($conn,"
SELECT id
FROM products
WHERE product_name='$product_name'
AND id!='$id'
");

if(mysqli_num_rows($check)>0){

$message="Product already exists.";

$type="warning";

}else{

/* Upload New Image */

if(isset($_FILES['image']) && $_FILES['image']['error']==0){

$allowed=['jpg','jpeg','png','webp'];

$ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

if(in_array($ext,$allowed)){

if($image!="default-product.png"){

$old="../../assets/images/products/".$image;

if(file_exists($old)){

unlink($old);

}

}

$image="product_".time().".".$ext;

move_uploaded_file(

$_FILES['image']['tmp_name'],

"../../assets/images/products/".$image

);

}

}

/* Update */

$update=mysqli_query($conn,"
UPDATE products SET

category_id='$category_id',

product_name='$product_name',

description='$description',

price='$price',

discount='$discount',

stock='$stock',

size='$size',

color='$color',

brand='$brand',

image='$image',

status='$status',

featured='$featured'

WHERE id='$id'
");

if($update){

$_SESSION['message']="Product updated successfully.";

$_SESSION['type']="success";

header("Location:index.php");

exit();

}else{

$message=mysqli_error($conn);

$type="danger";

}

}

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Product</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>

<?php include("../../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../../includes/topbar.php"); ?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>

<i class="bi bi-pencil-square text-warning"></i>

Edit Product

</h3>

<p class="text-muted">

Update Product Information

</p>

</div>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<div class="content-card">

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label>Product Name</label>

<input

type="text"

name="product_name"

class="form-control"

value="<?php echo htmlspecialchars($product['product_name']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label>Category</label>

<select

name="category_id"

class="form-select"

required>

<?php while($cat=mysqli_fetch_assoc($categories)){ ?>

<option

value="<?php echo $cat['id']; ?>"

<?php if($cat['id']==$product['category_id']) echo "selected"; ?>>

<?php echo $cat['category_name']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label>Brand</label>

<input

type="text"

name="brand"

class="form-control"

value="<?php echo htmlspecialchars($product['brand']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>Price</label>

<input

type="number"

step="0.01"

name="price"

class="form-control"

value="<?php echo $product['price']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>Discount</label>

<input

type="number"

step="0.01"

name="discount"

class="form-control"

value="<?php echo $product['discount']; ?>">

</div>

<div class="col-md-6 mb-3">

<label>Stock</label>

<input

type="number"

name="stock"

class="form-control"

value="<?php echo $product['stock']; ?>">
</div>

<div class="col-md-6 mb-3">

<label>Size</label>

<input

type="text"

name="size"

class="form-control"

value="<?php echo htmlspecialchars($product['size']); ?>"

placeholder="S, M, L, XL">

</div>

<div class="col-md-6 mb-3">

<label>Color</label>

<input

type="text"

name="color"

class="form-control"

value="<?php echo htmlspecialchars($product['color']); ?>"

placeholder="Black, White, Blue...">

</div>

<div class="col-12 mb-3">

<label>Description</label>

<textarea

name="description"

rows="5"

class="form-control"><?php echo htmlspecialchars($product['description']); ?></textarea>

</div>

<!-- Product Image -->

<div class="col-md-6 mb-3">

<label>Product Image</label>

<input

type="file"

name="image"

id="image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp"

onchange="previewImage(event)">

<small class="text-muted">

Leave empty to keep the current image.

</small>

</div>

<div class="col-md-6 mb-3 text-center">

<img

id="preview"

src="../../assets/images/products/<?php echo $product['image']; ?>"

style="

width:220px;

height:220px;

object-fit:cover;

border-radius:15px;

border:2px solid #ddd;

padding:5px;

">

</div>

<!-- Status -->

<div class="col-md-6 mb-3">

<label>Status</label>

<select

name="status"

class="form-select">

<option

value="Available"

<?php if($product['status']=="Available") echo "selected"; ?>>

Available

</option>

<option

value="Out of Stock"

<?php if($product['status']=="Out of Stock") echo "selected"; ?>>

Out of Stock

</option>

</select>

</div>

<!-- Featured -->

<div class="col-md-6 mb-3">

<label>Featured Product</label>

<select

name="featured"

class="form-select">

<option

value="No"

<?php if(isset($product['featured']) && $product['featured']=="No") echo "selected"; ?>>

No

</option>

<option

value="Yes"

<?php if(isset($product['featured']) && $product['featured']=="Yes") echo "selected"; ?>>

Yes

</option>

</select>

</div>

</div>

<hr>

<div class="d-flex gap-2">

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="bi bi-save-fill"></i>

Update Product

</button>

<a

href="index.php"

class="btn btn-outline-secondary">

<i class="bi bi-arrow-left"></i>

Cancel

</a>

</div>

</form>

</div>

</div>

</div>

<script>

function previewImage(event){

const reader = new FileReader();

reader.onload = function(){

document.getElementById("preview").src = reader.result;

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
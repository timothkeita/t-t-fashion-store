<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

$message="";
$type="";

/* ==========================================
GET CATEGORIES
========================================== */

$categories=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name ASC
");

/* ==========================================
SAVE PRODUCT
========================================== */

if(isset($_POST['save'])){

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

if(empty($product_name)){

$message="Product name is required.";

$type="danger";

}else{

$check=mysqli_query($conn,"
SELECT id
FROM products
WHERE product_name='$product_name'
LIMIT 1
");

if(mysqli_num_rows($check)>0){

$message="Product already exists.";

$type="warning";

}else{

$image="default-product.png";

if(isset($_FILES['image']) && $_FILES['image']['error']==0){

$allowed=['jpg','jpeg','png','webp'];

$ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

if(in_array($ext,$allowed)){

$image="product_".time().".".$ext;

move_uploaded_file(

$_FILES['image']['tmp_name'],

"../../assets/images/products/".$image

);

}

}

$insert=mysqli_query($conn,"
INSERT INTO products(

category_id,

product_name,

description,

price,

discount,

stock,

size,

color,

brand,

image,

status,

featured

)

VALUES(

'$category_id',

'$product_name',

'$description',

'$price',

'$discount',

'$stock',

'$size',

'$color',

'$brand',

'$image',

'$status',

'$featured'

)

");

if($insert){

$_SESSION['message']="Product added successfully.";

$_SESSION['type']="success";

header("Location:index.php");

exit();

}else{

$message=mysqli_error($conn);

$type="danger";

}

}

}

}

?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Add Product

</title>

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

<i class="bi bi-plus-circle-fill text-primary"></i>

Add Product

</h3>

<p class="text-muted">

Create a new fashion product

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
required>

</div>

<div class="col-md-6 mb-3">

<label>Category</label>

<select
name="category_id"
class="form-select"
required>

<option value="">Select Category</option>

<?php while($cat=mysqli_fetch_assoc($categories)){ ?>

<option value="<?php echo $cat['id']; ?>">

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
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Price (RWF)</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label>Discount (RWF)</label>

<input
type="number"
step="0.01"
name="discount"
value="0"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Stock</label>

<input
type="number"
name="stock"
value="0"
class="form-control">

</div>

<div class="col-md-6 mb-3">

<label>Size</label>

<input
type="text"
name="size"
class="form-control"
placeholder="S, M, L, XL">

</div>

<div class="col-md-6 mb-3">

<label>Color</label>

<input
type="text"
name="color"
class="form-control">

</div>

<div class="col-12 mb-3">

<label>Description</label>

<textarea
name="description"
rows="5"
class="form-control"></textarea>

</div>
<div class="col-md-6 mb-3">

<label>Product Image</label>

<input

type="file"

name="image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp"

onchange="previewImage(event)">

</div>

<div class="col-md-6 text-center">

<img

id="preview"

src="../../assets/images/products/default-product.png"

style="width:220px;height:220px;border-radius:15px;object-fit:cover;border:2px dashed #ccc;">

</div>

<div class="col-md-6">

<label>Status</label>

<select name="status" class="form-select">

<option>Available</option>

<option>Out of Stock</option>

</select>

</div>

<div class="col-md-6">

<label>Featured</label>

<select name="featured" class="form-select">

<option>No</option>

<option>Yes</option>

</select>

</div>

</div>

<hr>

<button

name="save"

class="btn btn-primary">

<i class="bi bi-save-fill"></i>

Save Product

</button>

<a

href="index.php"

class="btn btn-outline-secondary">

Cancel

</a>

</form>

</div>
</div>

</div>

<script>

function previewImage(event){

const reader=new FileReader();

reader.onload=function(){

document.getElementById("preview").src=reader.result;

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
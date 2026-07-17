<?php

$pageTitle = "Product Details";

include("includes/header.php");

/* ==========================================================
CHECK PRODUCT ID
========================================================== */

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){

    header("Location: shop.php");
    exit();

}

$id = (int)$_GET['id'];

/* ==========================================================
GET PRODUCT
========================================================== */

$productQuery = mysqli_query($conn,"

SELECT

p.*,

c.category_name

FROM products p

LEFT JOIN categories c

ON c.id = p.category_id

WHERE

p.id='$id'

AND p.status='Available'

LIMIT 1

");

if(mysqli_num_rows($productQuery)==0){

    header("Location: shop.php");
    exit();

}

$product = mysqli_fetch_assoc($productQuery);

/* ==========================================================
FINAL PRICE
========================================================== */

$finalPrice = $product['price'];

if($product['discount']>0){

    $finalPrice = $product['price'] - $product['discount'];

}

/* ==========================================================
RELATED PRODUCTS
========================================================== */

$relatedProducts = mysqli_query($conn,"

SELECT *

FROM products

WHERE

category_id='".$product['category_id']."'

AND

id!='".$product['id']."'

AND

status='Available'

ORDER BY id DESC

LIMIT 4

");

include("includes/navbar.php");

?>

<!-- ==========================================================
BREADCRUMB
========================================================== -->

<section class="bg-light border-bottom py-4">

<div class="container">

<nav>

<ol class="breadcrumb mb-0">

<li class="breadcrumb-item">

<a href="index.php">

Home

</a>

</li>

<li class="breadcrumb-item">

<a href="shop.php">

Shop

</a>

</li>

<li class="breadcrumb-item">

<a href="shop.php?category=<?php echo $product['category_id']; ?>">

<?php echo htmlspecialchars($product['category_name']); ?>

</a>

</li>

<li class="breadcrumb-item active">

<?php echo htmlspecialchars($product['product_name']); ?>

</li>

</ol>

</nav>

</div>

</section>

<!-- ==========================================================
PRODUCT SECTION
========================================================== -->

<div class="container py-5">

<div class="row">

<!-- PRODUCT IMAGE -->

<div class="col-lg-6">

<div class="card shadow border-0">

<img

src="assets/images/products/<?php echo $product['image']; ?>"

id="mainImage"

class="img-fluid rounded"

style="height:600px;object-fit:cover;">

</div>

<div class="row mt-3">

<div class="col-3">

<img

src="assets/images/products/<?php echo $product['image']; ?>"

class="img-thumbnail"

onclick="changeImage(this.src)"

style="cursor:pointer;">

</div>

</div>

</div>

<!-- PRODUCT DETAILS -->

<div class="col-lg-6">

<span class="badge bg-dark mb-3">

<?php echo htmlspecialchars($product['category_name']); ?>

</span>

<h2 class="fw-bold">

<?php echo htmlspecialchars($product['product_name']); ?>

</h2>

<div class="mb-3">

<span class="text-warning fs-5">

★★★★★

</span>

<small class="text-muted">

(0 Reviews)

</small>

</div>

<p class="text-muted">

Brand:

<strong>

<?php echo htmlspecialchars($product['brand']); ?>

</strong>

</p>

<?php if($product['discount']>0){ ?>

<h3>

<del class="text-muted">

RWF <?php echo number_format($product['price']); ?>

</del>

<br>

<span class="text-danger fw-bold display-6">

RWF <?php echo number_format($finalPrice); ?>

</span>

</h3>

<?php }else{ ?>

<h3 class="text-danger display-6">

RWF <?php echo number_format($product['price']); ?>

</h3>

<?php } ?>

<hr>
<!-- ==========================================
PRODUCT INFORMATION
========================================== -->

<div class="row mb-4">

<div class="col-md-6">

<p class="mb-2">

<strong>Size:</strong>

<span class="badge bg-light text-dark">

<?php echo htmlspecialchars($product['size']); ?>

</span>

</p>

</div>

<div class="col-md-6">

<p class="mb-2">

<strong>Color:</strong>

<span class="badge bg-secondary">

<?php echo htmlspecialchars($product['color']); ?>

</span>

</p>

</div>

</div>

<!-- ==========================================
STOCK STATUS
========================================== -->

<p>

<strong>Availability:</strong>

<?php if($product['stock']>0){ ?>

<span class="badge bg-success">

<i class="bi bi-check-circle-fill"></i>

In Stock (<?php echo $product['stock']; ?> Available)

</span>

<?php }else{ ?>

<span class="badge bg-danger">

<i class="bi bi-x-circle-fill"></i>

Out Of Stock

</span>

<?php } ?>

</p>

<hr>

<!-- ==========================================
PRODUCT DESCRIPTION
========================================== -->

<p class="text-muted">

<?php

if(!empty($product['description'])){

echo nl2br(htmlspecialchars($product['description']));

}else{

echo "No description available.";

}

?>

</p>

<hr>

<!-- ==========================================
QUANTITY
========================================== -->

<form

action="cart/add.php"

method="POST">

<input

type="hidden"

name="product_id"

value="<?php echo $product['id']; ?>">

<label class="fw-bold mb-2">

Quantity

</label>

<div class="input-group mb-4" style="width:170px;">

<button

type="button"

class="btn btn-outline-secondary"

onclick="decreaseQty()">

<i class="bi bi-dash"></i>

</button>

<input

type="number"

name="quantity"

id="qty"

class="form-control text-center"

value="1"

min="1"

max="<?php echo $product['stock']; ?>">

<button

type="button"

class="btn btn-outline-secondary"

onclick="increaseQty()">

<i class="bi bi-plus"></i>

</button>

</div>

<!-- ==========================================
BUTTONS
========================================== -->

<div class="d-grid gap-3">

<button

type="submit"

class="btn btn-warning btn-lg">

<i class="bi bi-cart-plus-fill"></i>

Add To Cart

</button>

<a

href="checkout.php?id=<?php echo $product['id']; ?>"

class="btn btn-dark btn-lg">

<i class="bi bi-lightning-fill"></i>

Buy Now

</a>

<button

type="button"

class="btn btn-outline-danger">

<i class="bi bi-heart-fill"></i>

Add To Wishlist

</button>

</div>

</form>

<hr>

<!-- ==========================================
PRODUCT FEATURES
========================================== -->

<div class="row text-center mt-4">

<div class="col-4">

<i class="bi bi-truck display-6 text-primary"></i>

<p class="small mt-2">

Fast Delivery

</p>

</div>

<div class="col-4">

<i class="bi bi-arrow-repeat display-6 text-success"></i>

<p class="small mt-2">

Easy Returns

</p>

</div>

<div class="col-4">

<i class="bi bi-shield-check display-6 text-warning"></i>

<p class="small mt-2">

Secure Payment

</p>

</div>

</div>

<hr>

<!-- ==========================================
SOCIAL SHARE
========================================== -->

<h6 class="fw-bold">

Share This Product

</h6>

<div class="d-flex gap-2">

<a href="#" class="btn btn-outline-primary">

<i class="bi bi-facebook"></i>

</a>

<a href="#" class="btn btn-outline-info">

<i class="bi bi-twitter-x"></i>

</a>

<a href="#" class="btn btn-outline-success">

<i class="bi bi-whatsapp"></i>

</a>

<a href="#" class="btn btn-outline-danger">

<i class="bi bi-instagram"></i>

</a>

</div>

</div>

</div>

<!-- ==========================================
DESCRIPTION & SPECIFICATIONS
========================================== -->

<div class="container mt-5">

<div class="row">

<div class="col-lg-8">

<div class="card shadow-sm border-0">

<div class="card-header bg-dark text-white">

<h4>

<i class="bi bi-card-text"></i>

Product Description

</h4>

</div>

<div class="card-body">

<?php

if(!empty($product['description'])){

echo nl2br(htmlspecialchars($product['description']));

}else{

?>

<p class="text-muted">

No description available.

</p>

<?php

}

?>

</div>

</div>

</div>

<div class="col-lg-4">

<div class="card shadow-sm border-0">

<div class="card-header bg-primary text-white">

<h4>

Specifications

</h4>

</div>

<div class="card-body">

<table class="table table-sm">

<tr>

<th>Brand</th>

<td><?php echo htmlspecialchars($product['brand']); ?></td>

</tr>

<tr>

<th>Category</th>

<td><?php echo htmlspecialchars($product['category_name']); ?></td>

</tr>

<tr>

<th>Color</th>

<td><?php echo htmlspecialchars($product['color']); ?></td>

</tr>

<tr>

<th>Size</th>

<td><?php echo htmlspecialchars($product['size']); ?></td>

</tr>

<tr>

<th>Stock</th>

<td><?php echo $product['stock']; ?></td>

</tr>

</table>

</div>

</div>

</div>

</div>

</div>
<!-- ==========================================
CUSTOMER REVIEWS
========================================== -->

<div class="container mt-5">

<div class="card shadow-sm border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="bi bi-star-fill"></i>

Customer Reviews

</h4>

</div>

<div class="card-body text-center">

<i class="bi bi-chat-square-text display-4 text-secondary"></i>

<h5 class="mt-3">

No Reviews Yet

</h5>

<p class="text-muted">

Be the first customer to review this product.

</p>

<button class="btn btn-outline-dark" disabled>

Write a Review

</button>

</div>

</div>

</div>

<!-- ==========================================
RELATED PRODUCTS
========================================== -->

<div class="container mt-5">

<h3 class="fw-bold mb-4">

Related Products

</h3>

<div class="row">

<?php

if(mysqli_num_rows($relatedProducts)>0){

while($related=mysqli_fetch_assoc($relatedProducts)){

$relatedPrice = $related['price'];

if($related['discount']>0){

$relatedPrice = $related['price']-$related['discount'];

}

?>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card shadow-sm border-0 h-100">

<div class="position-relative">

<img

src="assets/images/products/<?php echo $related['image']; ?>"

class="card-img-top"

style="height:250px;object-fit:cover;">

<?php if($related['discount']>0){ ?>

<span class="badge bg-danger position-absolute top-0 start-0 m-2">

SALE

</span>

<?php } ?>

</div>

<div class="card-body d-flex flex-column">

<h5>

<?php echo htmlspecialchars($related['product_name']); ?>

</h5>

<p class="text-muted mb-1">

<?php echo htmlspecialchars($related['brand']); ?>

</p>

<?php if($related['discount']>0){ ?>

<p>

<del class="text-muted">

RWF <?php echo number_format($related['price']); ?>

</del>

<br>

<span class="fw-bold text-danger">

RWF <?php echo number_format($relatedPrice); ?>

</span>

</p>

<?php }else{ ?>

<p class="fw-bold text-danger">

RWF <?php echo number_format($related['price']); ?>

</p>

<?php } ?>

<div class="mt-auto">

<a

href="product.php?id=<?php echo $related['id']; ?>"

class="btn btn-outline-dark w-100 mb-2">

<i class="bi bi-eye-fill"></i>

View Details

</a>

<form action="cart/add.php" method="POST">

<input

type="hidden"

name="product_id"

value="<?php echo $related['id']; ?>">

<input

type="hidden"

name="quantity"

value="1">

<button

type="submit"

class="btn btn-warning w-100">

<i class="bi bi-cart-plus-fill"></i>

Add To Cart

</button>

</form>

</div>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="col-12">

<div class="alert alert-info text-center">

No related products found.

</div>

</div>

<?php

}

?>

</div>

</div>

<!-- ==========================================
NEWSLETTER
========================================== -->

<section class="py-5 mt-5 bg-dark text-white">

<div class="container text-center">

<h2 class="fw-bold">

Stay Updated

</h2>

<p>

Subscribe for new arrivals, exclusive discounts and fashion news.

</p>

<form class="row justify-content-center">

<div class="col-md-5">

<input

type="email"

class="form-control"

placeholder="Enter your email">

</div>

<div class="col-md-2 mt-3 mt-md-0">

<button

type="button"

class="btn btn-warning w-100">

Subscribe

</button>

</div>

</form>

</div>

</section>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>

<script>

/* ==========================================
IMAGE GALLERY
========================================== */

function changeImage(src){

document.getElementById("mainImage").src = src;

}

/* ==========================================
QUANTITY
========================================== */

function increaseQty(){

let qty = document.getElementById("qty");

let value = parseInt(qty.value);

let max = parseInt(qty.max);

if(value < max){

qty.value = value + 1;

}

}

function decreaseQty(){

let qty = document.getElementById("qty");

let value = parseInt(qty.value);

if(value > 1){

qty.value = value - 1;

}

}

</script>
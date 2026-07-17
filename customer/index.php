<?php
session_start();

include("config/config.php");
include("config/db.php");

/* ==========================================
STORE SETTINGS
========================================== */

$settings=mysqli_query($conn,"
SELECT *
FROM settings
LIMIT 1
");

$store=mysqli_fetch_assoc($settings);

/* ==========================================
FEATURED PRODUCTS
========================================== */

$featuredProducts=mysqli_query($conn,"
SELECT *
FROM products
WHERE status='Available'
ORDER BY id DESC
LIMIT 8
");

/* ==========================================
CATEGORIES
========================================== */

$categories=mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name ASC
");
?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

T & T Fashion Store

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

<style>

body{

background:#f7f8fa;

font-family:Segoe UI,sans-serif;

}

.navbar{

background:#ffffff;

box-shadow:0 5px 20px rgba(0,0,0,.08);

}

.logo{

height:55px;

}

.hero{

height:620px;

background:url('assets/images/banner/banner1.jpg') center center/cover no-repeat;

position:relative;

display:flex;

align-items:center;

}

.hero::before{

content:'';

position:absolute;

top:0;

left:0;

width:100%;

height:100%;

background:rgba(0,0,0,.55);

}

.hero-content{

position:relative;

color:#fff;

z-index:10;

}

.hero h1{

font-size:60px;

font-weight:700;

}

.hero p{

font-size:22px;

margin:20px 0;

}

.section-title{

font-weight:bold;

margin-bottom:35px;

}

.category-card{

transition:.3s;

border:none;

border-radius:15px;

overflow:hidden;

}

.category-card:hover{

transform:translateY(-8px);

}

.product-card{

border:none;

border-radius:18px;

overflow:hidden;

transition:.3s;

}

.product-card:hover{

transform:translateY(-8px);

box-shadow:0 15px 30px rgba(0,0,0,.12);

}

.product-card img{

height:260px;

object-fit:cover;

}

footer{

background:#111827;

color:#fff;

padding:60px 0;

margin-top:80px;

}

</style>

</head>

<body>
<nav class="navbar navbar-expand-lg sticky-top">

<div class="container">

<a class="navbar-brand fw-bold" href="index.php">

<img

src="assets/images/logo.png"

class="logo me-2">

T & T Fashion Store

</a>

<button

class="navbar-toggler"

data-bs-toggle="collapse"

data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div

class="collapse navbar-collapse"

id="menu">

<ul class="navbar-nav mx-auto">

<li class="nav-item">

<a class="nav-link active" href="index.php">

Home

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="shop.php">

Shop

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="about.php">

About

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="contact.php">

Contact

</a>

</li>

</ul>

<div>

<a href="login.php"

class="btn btn-outline-dark me-2">

Login

</a>

<a href="register.php"

class="btn btn-dark">

Register

</a>

</div>

</div>

</div>

</nav>
<section class="hero">

<div class="container">

<div class="hero-content col-lg-6">

<h1>

Discover Your Perfect Style

</h1>

<p>

Premium Fashion for Men, Women & Kids

Quality clothing, shoes, bags and accessories at affordable prices.

</p>

<a

href="shop.php"

class="btn btn-warning btn-lg">

<i class="bi bi-bag-fill"></i>

Shop Now

</a>

</div>

</div>

</section>
<!-- ==========================================
CATEGORIES
========================================== -->

<section class="py-5">

<div class="container">

<h2 class="section-title text-center">

Shop By Category

</h2>

<div class="row">

<?php

while($category=mysqli_fetch_assoc($categories)){

?>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card category-card shadow-sm">

<img

src="assets/images/categories/<?php echo $category['image']; ?>"

class="card-img-top"

style="height:220px;object-fit:cover;">

<div class="card-body text-center">

<h5 class="fw-bold">

<?php echo htmlspecialchars($category['category_name']); ?>

</h5>

<p class="text-muted">

<?php echo htmlspecialchars($category['description']); ?>

</p>

<a

href="shop.php?category=<?php echo $category['id']; ?>"

class="btn btn-dark">

Browse

</a>

</div>

</div>

</div>

<?php

}

?>

</div>

</div>

</section>
<!-- ==========================================
FEATURED PRODUCTS
========================================== -->

<section class="py-5 bg-light">

<div class="container">

<h2 class="section-title text-center">

Featured Products

</h2>

<div class="row">

<?php

while($product=mysqli_fetch_assoc($featuredProducts)){

?>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card product-card shadow-sm">

<img

src="assets/images/products/<?php echo $product['image']; ?>"

class="card-img-top">

<div class="card-body">

<h5>

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="text-muted mb-1">

<?php echo htmlspecialchars($product['brand']); ?>

</p>

<h4 class="text-danger">

RWF <?php echo number_format($product['price']); ?>

</h4>

<?php

if($product['stock']>0){

?>

<span class="badge bg-success mb-3">

In Stock

</span>

<?php

}else{

?>

<span class="badge bg-danger mb-3">

Out Of Stock

</span>

<?php

}

?>

<div class="d-grid gap-2">

<a

href="product.php?id=<?php echo $product['id']; ?>"

class="btn btn-outline-dark">

<i class="bi bi-eye-fill"></i>

Quick View

</a>

<form action="cart/add.php" method="POST">

<input
type="hidden"
name="product_id"
value="<?php echo $product['id']; ?>">

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

?>

</div>

</div>

</section>
<section class="py-5">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<img

src="assets/images/banner/banner2.jpg"

class="img-fluid rounded shadow">

</div>

<div class="col-lg-6">

<h2 class="fw-bold">

New Collection 2026

</h2>

<p class="lead">

Discover the latest fashion trends with premium quality clothing, shoes, handbags and accessories.

Upgrade your wardrobe today with T & T Fashion Store.

</p>

<a

href="shop.php"

class="btn btn-dark btn-lg">

Shop Collection

</a>

</div>

</div>

</div>

</section>
<!-- ==========================================
WHY CHOOSE US
========================================== -->

<section class="py-5 bg-white">

<div class="container">

<h2 class="section-title text-center">

Why Shop With Us

</h2>

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<div class="p-4 shadow rounded">

<i class="bi bi-truck display-4 text-primary"></i>

<h4 class="mt-3">

Free Delivery

</h4>

<p>

Fast and reliable delivery across Rwanda.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="p-4 shadow rounded">

<i class="bi bi-shield-lock-fill display-4 text-success"></i>

<h4 class="mt-3">

Secure Payment

</h4>

<p>

Safe and trusted online payment methods.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="p-4 shadow rounded">

<i class="bi bi-arrow-repeat display-4 text-warning"></i>

<h4 class="mt-3">

Easy Returns

</h4>

<p>

Simple return policy for your convenience.

</p>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="p-4 shadow rounded">

<i class="bi bi-headset display-4 text-danger"></i>

<h4 class="mt-3">

24/7 Support

</h4>

<p>

We're always here to help you.

</p>

</div>

</div>

</div>

</div>

</section>
<!-- ==========================================
NEWSLETTER
========================================== -->

<section class="py-5 bg-dark text-white">

<div class="container text-center">

<h2>

Subscribe To Our Newsletter

</h2>

<p>

Receive updates about new arrivals, discounts and exclusive fashion collections.

</p>

<form class="row justify-content-center">

<div class="col-lg-5">

<input

type="email"

class="form-control form-control-lg"

placeholder="Enter your email">

</div>

<div class="col-lg-2">

<button

class="btn btn-warning btn-lg w-100"

type="submit">

Subscribe

</button>

</div>

</form>

</div>

</section>
<!-- ==========================================
FOOTER
========================================== -->

<footer>

<div class="container">

<div class="row">

<div class="col-lg-4">

<h3>

T & T Fashion Store

</h3>

<p>

Your trusted online fashion destination for premium clothing, shoes, handbags and accessories.

</p>

</div>

<div class="col-lg-2">

<h5>

Quick Links

</h5>

<ul class="list-unstyled">

<li>

<a href="index.php" class="text-white text-decoration-none">

Home

</a>

</li>

<li>

<a href="shop.php" class="text-white text-decoration-none">

Shop

</a>

</li>

<li>

<a href="about.php" class="text-white text-decoration-none">

About

</a>

</li>

<li>

<a href="contact.php" class="text-white text-decoration-none">

Contact

</a>

</li>

</ul>

</div>

<div class="col-lg-3">

<h5>

Customer Service

</h5>

<p>

Email:

support@ttfashionstore.com

</p>

<p>

Phone:

+250 788 000 000

</p>

<p>

Kigali, Rwanda

</p>

</div>

<div class="col-lg-3">

<h5>

Follow Us

</h5>

<a href="#" class="text-white fs-3 me-3">

<i class="bi bi-facebook"></i>

</a>

<a href="#" class="text-white fs-3 me-3">

<i class="bi bi-instagram"></i>

</a>

<a href="#" class="text-white fs-3 me-3">

<i class="bi bi-twitter-x"></i>

</a>

<a href="#" class="text-white fs-3">

<i class="bi bi-tiktok"></i>

</a>

</div>

</div>

<hr class="my-4">

<div class="text-center">

© <?php echo date("Y"); ?>

T & T Fashion Store.

All Rights Reserved.

</div>

</div>

</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
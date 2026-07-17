<?php

$pageTitle="Home";

include("includes/header.php");

/* ==========================================================
GET FEATURED PRODUCTS
========================================================== */

$featuredQuery=mysqli_query($conn,"

SELECT

p.*,

c.category_name

FROM products p

LEFT JOIN categories c

ON c.id=p.category_id

WHERE p.status='Available'

ORDER BY p.id DESC

LIMIT 12

");

/* ==========================================================
GET CATEGORIES
========================================================== */

$categoryQuery=mysqli_query($conn,"

SELECT *

FROM categories

WHERE status='Active'

ORDER BY category_name ASC

LIMIT 12

");

include("includes/navbar.php");

?>

<!-- ==========================================================
HERO SECTION
========================================================== -->

<section class="bg-dark text-white py-5">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<h5 class="text-warning">

NEW COLLECTION 2026

</h5>

<h1 class="display-3 fw-bold">

Style That Defines You

</h1>

<p class="lead">

Discover premium fashion for men, women and kids.

Shop the latest collections at affordable prices.

</p>

<div class="mt-4">

<a

href="shop.php"

class="btn btn-warning btn-lg me-3">

<i class="bi bi-bag-fill"></i>

Shop Now

</a>

<a

href="categories.php"

class="btn btn-outline-light btn-lg">

Browse Categories

</a>

</div>

</div>

<div class="col-lg-6 text-center">

<img

src="assets/images/banner/fashion-banner.png"

class="img-fluid"

style="max-height:500px;">

</div>

</div>

</div>

</section>
<!-- ==========================================================
FEATURED CATEGORIES
========================================================== -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

    Shop By Category

</h2>

<p class="text-muted">

    Browse our fashion collections by category.

</p>

</div>

<div class="row">

<?php

while($category=mysqli_fetch_assoc($categoryQuery)){

?>

<div class="col-lg-3 col-md-6 mb-4">

    <div class="card shadow border-0 h-100 category-card">

        <!-- Category Image -->

        <img
        src="assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>"
        class="card-img-top"
        alt="<?php echo htmlspecialchars($category['category_name']); ?>"
        style="height:240px;object-fit:cover;">

        <div class="card-body text-center">

            <h4 class="fw-bold">

                <?php echo htmlspecialchars($category['category_name']); ?>

            </h4>

            <?php

            if(!empty($category['description'])){

            ?>

            <p class="text-muted">

                <?php echo htmlspecialchars($category['description']); ?>

            </p>

            <?php

            }else{

            ?>

            <p class="text-muted">

                Explore our latest collection.

            </p>

            <?php

            }

            ?>

            <a
            href="shop.php?category=<?php echo $category['id']; ?>"
            class="btn btn-dark mt-3">

                <i class="bi bi-bag-fill"></i>

                Browse Products

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
<!-- ==========================================================
NEW ARRIVALS
========================================================== -->

<section class="py-5">

<div class="container">

<div class="row mb-5">

<div class="col-md-6">

<h2 class="fw-bold">

New Arrivals

</h2>

<p class="text-muted">

Discover the latest fashion products added to our store.

</p>

</div>

<div class="col-md-6 text-end">

<a

href="shop.php"

class="btn btn-outline-dark">

View All Products

</a>

</div>

</div>

<div class="row">

<?php

if(mysqli_num_rows($featuredQuery)>0){

while($product=mysqli_fetch_assoc($featuredQuery)){

?>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card product-card shadow-sm border-0 h-100">

<div class="position-relative">

<img

src="assets/images/products/<?php echo $product['image']; ?>"

class="card-img-top"

style="height:280px;object-fit:cover;">

<?php

if($product['discount']>0){

?>

<span

class="badge bg-danger position-absolute top-0 start-0 m-2">

-<?php echo number_format($product['discount']); ?> RWF

</span>

<?php

}

?>

<?php

if($product['stock']>0){

?>

<span

class="badge bg-success position-absolute top-0 end-0 m-2">

In Stock

</span>

<?php

}else{

?>

<span

class="badge bg-secondary position-absolute top-0 end-0 m-2">

Out of Stock

</span>

<?php

}

?>

</div>

<div class="card-body d-flex flex-column">

<h5 class="fw-bold">

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="text-muted mb-1">

<?php echo htmlspecialchars($product['brand']); ?>

</p>

<p class="small text-secondary">

<?php echo htmlspecialchars($product['category_name']); ?>

</p>

<?php

if($product['discount']>0){

?>

<p>

<span class="text-decoration-line-through text-muted">

RWF <?php echo number_format($product['price']); ?>

</span>

<br>

<span class="fs-5 text-danger fw-bold">

RWF

<?php

echo number_format(

$product['price']-$product['discount']

);

?>

</span>

</p>

<?php

}else{

?>

<p class="fs-5 text-danger fw-bold">

RWF

<?php echo number_format($product['price']); ?>

</p>

<?php

}

?>

<div class="mt-auto">

<div class="d-grid gap-2">

<a

href="product.php?id=<?php echo $product['id']; ?>"

class="btn btn-outline-dark">

<i class="bi bi-eye-fill"></i>

View Details

</a>

<?php

if($product['stock']>0){

?>

<form

action="cart/add.php"

method="POST">

<input

type="hidden"

name="product_id"

value="<?php echo $product['id']; ?>">

<input

type="hidden"

name="quantity"

value="1">

<button

class="btn btn-warning w-100"

type="submit">

<i class="bi bi-cart-plus-fill"></i>

Add To Cart

</button>

</form>

<?php

}else{

?>

<button

class="btn btn-secondary"

disabled>

Out Of Stock

</button>

<?php

}

?>

</div>

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

<h4>

No Products Available

</h4>

<p>

Products added by the administrator will appear here.

</p>

<a

href="shop.php"

class="btn btn-dark">

Visit Shop

</a>

</div>

</div>

<?php

}

?>

</div>

</div>

</section>
<!-- ==========================================================
WHY CHOOSE US
========================================================== -->

<section class="py-5 bg-light">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Why Shop With T & T Fashion Store?

</h2>

<p class="text-muted">

We provide quality fashion products and excellent customer service.

</p>

</div>

<div class="row">

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 text-center">

<div class="card-body">

<i class="bi bi-truck display-3 text-primary"></i>

<h4 class="mt-3">

Fast Delivery

</h4>

<p class="text-muted">

Quick and reliable delivery across Rwanda.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 text-center">

<div class="card-body">

<i class="bi bi-shield-lock-fill display-3 text-success"></i>

<h4 class="mt-3">

Secure Payments

</h4>

<p class="text-muted">

Safe and secure payment methods for every order.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 text-center">

<div class="card-body">

<i class="bi bi-arrow-repeat display-3 text-warning"></i>

<h4 class="mt-3">

Easy Returns

</h4>

<p class="text-muted">

Simple return and exchange process.

</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 text-center">

<div class="card-body">

<i class="bi bi-headset display-3 text-danger"></i>

<h4 class="mt-3">

24/7 Support

</h4>

<p class="text-muted">

Our support team is always ready to help you.

</p>

</div>

</div>

</div>

</div>

</div>

</section>
<!-- ==========================================================
STORE STATISTICS
========================================================== -->

<section class="py-5">

<div class="container">

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 fw-bold text-warning">

50+

</h1>

<p class="text-muted">

Fashion Products

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 fw-bold text-primary">

100+

</h1>

<p class="text-muted">

Happy Customers

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 fw-bold text-success">

24/7

</h1>

<p class="text-muted">

Customer Support

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<h1 class="display-4 fw-bold text-danger">

100%

</h1>

<p class="text-muted">

Secure Shopping

</p>

</div>

</div>

</div>

</section>
<!-- ==========================================================
NEWSLETTER
========================================================== -->

<section class="py-5 bg-dark text-white">

<div class="container text-center">

<h2 class="fw-bold">

Stay Updated

</h2>

<p>

Subscribe to receive the latest fashion trends, new arrivals and exclusive offers.

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
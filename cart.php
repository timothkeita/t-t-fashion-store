<?php

$pageTitle = "Shopping Cart";

include("includes/header.php");

/* ==========================================================
INITIALIZE CART
========================================================== */

$grandTotal = 0;

$totalItems = 0;

if(!isset($_SESSION['cart'])){

    $_SESSION['cart'] = [];

}

foreach($_SESSION['cart'] as $item){

    $price = $item['price'];

    if($item['discount'] > 0){

        $price = $item['price'] - $item['discount'];

    }

    $grandTotal += ($price * $item['quantity']);

    $totalItems += $item['quantity'];

}

include("includes/navbar.php");

?>

<!-- ==========================================================
PAGE HEADER
========================================================== -->

<section class="bg-light border-bottom py-4">

<div class="container">

<div class="row align-items-center">

<div class="col-md-6">

<h1 class="fw-bold">

<i class="bi bi-cart3"></i>

Shopping Cart

</h1>

<p class="text-muted mb-0">

Review your selected items before proceeding to checkout.

</p>

</div>

<div class="col-md-6 text-md-end">

<nav>

<ol class="breadcrumb justify-content-md-end mb-0">

<li class="breadcrumb-item">

<a href="index.php">

Home

</a>

</li>

<li class="breadcrumb-item active">

Cart

</li>

</ol>

</nav>

</div>

</div>

</div>

</section>

<!-- ==========================================================
SUCCESS MESSAGE
========================================================== -->

<div class="container mt-4">

<?php

if(isset($_SESSION['success'])){

?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle-fill"></i>

<?php

echo $_SESSION['success'];

unset($_SESSION['success']);

?>

<button

type="button"

class="btn-close"

data-bs-dismiss="alert">

</button>

</div>

<?php

}

?>

</div>

<!-- ==========================================================
CART SECTION
========================================================== -->

<div class="container py-5">

<div class="row">

<div class="col-lg-8">

<div class="card shadow-sm border-0">

<div class="card-header bg-dark text-white">

<div class="d-flex justify-content-between align-items-center">

<h4 class="mb-0">

<i class="bi bi-bag-fill"></i>

Shopping Cart

</h4>

<span class="badge bg-warning text-dark">

<?php echo $totalItems; ?>

Item(s)

</span>

</div>

</div>

<div class="card-body p-0">

<?php

if(count($_SESSION['cart'])>0){

?>
<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>

<th width="120">

Product

</th>

<th>

Details

</th>

<th width="140">

Price

</th>

<th width="170">

Quantity

</th>

<th width="150">

Subtotal

</th>

<th width="80">

Remove

</th>

</tr>

</thead>

<tbody>

<?php

foreach($_SESSION['cart'] as $item){

$price = $item['price'];

if($item['discount']>0){

$price = $item['price']-$item['discount'];

}

$subtotal = $price * $item['quantity'];

?>

<tr>

<!-- PRODUCT IMAGE -->

<td>

<img

src="assets/images/products/<?php echo $item['image']; ?>"

class="img-fluid rounded"

style="width:90px;height:90px;object-fit:cover;">

</td>

<!-- PRODUCT DETAILS -->

<td>

<h5 class="fw-bold mb-1">

<?php echo htmlspecialchars($item['name']); ?>

</h5>

<p class="mb-1 text-muted">

Brand:

<strong>

<?php echo htmlspecialchars($item['brand']); ?>

</strong>

</p>

<p class="mb-1">

Size:

<span class="badge bg-light text-dark">

<?php echo htmlspecialchars($item['size']); ?>

</span>

</p>

<p class="mb-0">

Color:

<span class="badge bg-secondary">

<?php echo htmlspecialchars($item['color']); ?>

</span>

</p>

</td>

<!-- PRICE -->

<td>

<?php if($item['discount']>0){ ?>

<del class="text-muted">

RWF <?php echo number_format($item['price']); ?>

</del>

<br>

<strong class="text-danger">

RWF <?php echo number_format($price); ?>

</strong>

<?php }else{ ?>

<strong class="text-danger">

RWF <?php echo number_format($price); ?>

</strong>

<?php } ?>

</td>

<!-- QUANTITY -->

<td>

<form

action="cart/update.php"

method="POST">

<input

type="hidden"

name="product_id"

value="<?php echo $item['id']; ?>">

<div class="input-group">

<button

type="button"

class="btn btn-outline-secondary"

onclick="decreaseCartQty(<?php echo $item['id']; ?>)">

<i class="bi bi-dash"></i>

</button>

<input

type="number"

id="qty<?php echo $item['id']; ?>"

name="quantity"

class="form-control text-center"

value="<?php echo $item['quantity']; ?>"

min="1"

max="<?php echo $item['stock']; ?>">

<button

type="button"

class="btn btn-outline-secondary"

onclick="increaseCartQty(<?php echo $item['id']; ?>)">

<i class="bi bi-plus"></i>

</button>

</div>

<button

class="btn btn-sm btn-primary w-100 mt-2"

type="submit">

Update

</button>

</form>

</td>

<!-- SUBTOTAL -->

<td>

<strong class="text-danger fs-5">

RWF

<?php echo number_format($subtotal); ?>

</strong>

</td>

<!-- REMOVE -->

<td>

<a

href="cart/remove.php?id=<?php echo $item['id']; ?>"

class="btn btn-danger"

onclick="return confirm('Remove this item from your cart?');">

<i class="bi bi-trash-fill"></i>

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

<?php

}else{

?>

<div class="text-center py-5">

<i class="bi bi-cart-x display-1 text-muted"></i>

<h3 class="mt-4">

Your Shopping Cart Is Empty

</h3>

<p class="text-muted">

Looks like you haven't added any products yet.

</p>

<a

href="shop.php"

class="btn btn-dark btn-lg mt-3">

<i class="bi bi-bag-fill"></i>

Start Shopping

</a>

</div>

<?php

}

?>

</div>
<!-- ==========================================================
RIGHT SIDEBAR
========================================================== -->

<div class="col-lg-4">

<!-- COUPON -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-ticket-perforated-fill"></i>

Coupon Code

</h5>

</div>

<div class="card-body">

<form>

<div class="input-group">

<input

type="text"

class="form-control"

placeholder="Enter Coupon">

<button

class="btn btn-dark"

type="button">

Apply

</button>

</div>

</form>

<small class="text-muted">

Coupon feature will be activated later.

</small>

</div>

</div>

<!-- ORDER SUMMARY -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Order Summary

</h4>

</div>

<div class="card-body">

<table class="table">

<tr>

<th>

Items

</th>

<td class="text-end">

<?php echo $totalItems; ?>

</td>

</tr>

<tr>

<th>

Subtotal

</th>

<td class="text-end">

RWF

<?php echo number_format($grandTotal); ?>

</td>

</tr>

<tr>

<th>

Delivery

</th>

<td class="text-end text-success">

FREE

</td>

</tr>

<tr>

<th>

Discount

</th>

<td class="text-end">

RWF 0

</td>

</tr>

<tr>

<th class="fs-5">

Grand Total

</th>

<td class="text-end text-danger fw-bold fs-4">

RWF

<?php echo number_format($grandTotal); ?>

</td>

</tr>

</table>

<hr>

<div class="mb-4">

<h6 class="fw-bold">

<i class="bi bi-truck"></i>

Delivery Information

</h6>

<p class="small text-muted mb-1">

🚚 Estimated delivery:

<strong>

2–4 Business Days

</strong>

</p>

<p class="small text-muted mb-1">

✔ Secure Checkout

</p>

<p class="small text-muted mb-1">

✔ Free Delivery

</p>

<p class="small text-muted">

✔ Easy Returns

</p>

</div>

<div class="d-grid gap-2">

<a

href="checkout.php"

class="btn btn-warning btn-lg">

<i class="bi bi-credit-card-fill"></i>

Proceed To Checkout

</a>

<a

href="shop.php"

class="btn btn-outline-dark">

<i class="bi bi-arrow-left-circle"></i>

Continue Shopping

</a>

<?php if(count($_SESSION['cart'])>0){ ?>

<a

href="cart/clear.php"

class="btn btn-outline-danger"

onclick="return confirm('Are you sure you want to clear your cart?');">

<i class="bi bi-trash-fill"></i>

Clear Cart

</a>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

</div>
<!-- ==========================================================
RECOMMENDED PRODUCTS
========================================================== -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

You May Also Like

</h2>

<p class="text-muted">

Discover more fashion products you might love.

</p>

</div>

<div class="row">

<?php

$recommended = mysqli_query($conn,"
SELECT *
FROM products
WHERE status='Available'
ORDER BY RAND()
LIMIT 4
");

while($row=mysqli_fetch_assoc($recommended)){

$price = $row['price'];

if($row['discount']>0){

$price = $row['price']-$row['discount'];

}

?>

<div class="col-lg-3 col-md-6 mb-4">

<div class="card shadow-sm border-0 h-100">

<div class="position-relative">

<img

src="assets/images/products/<?php echo $row['image']; ?>"

class="card-img-top"

style="height:260px;object-fit:cover;">

<?php if($row['discount']>0){ ?>

<span class="badge bg-danger position-absolute top-0 start-0 m-2">

SALE

</span>

<?php } ?>

</div>

<div class="card-body d-flex flex-column">

<h5 class="fw-bold">

<?php echo htmlspecialchars($row['product_name']); ?>

</h5>

<p class="text-muted">

<?php echo htmlspecialchars($row['brand']); ?>

</p>

<?php if($row['discount']>0){ ?>

<p>

<del class="text-muted">

RWF <?php echo number_format($row['price']); ?>

</del>

<br>

<span class="text-danger fw-bold fs-5">

RWF <?php echo number_format($price); ?>

</span>

</p>

<?php }else{ ?>

<p class="text-danger fw-bold fs-5">

RWF <?php echo number_format($price); ?>

</p>

<?php } ?>

<div class="mt-auto">

<a

href="product.php?id=<?php echo $row['id']; ?>"

class="btn btn-outline-dark w-100 mb-2">

<i class="bi bi-eye-fill"></i>

View Details

</a>

<form action="cart/add.php" method="POST">

<input
type="hidden"
name="product_id"
value="<?php echo $row['id']; ?>">

<input
type="hidden"
name="quantity"
value="1">

<button
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

<!-- ==========================================================
NEWSLETTER
========================================================== -->

<section class="py-5 bg-dark text-white">

<div class="container text-center">

<h2 class="fw-bold">

Subscribe To Our Newsletter

</h2>

<p>

Receive exclusive discounts, fashion trends and new arrivals.

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
QUANTITY BUTTONS
========================================== */

function increaseCartQty(id){

let qty=document.getElementById("qty"+id);

let max=parseInt(qty.max);

let value=parseInt(qty.value);

if(value<max){

qty.value=value+1;

}

}

function decreaseCartQty(id){

let qty=document.getElementById("qty"+id);

let value=parseInt(qty.value);

if(value>1){

qty.value=value-1;

}

}

</script>
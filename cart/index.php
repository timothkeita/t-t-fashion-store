<?php

session_start();

$pageTitle="Shopping Cart";

include("../config/config.php");
include("../config/db.php");

include("../includes/header.php");
include("../includes/navbar.php");

/* ==========================================
INITIALIZE TOTAL
========================================== */

$grandTotal=0;

?>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="bi bi-cart3"></i>

Shopping Cart

</h2>

<p class="text-muted">

Review your selected products before checkout.

</p>

</div>

<a href="../shop.php" class="btn btn-outline-dark">

<i class="bi bi-arrow-left"></i>

Continue Shopping

</a>

</div>

<?php

if(isset($_SESSION['success'])){

?>

<div class="alert alert-success alert-dismissible fade show">

<?php

echo $_SESSION['success'];

unset($_SESSION['success']);

?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php

}

?>

<div class="card shadow border-0">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-dark">

<tr>

<th width="120">

Image

</th>

<th>

Product

</th>

<th width="150">

Price

</th>

<th width="170">

Quantity

</th>

<th width="170">

Subtotal

</th>

<th width="100">

Remove

</th>

</tr>

</thead>

<tbody>
<?php

if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){

foreach($_SESSION['cart'] as $item){

$price=$item['price'];

if($item['discount']>0){

$price=$item['price']-$item['discount'];

}

$subtotal=$price*$item['quantity'];

$grandTotal+=$subtotal;

?>

<tr>

<td>

<img

src="../assets/images/products/<?php echo $item['image']; ?>"

style="

width:90px;

height:90px;

object-fit:cover;

border-radius:10px;

">

</td>

<td>

<h5>

<?php echo htmlspecialchars($item['name']); ?>

</h5>

<p class="text-muted">

Stock:

<?php echo $item['stock']; ?>

</p>

</td>

<td>

<strong>

RWF

<?php echo number_format($price); ?>

</strong>

</td>

<td>

<form

action="update.php"

method="POST">

<input

type="hidden"

name="product_id"

value="<?php echo $item['id']; ?>">
<div class="input-group">

<input

type="number"

name="quantity"

value="<?php echo $item['quantity']; ?>"

min="1"

max="<?php echo $item['stock']; ?>"

class="form-control">

<button

class="btn btn-primary"

type="submit">

Update

</button>

</div>

</form>

</td>

<td>

<strong class="text-danger">

RWF

<?php echo number_format($subtotal); ?>

</strong>

</td>

<td>

<a

href="remove.php?id=<?php echo $item['id']; ?>"

class="btn btn-danger">

<i class="bi bi-trash-fill"></i>

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6" class="text-center py-5">

<i class="bi bi-cart-x display-1 text-muted"></i>

<h4 class="mt-3">

Your cart is empty.

</h4>

<a

href="../shop.php"

class="btn btn-dark mt-3">

Start Shopping

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

<!-- ==============================
CART SUMMARY
============================== -->

<div class="row mt-4">

<div class="col-lg-8">

<div class="card shadow-sm border-0">

<div class="card-body">

<h4>

<i class="bi bi-info-circle-fill text-primary"></i>

Shopping Information

</h4>

<p class="text-muted">

Review your cart before proceeding to checkout.

You can update quantities, remove products or continue shopping.

</p>

<div class="d-flex flex-wrap gap-2">

<a

href="../shop.php"

class="btn btn-outline-dark">

<i class="bi bi-arrow-left"></i>

Continue Shopping

</a>

<?php

if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){

?>

<a

href="clear.php"

class="btn btn-outline-danger"

onclick="return confirm('Clear your shopping cart?');">

<i class="bi bi-trash3-fill"></i>

Clear Cart

</a>

<?php

}

?>

</div>

</div>

</div>

</div>

<div class="col-lg-4">

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

<?php

$totalItems=0;

if(isset($_SESSION['cart'])){

foreach($_SESSION['cart'] as $cartItem){

$totalItems += $cartItem['quantity'];

}

}

echo $totalItems;

?>

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

<td class="text-end fs-4 text-danger fw-bold">

RWF

<?php echo number_format($grandTotal); ?>

</td>

</tr>

</table>

<hr>

<?php

if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){

?>

<div class="d-grid">

<a

href="../checkout.php"

class="btn btn-warning btn-lg">

<i class="bi bi-credit-card-fill"></i>

Proceed To Checkout

</a>

</div>

<?php

}else{

?>

<button

class="btn btn-secondary btn-lg"

disabled>

Cart Empty

</button>

<?php

}

?>

</div>

</div>

</div>

</div>
<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>
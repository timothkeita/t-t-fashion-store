<?php

$pageTitle = "Secure Checkout";

include("includes/header.php");

/* ==========================================================
CHECK LOGIN
========================================================== */

if(!isset($_SESSION['customer_id'])){

    $_SESSION['redirect_after_login']="checkout.php";

    header("Location: login.php");

    exit();

}

/* ==========================================================
CHECK CART
========================================================== */

if(
!isset($_SESSION['cart'])
||
count($_SESSION['cart'])==0
){

    header("Location: cart.php");

    exit();

}

/* ==========================================================
GET CUSTOMER
========================================================== */

$customer_id=$_SESSION['customer_id'];

$customerQuery=mysqli_query($conn,"
SELECT *
FROM customers
WHERE id='$customer_id'
LIMIT 1
");

$customer=mysqli_fetch_assoc($customerQuery);

/* ==========================================================
CALCULATE TOTALS
========================================================== */

$totalItems=0;

$grandTotal=0;

foreach($_SESSION['cart'] as $item){

    $price=$item['price'];

    if($item['discount']>0){

        $price=$item['price']-$item['discount'];

    }

    $grandTotal += ($price*$item['quantity']);

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

<i class="bi bi-credit-card-fill"></i>

Secure Checkout

</h1>

<p class="text-muted">

Complete your order safely and securely.

</p>

</div>

<div class="col-md-6 text-md-end">

<nav>

<ol class="breadcrumb justify-content-md-end">

<li class="breadcrumb-item">

<a href="index.php">

Home

</a>

</li>

<li class="breadcrumb-item">

<a href="cart.php">

Cart

</a>

</li>

<li class="breadcrumb-item active">

Checkout

</li>

</ol>

</nav>

</div>

</div>

</div>

</section>

<div class="container py-5">

<div class="row">

<!-- ==========================================
LEFT SIDE
========================================== -->

<div class="col-lg-7">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="bi bi-person-fill"></i>

Billing Details

</h4>

</div>

<div class="card-body">

<form

action="cart/place_order.php"

method="POST">
<!-- ==========================================
BILLING INFORMATION
========================================== -->

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Full Name

</label>

<input

type="text"

class="form-control"

value="<?php echo htmlspecialchars($customer['fullname']); ?>"

readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Email Address

</label>

<input

type="email"

class="form-control"

value="<?php echo htmlspecialchars($customer['email']); ?>"

readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Phone Number

</label>

<input

type="text"

name="phone"

class="form-control"

value="<?php echo htmlspecialchars($customer['phone']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Country

</label>

<input

type="text"

name="country"

class="form-control"

value="Rwanda"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

City

</label>

<input

type="text"

name="city"

class="form-control"

value="<?php echo htmlspecialchars($customer['city']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

District

</label>

<input

type="text"

name="district"

class="form-control"

placeholder="Enter District">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Province

</label>

<select

name="province"

class="form-select"

required>

<option value="">Select Province</option>

<option>Kigali City</option>

<option>Eastern Province</option>

<option>Northern Province</option>

<option>Southern Province</option>

<option>Western Province</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Postal Code

</label>

<input

type="text"

name="postal_code"

class="form-control"

placeholder="Optional">

</div>

<div class="col-12 mb-4">

<label class="form-label">

Delivery Address

</label>

<textarea

name="address"

rows="4"

class="form-control"

required><?php echo htmlspecialchars($customer['address']); ?></textarea>

</div>

<!-- ==========================================
DELIVERY INFORMATION
========================================== -->

<div class="col-12">

<div class="alert alert-info">

<h5>

<i class="bi bi-truck"></i>

Delivery Information

</h5>

<ul class="mb-0">

<li>Free delivery within Kigali.</li>

<li>Delivery takes approximately <strong>2–4 business days</strong>.</li>

<li>You will receive a confirmation call before delivery.</li>

<li>Please ensure your address and phone number are correct.</li>

</ul>

</div>

</div>

</div>

</div>

</div>

</div>

<!-- ==========================================
RIGHT SIDE
========================================== -->

<div class="col-lg-5">
<!-- ==========================================================
PAYMENT & ORDER SUMMARY
========================================================== -->

<div class="card shadow border-0 mb-4">

<div class="card-header bg-warning">

<h4 class="mb-0">

<i class="bi bi-wallet2"></i>

Payment Method

</h4>

</div>

<div class="card-body">

<div class="form-check mb-3">

    <input
    class="form-check-input"
    type="radio"
    name="payment_method"
    id="mtn"
    value="MTN Mobile Money">

    <label class="form-check-label" for="mtn">

        <i class="bi bi-phone-fill text-warning"></i>

        MTN Mobile Money

    </label>

</div>

<div class="form-check mb-3">

    <input
    class="form-check-input"
    type="radio"
    name="payment_method"
    id="airtel"
    value="Airtel Money">

    <label class="form-check-label" for="airtel">

        <i class="bi bi-phone-fill text-danger"></i>

        Airtel Money

    </label>

</div>

<!-- Mobile Money Number -->

<div id="paymentNumberSection" class="mt-3" style="display:none;">

    <label class="form-label fw-bold">

        Mobile Money Number

    </label>

    <input
    type="text"
    name="payment_phone"
    id="payment_phone"
    class="form-control"
    placeholder="07XXXXXXXX">

    <small class="text-muted">

        Enter the number registered for MTN or Airtel Money.

    </small>

</div>

<div class="alert alert-light border">

    <i class="bi bi-shield-lock-fill text-success"></i>

    Your payment information is protected using secure checkout technology.

</div>

</div>
</div>

</div>

</div>

<!-- ==========================================================
ORDER SUMMARY
========================================================== -->

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

<i class="bi bi-cart-check-fill"></i>

Order Summary

</h4>

</div>

<div class="card-body">

<?php

foreach($_SESSION['cart'] as $item){

$price=$item['price'];

if($item['discount']>0){

$price=$item['price']-$item['discount'];

}

$subtotal=$price*$item['quantity'];

?>

<div class="d-flex justify-content-between mb-3">

<div>

<strong>

<?php echo htmlspecialchars($item['name']); ?>

</strong>

<br>

<small class="text-muted">

Qty: <?php echo $item['quantity']; ?>

</small>

</div>

<div class="text-end">

<strong>

RWF <?php echo number_format($subtotal); ?>

</strong>

</div>

</div>

<hr>

<?php

}

?>

<table class="table">

<tr>

<th>Total Items</th>

<td class="text-end">

<?php echo $totalItems; ?>

</td>

</tr>

<tr>

<th>Subtotal</th>

<td class="text-end">

RWF <?php echo number_format($grandTotal); ?>

</td>

</tr>

<tr>

<th>Delivery</th>

<td class="text-end text-success">

FREE

</td>

</tr>

<tr>

<th>Discount</th>

<td class="text-end">

RWF 0

</td>

</tr>

<tr>

<th class="fs-5">

Grand Total

</th>

<td class="text-end">

<span class="text-danger fw-bold fs-4">

RWF <?php echo number_format($grandTotal); ?>

</span>

</td>

</tr>

</table>

<input

type="hidden"

name="grand_total"

value="<?php echo $grandTotal; ?>">

<hr>

<div class="mb-4">

<h6 class="fw-bold">

<i class="bi bi-shield-check text-success"></i>

Secure Checkout

</h6>

<p class="small text-muted mb-2">

✔ SSL Encrypted Checkout

</p>

<p class="small text-muted mb-2">

✔ Free Delivery

</p>

<p class="small text-muted mb-2">

✔ Easy Returns

</p>

<p class="small text-muted">

✔ 24/7 Customer Support

</p>

</div>

<div class="d-grid">

<button

type="submit"

class="btn btn-success btn-lg">

<i class="bi bi-check-circle-fill"></i>

Place Order

</button>

</div>

</div>

</div>

</div>

</div>

</div>
<!-- ==========================================================
WHY SHOP WITH US
========================================================== -->

<section class="py-5 mt-5">

<div class="container">

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-truck display-4 text-primary"></i>

<h5 class="mt-3">

Fast Delivery

</h5>

<p class="text-muted">

Fast and reliable delivery throughout Rwanda.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-shield-check display-4 text-success"></i>

<h5 class="mt-3">

Secure Payment

</h5>

<p class="text-muted">

Your payments are protected with secure checkout.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-arrow-repeat display-4 text-warning"></i>

<h5 class="mt-3">

Easy Returns

</h5>

<p class="text-muted">

Simple exchange and return policy.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-headset display-4 text-danger"></i>

<h5 class="mt-3">

24/7 Support

</h5>

<p class="text-muted">

Our customer support team is always ready to help.

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

Subscribe To Our Newsletter

</h2>

<p>

Receive exclusive discounts, new arrivals and fashion updates.

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

</form>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>

<script>

/* ==========================================================
PAYMENT METHOD
========================================================== */

const paymentMethods = document.querySelectorAll("input[name='payment_method']");

const paymentSection = document.getElementById("paymentNumberSection");

const paymentPhone = document.getElementById("payment_phone");

paymentMethods.forEach(function(method){

    method.addEventListener("change", function(){

        paymentSection.style.display = "block";

        paymentPhone.required = true;

    });

});

</script>
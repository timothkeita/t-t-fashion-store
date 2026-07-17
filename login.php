<?php

session_start();

include("config/config.php");
include("config/db.php");

/* ==========================================
CHECK LOGIN
========================================== */

if(isset($_SESSION['customer_id'])){

    header("Location:account/index.php");

    exit();

}

$message="";

$type="";

if(isset($_POST['login'])){

$email=mysqli_real_escape_string($conn,trim($_POST['email']));

$password=$_POST['password'];

$query=mysqli_query($conn,"
SELECT *
FROM customers
WHERE email='$email'
LIMIT 1
");

if(mysqli_num_rows($query)==1){

$customer=mysqli_fetch_assoc($query);

/* CHECK STATUS */

if($customer['status']!="Active"){

$message="Your account has been disabled.";

$type="danger";

}else{

/* VERIFY PASSWORD */

if(password_verify($password,$customer['password'])){

$_SESSION['customer_id']=$customer['id'];

$_SESSION['customer_name']=$customer['fullname'];

$_SESSION['customer_email']=$customer['email'];

mysqli_query($conn,"
UPDATE customers
SET last_login=NOW()
WHERE id='".$customer['id']."'
");

header("Location:account/index.php");

exit();

}else{

$message="Incorrect password.";

$type="danger";

}

}

}else{

$message="Account not found.";

$type="danger";

}

}

$pageTitle="Customer Login";

include("includes/header.php");

include("includes/navbar.php");

?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-5">

<div class="card shadow-lg border-0">

<div class="card-header bg-dark text-white text-center">

<h3>

<i class="bi bi-box-arrow-in-right"></i>

Customer Login

</h3>

<p class="mb-0">

Welcome back to T & T Fashion Store

</p>

</div>

<div class="card-body p-4">

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">
<div class="mb-3">

<label>

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

Password

</label>

<input

type="password"

name="password"

class="form-control"

required>

</div>

<div class="form-check mb-3">

<input

type="checkbox"

class="form-check-input"

id="remember">

<label

class="form-check-label"

for="remember">

Remember Me

</label>

</div>
<div class="d-grid mb-3">

<button

type="submit"

name="login"

class="btn btn-dark btn-lg">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</div>

<div class="text-center mb-3">

<a

href="forgot-password.php"

class="text-decoration-none">

Forgot Password?

</a>

</div>

<hr>

<div class="text-center">

Don't have an account?

<a

href="register.php"

class="fw-bold text-decoration-none">

Create Account

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</div>
<?php

include("includes/footer.php");

include("includes/scripts.php");

?>   
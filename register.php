<?php

session_start();

include("config/config.php");
include("config/db.php");

if(isset($_SESSION['customer_id'])){

    header("Location:account/index.php");
    exit();

}

$message="";
$type="";

if(isset($_POST['register'])){

$fullname=mysqli_real_escape_string($conn,trim($_POST['fullname']));

$email=mysqli_real_escape_string($conn,trim($_POST['email']));

$phone=mysqli_real_escape_string($conn,trim($_POST['phone']));

$password=$_POST['password'];

$confirm_password=$_POST['confirm_password'];

$address=mysqli_real_escape_string($conn,trim($_POST['address']));

$city=mysqli_real_escape_string($conn,trim($_POST['city']));

$country=mysqli_real_escape_string($conn,trim($_POST['country']));

$photo="default.png";

/* ==========================================
VALIDATION
========================================== */

if(empty($fullname) ||

empty($email) ||

empty($phone) ||

empty($password) ||

empty($confirm_password)){

$message="Please fill in all required fields.";

$type="danger";

}elseif($password!=$confirm_password){

$message="Passwords do not match.";

$type="danger";

}else{

/* CHECK EMAIL */

$check=mysqli_query($conn,"
SELECT id
FROM customers
WHERE email='$email'
LIMIT 1
");

if(mysqli_num_rows($check)>0){

$message="Email address already exists.";

$type="warning";

}else{

/* PHOTO */

if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

$allowed=['jpg','jpeg','png','webp'];

$ext=strtolower(pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION));

if(in_array($ext,$allowed)){

$photo="customer_".time().".".$ext;

move_uploaded_file(

$_FILES['photo']['tmp_name'],

"assets/images/customers/".$photo

);

}

}

/* PASSWORD */

$hashedPassword=password_hash(

$password,

PASSWORD_DEFAULT

);

/* INSERT */

$insert=mysqli_query($conn,"
INSERT INTO customers(

fullname,

email,

phone,

password,

address,

city,

country,

photo,

status

)

VALUES(

'$fullname',

'$email',

'$phone',

'$hashedPassword',

'$address',

'$city',

'$country',

'$photo',

'Active'

)
");

if($insert){

$_SESSION['success']="Registration successful. Please login.";

header("Location:login.php");

exit();

}else{

$message=mysqli_error($conn);

$type="danger";

}

}

}

}

$pageTitle="Customer Registration";

include("includes/header.php");

include("includes/navbar.php");

?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card shadow-lg border-0">

<div class="card-header bg-dark text-white text-center">

<h3>

<i class="bi bi-person-plus-fill"></i>

Create Your Account

</h3>

<p class="mb-0">

Join T & T Fashion Store today.

</p>

</div>

<div class="card-body p-4">

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="row">
<div class="col-md-6 mb-3">

<label>

Full Name *

</label>

<input

type="text"

name="fullname"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Email Address *

</label>

<input

type="email"

name="email"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Phone Number *

</label>

<input

type="text"

name="phone"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Country

</label>

<input

type="text"

name="country"

class="form-control"

value="Rwanda">

</div>

<div class="col-md-6 mb-3">

<label>

Password *

</label>

<input

type="password"

name="password"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Confirm Password *

</label>

<input

type="password"

name="confirm_password"

class="form-control"

required>

</div>
<div class="col-12 mb-3">

<label>

Address

</label>

<textarea

name="address"

class="form-control"

rows="3"

placeholder="Enter your address"></textarea>

</div>

<div class="col-md-6 mb-3">

<label>

City

</label>

<input

type="text"

name="city"

class="form-control"

placeholder="Enter your city">

</div>

<div class="col-md-6 mb-3">

<label>

Profile Photo (Optional)

</label>

<input

type="file"

name="photo"

class="form-control"

accept=".jpg,.jpeg,.png,.webp">

<small class="text-muted">

Upload a profile picture (optional)

</small>

</div>

<div class="col-12 mt-3">

<div class="form-check">

<input

class="form-check-input"

type="checkbox"

id="terms"

required>

<label

class="form-check-label"

for="terms">

I agree to the Terms & Conditions

</label>

</div>

</div>

<div class="col-12 mt-4">

<div class="d-grid">

<button

type="submit"

name="register"

class="btn btn-dark btn-lg">

<i class="bi bi-person-plus-fill"></i>

Create Account

</button>

</div>

</div>

<div class="col-12 mt-4 text-center">

Already have an account?

<a

href="login.php"

class="text-decoration-none fw-bold">

Login Here

</a>

</div>

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
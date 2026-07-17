<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");
    exit();

}

$pageTitle="My Profile";

include("../includes/header.php");
include("../includes/navbar.php");

$customer_id=$_SESSION['customer_id'];

$query=mysqli_query($conn,"
SELECT *
FROM customers
WHERE id='$customer_id'
LIMIT 1
");

$customer=mysqli_fetch_assoc($query);

?>
<div class="container py-5">

<div class="row">

<div class="col-lg-3">


<div class="card shadow border-0">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            <i class="bi bi-person-circle"></i>

            My Account

        </h5>

    </div>

    <div class="list-group list-group-flush">

        <a
        href="index.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-speedometer2 me-2"></i>

            Dashboard

        </a>

        <a
        href="orders.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-bag-check-fill me-2"></i>

            My Orders

        </a>

        <a
        href="profile.php"
        class="list-group-item list-group-item-action active">

            <i class="bi bi-person-fill me-2"></i>

            My Profile

        </a>

        <a
        href="change_password.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-key-fill me-2"></i>

            Change Password

        </a>

        <a
        href="support.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-chat-dots-fill me-2"></i>

            Support Center

        </a>

        <a
        href="../logout.php"
        class="list-group-item list-group-item-action text-danger">

            <i class="bi bi-box-arrow-right me-2"></i>

            Logout

        </a>

    </div>

</div>

</div>

</div>

<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

Edit Profile

</h4>

</div>

<div class="card-body">

<form

action="update_profile.php"

method="POST">
<div class="row">

<div class="col-md-6 mb-3">

<label>

Full Name

</label>

<input

type="text"

name="fullname"

class="form-control"

value="<?php echo htmlspecialchars($customer['fullname']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Email

</label>

<input

type="email"

name="email"

class="form-control"

value="<?php echo htmlspecialchars($customer['email']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Phone

</label>

<input

type="text"

name="phone"

class="form-control"

value="<?php echo htmlspecialchars($customer['phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label>

City

</label>

<input

type="text"

name="city"

class="form-control"

value="<?php echo htmlspecialchars($customer['city']); ?>">

</div>

<div class="col-12 mb-3">

<label>

Address

</label>

<textarea

name="address"

rows="4"

class="form-control"><?php echo htmlspecialchars($customer['address']); ?></textarea>

</div>

</div>
<div class="d-grid">

<button

type="submit"

class="btn btn-success btn-lg">

<i class="bi bi-save-fill"></i>

Update Profile

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
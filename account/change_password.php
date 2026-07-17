<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");

    exit();

}

$pageTitle="Change Password";

include("../includes/header.php");
include("../includes/navbar.php");

$customer_id=$_SESSION['customer_id'];

?>
<div class="container py-5">

<div class="row">

<div class="col-lg-3">

<div class="card shadow-sm">

<div class="card-body">

<h4 class="mb-4">

My Account

</h4>

<div class="list-group">

<a
href="dashboard.php"
class="list-group-item list-group-item-action">

Dashboard

</a>

<a
href="orders.php"
class="list-group-item list-group-item-action">

My Orders

</a>

<a
href="profile.php"
class="list-group-item list-group-item-action">

Profile

</a>

<a
href="change_password.php"
class="list-group-item list-group-item-action active">

Change Password

</a>

<a
href="support.php"
class="list-group-item list-group-item-action">

Support

</a>

<a
href="../logout.php"
class="list-group-item list-group-item-action text-danger">

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

Change Password

</h4>

</div>

<div class="card-body">

<form

action="update_password.php"

method="POST">
<div class="mb-3">

<label>

Current Password

</label>

<input

type="password"

name="current_password"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

New Password

</label>

<input

type="password"

name="new_password"

class="form-control"

required>

</div>

<div class="mb-4">

<label>

Confirm Password

</label>

<input

type="password"

name="confirm_password"

class="form-control"

required>

</div>

<div class="d-grid">

<button

class="btn btn-success btn-lg">

<i class="bi bi-shield-lock-fill"></i>

Update Password

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
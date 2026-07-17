<?php
session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:login.php");
    exit();
}

$pageTitle="My Profile";

/* Get Admin Information */

$admin_id = $_SESSION['admin'];

$query = mysqli_query($conn,"
SELECT *
FROM admins
WHERE id='$admin_id'
LIMIT 1
");

$admin = mysqli_fetch_assoc($query);

if(!$admin){
    die("Administrator account not found.");
}

include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-person-circle"></i>

My Profile

</h2>

<p class="text-muted">

Manage your administrator account.

</p>

</div>
<div class="row">

<div class="col-lg-4">

<div class="card shadow-sm">

<div class="card-body text-center">

<?php

$photo="default.png";

if(!empty($admin['photo'])){

$photo=$admin['photo'];

}

?>

<img

src="../assets/images/admins/<?php echo htmlspecialchars($photo);?>"

class="rounded-circle shadow"

style="width:170px;height:170px;object-fit:cover;">

<h3 class="mt-3">

<?php echo htmlspecialchars($admin['fullname']);?>

</h3>

<span class="badge bg-success">

Administrator

</span>

</div>

</div>

</div>
<div class="col-lg-8">

<div class="card shadow-sm">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

Account Information

</h5>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th width="30%">

Full Name

</th>

<td>

<?php echo htmlspecialchars($admin['fullname']);?>

</td>

</tr>

<tr>

<th>

Username

</th>

<td>

<?php echo htmlspecialchars($admin['username']);?>

</td>

</tr>

<tr>

<th>

Email

</th>

<td>

<?php echo htmlspecialchars($admin['email']);?>

</td>

</tr>

<tr>

<th>

Phone

</th>

<td>

<?php

echo !empty($admin['phone'])

? htmlspecialchars($admin['phone'])

: "-";

?>

</td>

</tr>

<tr>

<th>

Member Since

</th>

<td>

<?php echo date("d F Y",strtotime($admin['created_at']));?>

</td>

</tr>

</table>
<div class="mt-4">

<a

href="edit-profile.php"

class="btn btn-primary">

<i class="bi bi-pencil-square"></i>

Edit Profile

</a>

<a

href="change-password.php"

class="btn btn-warning">

<i class="bi bi-lock-fill"></i>

Change Password

</a>

</div>

</div>

</div>

</div>

</div>
</div>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>
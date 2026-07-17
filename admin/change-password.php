<?php
session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:login.php");
    exit();
}

$pageTitle="Change Password";

$username=$_SESSION['admin'];

$query=mysqli_query($conn,"
SELECT *
FROM admins
WHERE username='$username'
LIMIT 1
");

$admin=mysqli_fetch_assoc($query);

$message="";
$error="";

if(isset($_POST['change_password'])){

    $current=$_POST['current_password'];
    $new=$_POST['new_password'];
    $confirm=$_POST['confirm_password'];

    if(empty($current) || empty($new) || empty($confirm)){

        $error="All fields are required.";

    }elseif(!password_verify($current,$admin['password'])){

        $error="Current password is incorrect.";

    }elseif(strlen($new)<6){

        $error="New password must be at least 6 characters.";

    }elseif($new!=$confirm){

        $error="New passwords do not match.";

    }else{

        $hash=password_hash($new,PASSWORD_DEFAULT);

        mysqli_query($conn,"
        UPDATE admins
        SET password='$hash'
        WHERE id='{$admin['id']}'
        ");

        $message="Password changed successfully.";

    }

}

include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-lock-fill"></i>

Change Password

</h2>

<p class="text-muted">

Update your administrator password.

</p>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="card shadow-sm">

<div class="card-header bg-warning">

<h5 class="mb-0">

Security

</h5>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Current Password</label>

<input
type="password"
name="current_password"
class="form-control"
required>

</div>

<div class="mb-3">

<label>New Password</label>

<input
type="password"
name="new_password"
class="form-control"
required>

</div>

<div class="mb-4">

<label>Confirm New Password</label>

<input
type="password"
name="confirm_password"
class="form-control"
required>

</div>

<button
type="submit"
name="change_password"
class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Change Password

</button>

<a
href="profile.php"
class="btn btn-secondary">

Cancel

</a>

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
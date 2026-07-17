<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");

    exit();

}

$customer_id=$_SESSION['customer_id'];

$current=$_POST['current_password'];

$new=$_POST['new_password'];

$confirm=$_POST['confirm_password'];

$query=mysqli_query($conn,"
SELECT password
FROM customers
WHERE id='$customer_id'
");

$user=mysqli_fetch_assoc($query);

/* ==================================================
VERIFY CURRENT PASSWORD
================================================== */

if($current != $user['password']){

    $_SESSION['error']="Current password is incorrect.";

    header("Location: change_password.php");

    exit();

}

/* ==================================================
MATCH PASSWORDS
================================================== */

if($new != $confirm){

    $_SESSION['error']="Passwords do not match.";

    header("Location: change_password.php");

    exit();

}

/* ==================================================
UPDATE PASSWORD
================================================== */

mysqli_query($conn,"
UPDATE customers
SET password='$new'
WHERE id='$customer_id'
");

$_SESSION['success']="Password updated successfully.";

header("Location: profile.php");

exit();

?>

<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");
    exit();

}

$customer_id=$_SESSION['customer_id'];

$fullname=mysqli_real_escape_string($conn,$_POST['fullname']);
$email=mysqli_real_escape_string($conn,$_POST['email']);
$phone=mysqli_real_escape_string($conn,$_POST['phone']);
$city=mysqli_real_escape_string($conn,$_POST['city']);
$address=mysqli_real_escape_string($conn,$_POST['address']);

mysqli_query($conn,"
UPDATE customers
SET
fullname='$fullname',
email='$email',
phone='$phone',
city='$city',
address='$address'
WHERE id='$customer_id'
");

$_SESSION['success']="Profile updated successfully.";

header("Location: profile.php");

exit();

?>

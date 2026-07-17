<?php

session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){

header("Location:../login.php");

exit();

}

$id=(int)$_GET['id'];

mysqli_query($conn,"
DELETE
FROM reviews
WHERE id='$id'
");

header("Location:index.php");

exit();

?>
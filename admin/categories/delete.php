<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ===========================
CHECK ID
=========================== */

if(!isset($_GET['id'])){

    $_SESSION['success'] = "Invalid category.";

    header("Location:index.php");

    exit();

}

$id = (int)$_GET['id'];

/* ===========================
GET CATEGORY
=========================== */

$categoryQuery = mysqli_query($conn,"
SELECT *
FROM categories
WHERE id='$id'
");

if(mysqli_num_rows($categoryQuery)==0){

    $_SESSION['success'] = "Category not found.";

    header("Location:index.php");

    exit();

}

$category = mysqli_fetch_assoc($categoryQuery);

/* ===========================
CHECK PRODUCTS
=========================== */

$productCheck = mysqli_query($conn,"
SELECT id
FROM products
WHERE category_id='$id'
LIMIT 1
");

if(mysqli_num_rows($productCheck)>0){

    $_SESSION['success'] = "You cannot delete this category because it contains products.";

    header("Location:index.php");

    exit();

}

/* ===========================
DELETE IMAGE
=========================== */

if($category['image']!="default-category.png"){

    $image="../../assets/images/categories/".$category['image'];

    if(file_exists($image)){

        unlink($image);

    }

}

/* ===========================
DELETE CATEGORY
=========================== */

$delete = mysqli_query($conn,"
DELETE FROM categories
WHERE id='$id'
");

if($delete){

    $_SESSION['success']="Category deleted successfully.";

}else{

    $_SESSION['success']="Unable to delete category.";

}

header("Location:index.php");

exit();

?>
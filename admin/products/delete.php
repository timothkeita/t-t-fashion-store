<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");


/* ==========================================
CHECK PRODUCT ID
========================================== */

if(!isset($_GET['id'])){


    $_SESSION['message']="Invalid Product.";

    $_SESSION['type']="danger";


    header("Location:index.php");

    exit();


}


$id=(int)$_GET['id'];



/* ==========================================
GET PRODUCT
========================================== */


$productQuery=mysqli_query($conn,"
SELECT *
FROM products
WHERE id='$id'
LIMIT 1
");



if(mysqli_num_rows($productQuery)==0){


    $_SESSION['message']="Product not found.";

    $_SESSION['type']="danger";


    header("Location:index.php");

    exit();

}



$product=mysqli_fetch_assoc($productQuery);



/* ==========================================
DELETE IMAGE
========================================== */


if($product['image']!="default-product.png"){


    $imagePath="../../assets/images/products/".$product['image'];



    if(file_exists($imagePath)){


        unlink($imagePath);


    }


}



/* ==========================================
DELETE PRODUCT
========================================== */


$delete=mysqli_query($conn,"
DELETE FROM products
WHERE id='$id'
");



if($delete){


    $_SESSION['message']="Product deleted successfully.";

    $_SESSION['type']="success";


}else{


    $_SESSION['message']="Unable to delete product.";

    $_SESSION['type']="danger";


}



header("Location:index.php");

exit();


?>

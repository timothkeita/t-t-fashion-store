<?php

session_start();

include("../config/config.php");
include("../config/db.php");

/* ==========================================================
CHECK REQUEST METHOD
========================================================== */

if($_SERVER['REQUEST_METHOD'] != "POST"){

    header("Location: ../shop.php");
    exit();

}

/* ==========================================================
CHECK PRODUCT
========================================================== */

if(!isset($_POST['product_id'])){

    $_SESSION['error'] = "Invalid product.";

    header("Location: ../shop.php");

    exit();

}

$product_id = (int)$_POST['product_id'];

$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if($quantity < 1){

    $quantity = 1;

}

/* ==========================================================
GET PRODUCT
========================================================== */

$productQuery = mysqli_query($conn,"

SELECT *

FROM products

WHERE id='$product_id'

AND status='Available'

LIMIT 1

");

if(mysqli_num_rows($productQuery)==0){

    $_SESSION['error']="Product not found.";

    header("Location: ../shop.php");

    exit();

}

$product = mysqli_fetch_assoc($productQuery);

/* ==========================================================
CHECK STOCK
========================================================== */

if($quantity > $product['stock']){

    $quantity = $product['stock'];

}

/* ==========================================================
CREATE CART
========================================================== */

if(!isset($_SESSION['cart'])){

    $_SESSION['cart'] = [];

}

/* ==========================================================
ADD OR UPDATE
========================================================== */

if(isset($_SESSION['cart'][$product_id])){

    $_SESSION['cart'][$product_id]['quantity'] += $quantity;

    if($_SESSION['cart'][$product_id]['quantity'] > $product['stock']){

        $_SESSION['cart'][$product_id]['quantity'] = $product['stock'];

    }

}else{

    $_SESSION['cart'][$product_id] = [

        "id" => $product['id'],

        "name" => $product['product_name'],

        "brand" => $product['brand'],

        "category_id" => $product['category_id'],

        "size" => $product['size'],

        "color" => $product['color'],

        "price" => $product['price'],

        "discount" => $product['discount'],

        "image" => $product['image'],

        "stock" => $product['stock'],

        "quantity" => $quantity

    ];

}

/* ==========================================================
SUCCESS
========================================================== */

$_SESSION['success'] = "Product added to your shopping cart successfully.";

header("Location: ../cart.php");

exit();

?>

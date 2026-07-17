<?php

include_once(__DIR__ . "/../config/config.php");
include_once(__DIR__ . "/../config/db.php");

/* ==========================================
STORE SETTINGS
========================================== */

$settings = mysqli_query($conn,"
SELECT *
FROM settings
LIMIT 1
");

$store = mysqli_fetch_assoc($settings);

/* ==========================================
CART COUNT
========================================== */

$cartCount = 0;

if(isset($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $item){

        $cartCount += $item['quantity'];

    }

}

?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>

<?php

echo isset($pageTitle)

? $pageTitle

: "T & T Fashion Store";

?>

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
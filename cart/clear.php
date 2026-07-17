<?php

session_start();

/* ==========================================================
CLEAR CART
========================================================== */

unset($_SESSION['cart']);

$_SESSION['success']="Your shopping cart has been cleared.";

header("Location: ../cart.php");

exit();

?>
<?php

session_start();

/* Destroy Customer Session */

unset($_SESSION['customer_id']);
unset($_SESSION['customer_name']);
unset($_SESSION['customer_email']);

/* Optional: Keep Cart
   If you want to clear the cart too, uncomment the line below.
*/
// unset($_SESSION['cart']);

$_SESSION['success']="You have logged out successfully.";

header("Location:login.php");

exit();

?>
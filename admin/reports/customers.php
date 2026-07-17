<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle="Customer Report";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

$customers=mysqli_query($conn,"
SELECT

customers.*,

COUNT(orders.id) total_orders,

IFNULL(SUM(orders.total),0) total_spent

FROM customers

LEFT JOIN orders

ON customers.id=orders.customer_id

GROUP BY customers.id

ORDER BY total_spent DESC
");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2>

<i class="bi bi-people-fill"></i>

Customer Report

</h2>

</div>

<div class="content-card">

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Customer</th>

<th>Email</th>

<th>Phone</th>

<th>Orders</th>

<th>Total Spent</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($customers)){

?>

<tr>

<td>

<?php echo $row['fullname'];?>

</td>

<td>

<?php echo $row['email'];?>

</td>

<td>

<?php echo $row['phone'];?>

</td>

<td>

<?php echo $row['total_orders'];?>

</td>

<td>

<strong>

RWF <?php echo number_format($row['total_spent']);?>

</strong>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
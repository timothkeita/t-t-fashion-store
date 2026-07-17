<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location: ../login.php");

    exit();

}

$pageTitle="My Orders";

include("../includes/header.php");
include("../includes/navbar.php");

$customer_id=$_SESSION['customer_id'];

/* ==========================================
GET ORDERS
========================================== */

$orderQuery=mysqli_query($conn,"

SELECT *

FROM orders

WHERE customer_id='$customer_id'

ORDER BY id DESC

");

?>
<div class="container py-5">

<div class="row">

<div class="col-lg-3">

<div class="card shadow border-0">

    <div class="card-header bg-dark text-white">

        <h5 class="mb-0">

            <i class="bi bi-person-circle"></i>

            My Account

        </h5>

    </div>

    <div class="list-group list-group-flush">

        <a
        href="index.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-speedometer2 me-2"></i>

            Dashboard

        </a>

        <a
        href="orders.php"
        class="list-group-item list-group-item-action active">

            <i class="bi bi-bag-check-fill me-2"></i>

            My Orders

        </a>

        <a
        href="profile.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-person-fill me-2"></i>

            My Profile

        </a>

        <a
        href="change_password.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-key-fill me-2"></i>

            Change Password

        </a>

        <a
        href="support.php"
        class="list-group-item list-group-item-action">

            <i class="bi bi-chat-dots-fill me-2"></i>

            Support Center

        </a>

        <a
        href="../logout.php"
        class="list-group-item list-group-item-action text-danger">

            <i class="bi bi-box-arrow-right me-2"></i>

            Logout

        </a>

    </div>

</div>

</div>

</div>

<div class="col-lg-9">

<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">

My Orders

</h4>

</div>

<div class="card-body">
<?php

if(mysqli_num_rows($orderQuery)>0){

?>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr>

<th>Order ID</th>

<th>Transaction ID</th>

<th>Total</th>

<th>Payment</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

while($order=mysqli_fetch_assoc($orderQuery)){

?>

<tr>

<td>

#<?php echo $order['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($order['transaction_id']); ?>

</td>

<td>

<strong>

RWF <?php echo number_format($order['total']); ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($order['payment_method']); ?>

</td>

<td>

<?php

$status=$order['order_status'];

if($status=="Pending"){

echo '<span class="badge bg-warning">Pending</span>';

}elseif($status=="Processing"){

echo '<span class="badge bg-primary">Processing</span>';

}elseif($status=="Delivered"){

echo '<span class="badge bg-success">Delivered</span>';

}else{

echo '<span class="badge bg-danger">'.$status.'</span>';

}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($order['created_at'])); ?>

</td>

<td>

<a

href="order_details.php?id=<?php echo $order['id']; ?>"

class="btn btn-sm btn-dark">

View

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<?php

}else{

?>
<div class="text-center py-5">

<i class="bi bi-bag-x display-1 text-muted"></i>

<h3 class="mt-4">

No Orders Yet

</h3>

<p class="text-muted">

You have not placed any orders.

</p>

<a

href="../shop.php"

class="btn btn-warning">

Start Shopping

</a>

</div>

<?php

}

?>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>
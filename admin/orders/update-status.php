<?php

include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ==========================================
CHECK ORDER ID
========================================== */

if(!isset($_GET['id'])){

    $_SESSION['message']="Invalid Order.";

    $_SESSION['type']="danger";

    header("Location:index.php");

    exit();

}

$id=(int)$_GET['id'];

/* ==========================================
GET ORDER
========================================== */

$orderQuery=mysqli_query($conn,"
SELECT

o.*,

c.fullname

FROM orders o

LEFT JOIN customers c

ON c.id=o.customer_id

WHERE o.id='$id'

LIMIT 1
");

if(mysqli_num_rows($orderQuery)==0){

    $_SESSION['message']="Order not found.";

    $_SESSION['type']="danger";

    header("Location:index.php");

    exit();

}

$order=mysqli_fetch_assoc($orderQuery);

$message="";

$type="";

/* ==========================================
UPDATE ORDER
========================================== */

if(isset($_POST['update'])){

$order_status=$_POST['order_status'];

$payment_status=$_POST['payment_status'];

$update=mysqli_query($conn,"
UPDATE orders
SET

order_status='$order_status',

payment_status='$payment_status'

WHERE id='$id'
");

if($update){

$_SESSION['message']="Order updated successfully.";

$_SESSION['type']="success";

header("Location:index.php");

exit();

}else{

$message=mysqli_error($conn);

$type="danger";

}

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>

Update Order

</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet"
href="../../assets/css/dashboard.css">

</head>

<body>

<?php include("../../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../../includes/topbar.php"); ?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3>

<i class="bi bi-pencil-square text-success"></i>

Update Order Status

</h3>

<p class="text-muted">

Modify payment and delivery status

</p>

</div>

<a

href="details.php?id=<?php echo $order['id']; ?>"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<div class="content-card">

<form method="POST">

<div class="row">

<div class="col-md-6 mb-4">

<label class="form-label">

Customer

</label>

<input

type="text"

class="form-control"

value="<?php echo htmlspecialchars($order['fullname']); ?>"

readonly>

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Order Total

</label>

<input

type="text"

class="form-control"

value="RWF <?php echo number_format($order['total']); ?>"

readonly>

</div>
</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Payment Status

</label>

<select

name="payment_status"

class="form-select"

required>

<option

value="Pending"

<?php if($order['payment_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option

value="Paid"

<?php if($order['payment_status']=="Paid") echo "selected"; ?>>

Paid

</option>

<option

value="Failed"

<?php if($order['payment_status']=="Failed") echo "selected"; ?>>

Failed

</option>

</select>

</div>

<div class="col-md-6 mb-4">

<label class="form-label">

Order Status

</label>

<select

name="order_status"

class="form-select"

required>

<option

value="Pending"

<?php if($order['order_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option

value="Processing"

<?php if($order['order_status']=="Processing") echo "selected"; ?>>

Processing

</option>

<option

value="Shipped"

<?php if($order['order_status']=="Shipped") echo "selected"; ?>>

Shipped

</option>

<option

value="Delivered"

<?php if($order['order_status']=="Delivered") echo "selected"; ?>>

Delivered

</option>

<option

value="Cancelled"

<?php if($order['order_status']=="Cancelled") echo "selected"; ?>>

Cancelled

</option>

</select>

</div>

</div>

<hr>

<div class="d-flex justify-content-between">

<a

href="details.php?id=<?php echo $order['id']; ?>"

class="btn btn-outline-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button

type="submit"

name="update"

class="btn btn-success">

<i class="bi bi-check-circle-fill"></i>

Update Order

</button>

</div>

</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
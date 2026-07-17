<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

/* ===========================
CHECK ADMIN LOGIN
=========================== */

if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit();
}

$pageTitle = "Customers";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* ===========================
SEARCH
=========================== */

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

/* ===========================
STATISTICS
=========================== */

$totalCustomers = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM customers
    ")
)['total'];

$activeCustomers = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM customers
        WHERE status='Active'
    ")
)['total'];

$inactiveCustomers = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) total
        FROM customers
        WHERE status='Inactive'
    ")
)['total'];

$customersWithOrders = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(DISTINCT customer_id) total
        FROM orders
    ")
)['total'];

/* ===========================
CUSTOMERS
=========================== */

$sql = "
SELECT
customers.*,

COUNT(orders.id) AS total_orders,

IFNULL(SUM(orders.total),0) AS total_spent

FROM customers

LEFT JOIN orders
ON customers.id = orders.customer_id
";

if($search != ""){

    $sql .= "
    WHERE

    customers.fullname LIKE '%$search%'

    OR customers.email LIKE '%$search%'

    OR customers.phone LIKE '%$search%'
    ";

}

$sql .= "

GROUP BY customers.id

ORDER BY customers.id DESC

";

$customers = mysqli_query($conn,$sql);

?>
<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-people-fill"></i>

Customers

</h2>

<p class="text-muted">

Manage all registered customers.

</p>

</div>
<div class="row mb-4">

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-primary">

<h6>Total Customers</h6>

<h2><?php echo $totalCustomers; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-success">

<h6>Active</h6>

<h2><?php echo $activeCustomers; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-danger">

<h6>Inactive</h6>

<h2><?php echo $inactiveCustomers; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-warning">

<h6>Customers With Orders</h6>

<h2><?php echo $customersWithOrders; ?></h2>

</div>

</div>

</div>
<div class="content-card mb-4">

<form method="GET">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search customer by name, email or phone..."
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

<i class="bi bi-search"></i>

Search

</button>

</div>

</div>

</form>

</div>
<div class="content-card">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4>

<i class="bi bi-people-fill"></i>

Registered Customers

</h4>

<span class="badge bg-primary">

<?php echo mysqli_num_rows($customers); ?>

Customers

</span>

</div>

<div class="table-responsive">

<table class="table align-middle table-hover">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Orders</th>

<th>Total Spent</th>

<th>Status</th>

<th>Joined</th>

<th class="text-center">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($customers)>0){

while($row=mysqli_fetch_assoc($customers)){

?>

<tr>

<td>

<strong>#<?php echo $row['id']; ?></strong>

</td>

<td>

<div class="d-flex align-items-center">

<?php

$photo = "../assets/images/default-user.png";

if(!empty($row['photo'])){

    $photo = "../../assets/images/customers/".$row['photo'];

}

?>

<img

src="<?php echo $photo; ?>"

style="width:60px;
height:60px;
border-radius:50%;
object-fit:cover;
margin-right:15px;">

<div>

<h6 class="mb-1">

<?php echo htmlspecialchars($row['fullname']); ?>

</h6>

<small class="text-muted d-block">

<i class="bi bi-envelope-fill"></i>

<?php echo htmlspecialchars($row['email']); ?>

</small>

<small class="text-muted">

<i class="bi bi-telephone-fill"></i>

<?php echo htmlspecialchars($row['phone']); ?>

</small>

</div>

</div>

</td>

<td>

<span class="badge bg-dark">

<?php echo $row['total_orders']; ?>

Orders

</span>

</td>

<td>

<strong class="text-success">

RWF <?php echo number_format($row['total_spent']); ?>

</strong>

</td>

<td>

<?php

if($row['status']=="Active"){

?>

<span class="badge bg-success">

Active

</span>

<?php

}else{

?>

<span class="badge bg-danger">

Inactive

</span>

<?php

}

?>

</td>

<td>

<?php

echo date("d M Y",strtotime($row['created_at']));

?>

</td>

<td class="text-center">

<a

href="view.php?id=<?php echo $row['id']; ?>"

class="btn btn-primary btn-sm">

<i class="bi bi-eye-fill"></i>

View

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7">

<div class="alert alert-warning text-center mb-0">

No customers found.

</div>

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
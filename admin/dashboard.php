<?php
include("../config/config.php");
include("../config/db.php");
include("../config/auth.php");

/* ======================================================
   DASHBOARD STATISTICS
====================================================== */

$totalProducts = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));

$totalCategories = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories"));

$totalCustomers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers"));

$totalOrders = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));

$pendingOrders = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM orders WHERE order_status='Pending'"));

$lowStock = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM products WHERE stock<=10"));

$revenueQuery = mysqli_query($conn,
"SELECT SUM(total) AS revenue
FROM orders
WHERE payment_status='Paid'");

$revenue = mysqli_fetch_assoc($revenueQuery);

$totalRevenue = $revenue['revenue'] ?? 0;

$todayRevenueQuery = mysqli_query($conn,
"SELECT SUM(total) AS revenue
FROM orders
WHERE DATE(created_at)=CURDATE()
AND payment_status='Paid'");

$todayRevenue = mysqli_fetch_assoc($todayRevenueQuery);

$today = $todayRevenue['revenue'] ?? 0;

/* ======================================================
RECENT ORDERS
====================================================== */

$recentOrders = mysqli_query($conn,
"SELECT *
FROM orders
ORDER BY id DESC
LIMIT 5");

/* ======================================================
LATEST PRODUCTS
====================================================== */

$latestProducts = mysqli_query($conn,
"SELECT p.*,c.category_name
FROM products p
LEFT JOIN categories c
ON c.id=p.category_id
ORDER BY p.id DESC
LIMIT 5");

/* ======================================================
LOW STOCK
====================================================== */

$lowStockProducts = mysqli_query($conn,
"SELECT *
FROM products
WHERE stock<=10
ORDER BY stock ASC
LIMIT 5");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Dashboard | T & T Fashion Store

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/dashboard.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main-content">

<?php include("includes/topbar.php"); ?>

<div class="container-fluid mt-4">
<!-- ===========================
Dashboard Statistics
=========================== -->

<div class="row g-4">

<div class="col-xl-3 col-lg-6">

<div class="dashboard-card blue">

<div>

<h5>Total Products</h5>

<h2><?php echo $totalProducts; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-bag"></i>

</div>

</div>

</div>

<div class="col-xl-3 col-lg-6">

<div class="dashboard-card green">

<div>

<h5>Categories</h5>

<h2><?php echo $totalCategories; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-grid"></i>

</div>

</div>

</div>

<div class="col-xl-3 col-lg-6">

<div class="dashboard-card orange">

<div>

<h5>Customers</h5>

<h2><?php echo $totalCustomers; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-people"></i>

</div>

</div>

</div>

<div class="col-xl-3 col-lg-6">

<div class="dashboard-card purple">

<div>

<h5>Orders</h5>

<h2><?php echo $totalOrders; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-cart-check"></i>

</div>

</div>

</div>

</div>

<!-- SECOND ROW -->

<div class="row mt-4 g-4">

<div class="col-xl-3">

<div class="dashboard-card yellow">

<div>

<h5>Today's Revenue</h5>

<h3>RWF <?php echo number_format($today); ?></h3>

</div>

<div class="card-icon">

<i class="bi bi-cash-stack"></i>

</div>

</div>

</div>

<div class="col-xl-3">

<div class="dashboard-card cyan">

<div>

<h5>Total Revenue</h5>

<h3>RWF <?php echo number_format($totalRevenue); ?></h3>

</div>

<div class="card-icon">

<i class="bi bi-wallet2"></i>

</div>

</div>

</div>

<div class="col-xl-3">

<div class="dashboard-card red">

<div>

<h5>Low Stock</h5>

<h2><?php echo $lowStock; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-exclamation-triangle"></i>

</div>

</div>

</div>

<div class="col-xl-3">

<div class="dashboard-card blue2">

<div>

<h5>Pending Orders</h5>

<h2><?php echo $pendingOrders; ?></h2>

</div>

<div class="card-icon">

<i class="bi bi-clock-history"></i>

</div>

</div>

</div>

</div>

<!-- =======================================================
CONTENT STARTS HERE
PART B CONTINUES FROM THIS POINT
======================================================= -->
<!-- =======================================================
ROW 1
Sales Overview + Latest Products
======================================================= -->

<div class="row mt-4">

    <!-- Sales Overview -->

    <div class="col-lg-8">

        <div class="content-card">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4>

                    <i class="bi bi-bar-chart-fill text-primary"></i>

                    Sales Overview

                </h4>

                <button class="btn btn-primary">

                    View Report

                </button>

            </div>

            <div class="chart-container">

                <canvas id="salesChart"></canvas>

            </div>

        </div>

    </div>

    <!-- Latest Products -->

    <div class="col-lg-4">

        <div class="content-card">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4>

                    <i class="bi bi-stars text-warning"></i>

                    Latest Products

                </h4>

            </div>

            <?php

            if(mysqli_num_rows($latestProducts)>0){

                while($product=mysqli_fetch_assoc($latestProducts)){

            ?>

            <div class="product-item">

                
            <img

             src="<?php echo SITE_URL; ?>/assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"

             alt="<?php echo htmlspecialchars($product['product_name']); ?>"

             style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
             
                <div>

                    <h6>

                        <?php echo $product['product_name']; ?>

                    </h6>

                    <span>

                        <?php echo $product['category_name']; ?>

                    </span>

                    <br>

                    <strong class="text-primary">

                        RWF <?php echo number_format($product['price']); ?>

                    </strong>

                </div>

            </div>

            <?php

                }

            }else{

                echo "<p>No Products Available.</p>";

            }

            ?>

        </div>

    </div>

</div>

<!-- =======================================================
ROW 2
Recent Orders
======================================================= -->

<div class="row">

<div class="col-lg-12">

<div class="content-card">

<div class="d-flex justify-content-between align-items-center mb-4">

<h4>

<i class="bi bi-cart-fill text-success"></i>

Recent Orders

</h4>

<button class="btn btn-success">

View All Orders

</button>

</div>

<div class="table-responsive">

<table class="table align-middle">

<thead>

<tr>

<th>#</th>

<th>Customer</th>

<th>Amount</th>

<th>Payment</th>

<th>Status</th>

<th>Date</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($recentOrders)>0){

while($order=mysqli_fetch_assoc($recentOrders)){

?>

<tr>

<td>

#<?php echo $order['id']; ?>

</td>

<td>

Customer <?php echo $order['customer_id']; ?>

</td>

<td>

<strong>

RWF

<?php echo number_format($order['total']); ?>

</strong>

</td>

<td>

<?php

if($order['payment_status']=="Paid"){

?>

<span class="badge-success">

Paid

</span>

<?php

}else{

?>

<span class="badge-warning">

Pending

</span>

<?php

}

?>

</td>

<td>

<?php

if($order['order_status']=="Delivered"){

?>

<span class="badge-success">

Delivered

</span>

<?php

}elseif($order['order_status']=="Pending"){

?>

<span class="badge-warning">

Pending

</span>

<?php

}else{

?>

<span class="badge-info">

Processing

</span>

<?php

}

?>

</td>

<td>

<?php

echo date("d M Y",strtotime($order['created_at']));

?>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6">

No Orders Found

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

</div>

<!-- =======================================================
PART C CONTINUES BELOW
======================================================= -->
<!-- =======================================================
ROW 3
Low Stock + Quick Actions
======================================================= -->

<div class="row mt-4">

    <!-- Low Stock -->

    <div class="col-lg-6">

        <div class="content-card">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h4>

                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>

                    Low Stock Products

                </h4>

            </div>

            <?php

            if(mysqli_num_rows($lowStockProducts)>0){

                while($stock=mysqli_fetch_assoc($lowStockProducts)){

            ?>

            <div class="low-stock">

                <img src="../uploads/<?php echo $stock['image']; ?>">

                <div class="low-stock-info">

                    <h6>

                        <?php echo $stock['product_name']; ?>

                    </h6>

                    <small>

                        <?php echo $stock['brand']; ?>

                    </small>

                </div>

                <div class="stock-number">

                    <?php echo $stock['stock']; ?> Left

                </div>

            </div>

            <?php

                }

            }else{

                echo "<p>No low stock products.</p>";

            }

            ?>

        </div>

    </div>

    <!-- Quick Actions -->

    <div class="col-lg-6">

        <div class="content-card">

            <h4 class="mb-4">

                <i class="bi bi-lightning-fill text-warning"></i>

                Quick Actions

            </h4>

            <div class="row g-3">

                <div class="col-md-6">

                    <a href="products/add.php" class="text-decoration-none">

                        <div class="quick-action">

                            <i class="bi bi-plus-circle-fill"></i>

                            <h5>Add Product</h5>

                        </div>

                    </a>

                </div>

                <div class="col-md-6">

                    <a href="categories/add.php" class="text-decoration-none">

                        <div class="quick-action">

                            <i class="bi bi-grid-fill"></i>

                            <h5>Add Category</h5>

                        </div>

                    </a>

                </div>

                <div class="col-md-6">

                    <a href="orders/index.php" class="text-decoration-none">

                        <div class="quick-action">

                            <i class="bi bi-cart-fill"></i>

                            <h5>Manage Orders</h5>

                        </div>

                    </a>

                </div>

                <div class="col-md-6">

                    <a href="customers/index.php" class="text-decoration-none">

                        <div class="quick-action">

                            <i class="bi bi-people-fill"></i>

                            <h5>Customers</h5>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- =======================================================
CHART.JS
======================================================= -->

<script>

const ctx = document.getElementById('salesChart');

new Chart(ctx, {

type: 'bar',

data: {

labels: [

'Jan',

'Feb',

'Mar',

'Apr',

'May',

'Jun',

'Jul'

],

datasets: [{

label: 'Sales',

data: [

120000,

190000,

150000,

220000,

180000,

260000,

320000

],

borderRadius:10,

backgroundColor:[

'#2563eb',

'#10b981',

'#f97316',

'#7c3aed',

'#ef4444',

'#06b6d4',

'#f59e0b'

]

}]

},

options:{

responsive:true,

plugins:{

legend:{

display:false

}

}

}

});

</script>

</div>

</div>

<script src="../assets/js/dashboard.js"></script>

</body>

</html>
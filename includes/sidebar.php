<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <!-- Logo -->
    <div class="sidebar-header">

        <img src="../assets/images/logo.png" alt="T & T Fashion Store">

        <h2>T & T</h2>

        <span>Fashion Store</span>

    </div>

    <!-- Navigation -->
    <ul class="sidebar-menu">

        <li class="<?= ($currentPage == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="../admin/dashboard.php">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="menu-title">
            Catalog
        </li>

        <li class="<?= ($currentPage == 'index.php' && strpos($_SERVER['REQUEST_URI'],'products')) ? 'active' : ''; ?>">
            <a href="../admin/products/index.php">
                <i class="bi bi-bag"></i>
                <span>Products</span>
            </a>
        </li>

        <li>
            <a href="../admin/categories/index.php">
                <i class="bi bi-grid"></i>
                <span>Categories</span>
            </a>
        </li>

        <li class="menu-title">
            Sales
        </li>

        <li>
            <a href="../admin/orders/index.php">
                <i class="bi bi-cart-check"></i>
                <span>Orders</span>
            </a>
        </li>

        <li>
            <a href="../admin/customers/index.php">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>
        </li>
        <li class="nav-item">

        <a href="../messages/index.php" class="nav-link">

        <i class="bi bi-chat-dots-fill"></i>

         Support Center

       </a>

      </li>
        <li class="menu-title">
            Marketing
        </li>

        <li>
            <a href="../admin/reviews/index.php">
                <i class="bi bi-star"></i>
                <span>Reviews</span>
            </a>
        </li>

        <li>
            <a href="../admin/messages/index.php">
                <i class="bi bi-chat-left-text"></i>
                <span>Messages</span>

                <small class="badge bg-danger">3</small>

            </a>
        </li>

        <li class="menu-title">
            Analytics
        </li>

        <li>
            <a href="../admin/reports/index.php">
                <i class="bi bi-bar-chart-line"></i>
                <span>Reports</span>
            </a>
        </li>

        <li class="menu-title">
            System
        </li>

        <li>
            <a href="../admin/settings/index.php">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        </li>

        <li>
            <a href="../admin/profile.php">
                <i class="bi bi-person-circle"></i>
                <span>Profile</span>
            </a>
        </li>

        <li>
            <a href="../admin/logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </li>

    </ul>

    <!-- Footer -->

    <div class="sidebar-footer">

        <img src="../assets/images/admin.jpeg" alt="Admin">

        <div>

            <strong>

                <?php

                echo $_SESSION['admin_name'];

                ?>

            </strong>

            <small>Administrator</small>

        </div>

    </div>

</div>
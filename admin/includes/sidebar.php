<?php

$currentPage = basename($_SERVER['PHP_SELF']);

/* =========================================
   LOAD WEBSITE SETTINGS
========================================= */

$settings = [];

$settingsQuery = mysqli_query($conn,"SELECT * FROM settings LIMIT 1");

if($settingsQuery && mysqli_num_rows($settingsQuery)>0){
    $settings = mysqli_fetch_assoc($settingsQuery);
}

/* =========================================
   STORE LOGO
========================================= */

$storeLogo = "logo.png";

if(!empty($settings['logo'])){
    $storeLogo = $settings['logo'];
}

/* =========================================
   ADMIN PHOTO
========================================= */

$adminPhoto = "default.png";

if(isset($_SESSION['admin_photo']) && !empty($_SESSION['admin_photo'])){
    $adminPhoto = $_SESSION['admin_photo'];
}

?>

<div class="sidebar">

    <!-- =======================================
    LOGO
    ======================================== -->

    <div class="sidebar-header text-center">

        <img

        src="<?php echo SITE_URL; ?>/assets/images/settings/<?php echo htmlspecialchars($storeLogo); ?>"

        alt="T & T Fashion Store"

        class="img-fluid mb-2"

        style="
        width:90px;
        height:90px;
        object-fit:contain;
        ">

        <h3 class="mb-0">

            <?php
            echo !empty($settings['store_name'])
            ? "T & T"
            : "T & T";
            ?>

        </h3>

        <small>

            Fashion Store

        </small>

    </div>

    <!-- =======================================
    MENU
    ======================================== -->

    <ul class="sidebar-menu">

        <!-- Dashboard -->

        <li class="<?php echo ($currentPage=="dashboard.php") ? "active" : ""; ?>">

            <a href="<?php echo SITE_URL; ?>/admin/dashboard.php">

                <i class="bi bi-speedometer2"></i>

                <span>Dashboard</span>

            </a>

        </li>

        <!-- Catalog -->

        <li class="menu-title">

            Catalog

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/products/index.php">

                <i class="bi bi-bag-fill"></i>

                <span>Products</span>

            </a>

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/categories/index.php">

                <i class="bi bi-grid-fill"></i>

                <span>Categories</span>

            </a>

        </li>

        <!-- Sales -->

        <li class="menu-title">

            Sales

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/orders/index.php">

                <i class="bi bi-cart-check-fill"></i>

                <span>Orders</span>

            </a>

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/customers/index.php">

                <i class="bi bi-people-fill"></i>

                <span>Customers</span>

            </a>

        </li>

        <!-- Customer Service -->

        <li class="menu-title">

            Customer Service

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/messages/index.php">

                <i class="bi bi-chat-dots-fill"></i>

                <span>Support Center</span>

            </a>

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/reviews/index.php">

                <i class="bi bi-star-fill"></i>

                <span>Reviews</span>

            </a>

        </li>

        <!-- Analytics -->

        <li class="menu-title">

            Analytics

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/reports/index.php">

                <i class="bi bi-bar-chart-fill"></i>

                <span>Reports</span>

            </a>

        </li>

        <!-- Administration -->

        <li class="menu-title">

            Administration

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/settings/index.php">

                <i class="bi bi-gear-fill"></i>

                <span>Settings</span>

            </a>

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/profile.php">

                <i class="bi bi-person-circle"></i>

                <span>Profile</span>

            </a>

        </li>

        <li>

            <a href="<?php echo SITE_URL; ?>/admin/logout.php">

                <i class="bi bi-box-arrow-right"></i>

                <span>Logout</span>

            </a>

        </li>

    </ul>

    <!-- =======================================
    ADMIN
    ======================================== -->

    <div class="sidebar-footer d-flex align-items-center">

        <img

        src="<?php echo SITE_URL; ?>/assets/images/admins/<?php echo htmlspecialchars($adminPhoto); ?>"

        class="rounded-circle border border-2 border-primary"

        style="
        width:55px;
        height:55px;
        object-fit:cover;
        "

        alt="Administrator">

        <div class="ms-2">

            <strong>

                <?php

                echo isset($_SESSION['admin_name'])

                ? htmlspecialchars($_SESSION['admin_name'])

                : "Administrator";

                ?>

            </strong>

            <br>

            <small>

                System Administrator

            </small>

        </div>

    </div>

</div>
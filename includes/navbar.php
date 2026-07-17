<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$cartCount = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cartCount += $item['quantity'];
    }
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

<div class="container">

    <!-- Logo -->

    <a class="navbar-brand fw-bold d-flex align-items-center" href="<?php echo SITE_URL; ?>/index.php">

        <img
        src="<?php echo SITE_URL; ?>/assets/images/logo.png"
        alt="T & T Fashion Store Logo"
        style="height:55px;"
        class="me-2">

        <span>T & T Fashion Store</span>

    </a>

    <!-- Mobile Toggle -->

    <button
    class="navbar-toggler"
    type="button"
    data-bs-toggle="collapse"
    data-bs-target="#navbarMenu">

        <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">

        <!-- Navigation -->

        <ul class="navbar-nav mx-auto">

            <li class="nav-item">

                <a class="nav-link" href="<?php echo SITE_URL; ?>/index.php">

                    <i class="bi bi-house-fill"></i>

                    Home

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link" href="<?php echo SITE_URL; ?>/shop.php">

                    <i class="bi bi-bag-fill"></i>

                    Shop

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link" href="<?php echo SITE_URL; ?>/categories.php">

                    <i class="bi bi-grid-fill"></i>

                    Categories

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link" href="<?php echo SITE_URL; ?>/about.php">

                    <i class="bi bi-info-circle-fill"></i>

                    About

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link" href="<?php echo SITE_URL; ?>/contact.php">

                    <i class="bi bi-envelope-fill"></i>

                    Contact

                </a>

            </li>

        </ul>

        <!-- Search -->

        <form
        class="d-flex me-3"
        action="<?php echo SITE_URL; ?>/shop.php"
        method="GET">

            <input
            type="search"
            name="search"
            class="form-control"
            placeholder="Search Products...">

        </form>

        <!-- Cart -->

        <a
        href="<?php echo SITE_URL; ?>/cart.php"
        class="btn btn-outline-dark position-relative me-2">

            <i class="bi bi-cart3"></i>

            Cart

            <?php if($cartCount > 0){ ?>

            <span
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                <?php echo $cartCount; ?>

            </span>

            <?php } ?>

        </a>

        <?php if(isset($_SESSION['customer_id'])){ ?>

        <!-- Customer Account -->

        <div class="dropdown">

            <button
            class="btn btn-dark dropdown-toggle"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false">

                <i class="bi bi-person-circle"></i>

                My Account

            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow">

                <li>

                    <a class="dropdown-item"
                    href="<?php echo SITE_URL; ?>/account/index.php">

                        <i class="bi bi-speedometer2 me-2"></i>

                        Dashboard

                    </a>

                </li>

                <li>

                    <a class="dropdown-item"
                    href="<?php echo SITE_URL; ?>/account/profile.php">

                        <i class="bi bi-person me-2"></i>

                        My Profile

                    </a>

                </li>

                <li>

                    <a class="dropdown-item"
                    href="<?php echo SITE_URL; ?>/account/orders.php">

                        <i class="bi bi-bag-check me-2"></i>

                        My Orders

                    </a>

                </li>

                <li>

                    <a class="dropdown-item"
                    href="<?php echo SITE_URL; ?>/account/change_password.php">

                        <i class="bi bi-key me-2"></i>

                        Change Password

                    </a>

                </li>

                <li>

                    <a class="dropdown-item"
                    href="<?php echo SITE_URL; ?>/account/support.php">

                        <i class="bi bi-chat-dots me-2"></i>

                        Support

                    </a>

                </li>

                <li><hr class="dropdown-divider"></li>

                <li>

                    <a class="dropdown-item text-danger"
                    href="<?php echo SITE_URL; ?>/logout.php">

                        <i class="bi bi-box-arrow-right me-2"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

        <?php }else{ ?>

        <!-- Guest -->

        <a
        href="<?php echo SITE_URL; ?>/login.php"
        class="btn btn-dark me-2">

            <i class="bi bi-box-arrow-in-right"></i>

            Login

        </a>

        <a
        href="<?php echo SITE_URL; ?>/register.php"
        class="btn btn-warning me-2">

            <i class="bi bi-person-plus-fill"></i>

            Register

        </a>

        <a
        href="<?php echo SITE_URL; ?>/admin/login.php"
        class="btn btn-outline-primary">

            <i class="bi bi-gear-fill"></i>

            Admin

        </a>

        <?php } ?>

    </div>

</div>

</nav>
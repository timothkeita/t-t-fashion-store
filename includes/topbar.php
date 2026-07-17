<?php

date_default_timezone_set("Africa/Kigali");

?>

<div class="topbar">

    <!-- Left Side -->

    <div class="topbar-left">

        <button class="menu-toggle" id="menu-toggle">

            <i class="bi bi-list"></i>

        </button>

        <div class="page-title">

            <h3>Dashboard</h3>

            <p>Welcome back, Administrator</p>

        </div>

    </div>

    <!-- Search -->

    <div class="search-box">

        <i class="bi bi-search"></i>

        <input

        type="text"

        placeholder="Search products, orders, customers...">

    </div>

    <!-- Right Side -->

    <div class="topbar-right">

        <!-- Notifications -->

        <div class="notification">

            <i class="bi bi-bell-fill"></i>

            <span class="notification-count">

                3

            </span>

        </div>

        <!-- Date -->

        <div class="today-date">

            <i class="bi bi-calendar-event"></i>

            <span>

                <?php

                echo date("l, d F Y");

                ?>

            </span>

        </div>

        <!-- Live Clock -->

        <div class="clock">

            <i class="bi bi-clock"></i>

            <span id="live-clock">

                00:00:00

            </span>

        </div>

        <!-- Admin -->

        <div class="admin-profile">

            <img

            src="../assets/images/admin.jpeg"

            alt="Administrator">

            <div>

                <h5>

                    <?php

                    echo $_SESSION['admin_name'];

                    ?>

                </h5>

                <small>

                    Administrator

                </small>

            </div>

            <i class="bi bi-chevron-down"></i>

        </div>

    </div>

</div>
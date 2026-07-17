<?php

date_default_timezone_set("Africa/Kigali");

/* Current Admin Photo */

$adminPhoto = "default.png";

if(isset($_SESSION['admin_photo']) && !empty($_SESSION['admin_photo'])){
    $adminPhoto = $_SESSION['admin_photo'];
}

?>

<div class="topbar">

    <!-- =========================
    LEFT
    ========================== -->

    <div class="topbar-left">

        <button class="menu-toggle" id="menu-toggle">

            <i class="bi bi-list"></i>

        </button>

        <div class="page-title">

            <h3>

                <?php
                echo isset($pageTitle)
                ? $pageTitle
                : "Dashboard";
                ?>

            </h3>

            <p>

                Welcome back,

                <strong>

                    <?php
                    echo isset($_SESSION['admin_name'])
                    ? htmlspecialchars($_SESSION['admin_name'])
                    : "Administrator";
                    ?>

                </strong>

            </p>

        </div>

    </div>

    <!-- =========================
    RIGHT
    ========================== -->

    <div class="topbar-right">

        <!-- Notifications -->

        <div class="notification me-4">

            <a
            href="<?php echo SITE_URL; ?>/admin/messages/index.php"
            class="text-dark">

                <i class="bi bi-bell-fill fs-5"></i>

            </a>

        </div>

        <!-- Date -->

        <div class="me-4">

            <i class="bi bi-calendar-event"></i>

            <?php echo date("d M Y"); ?>

        </div>

        <!-- Live Clock -->

        <div class="me-4">

            <i class="bi bi-clock-fill"></i>

            <span id="live-clock"></span>

        </div>

        <!-- Administrator -->

        <div class="admin-profile d-flex align-items-center">

            <img

            src="<?php echo SITE_URL; ?>/assets/images/admins/<?php echo htmlspecialchars($adminPhoto); ?>"

            alt="Administrator"

            style="
            width:45px;
            height:45px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid #0d6efd;
            margin-right:10px;
            ">

            <div>

                <strong>

                    <?php
                    echo isset($_SESSION['admin_name'])
                    ? htmlspecialchars($_SESSION['admin_name'])
                    : "Administrator";
                    ?>

                </strong>

                <br>

                <small class="text-muted">

                    Administrator

                </small>

            </div>

        </div>

    </div>

</div>

<script>

function updateClock(){

    let now = new Date();

    let time = now.toLocaleTimeString();

    document.getElementById("live-clock").innerHTML = time;

}

updateClock();

setInterval(updateClock,1000);

</script>
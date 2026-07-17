<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| NOTE:
| Do NOT include config.php or db.php here.
| Every page (dashboard.php, profile.php, settings/index.php, etc.)
| should include them BEFORE including header.php.
|--------------------------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>
<?php
echo isset($pageTitle)
    ? htmlspecialchars($pageTitle) . " | T & T Fashion Store"
    : "Admin Panel | T & T Fashion Store";
?>
</title>

<!-- Bootstrap -->
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<!-- Bootstrap Icons -->
<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">

<!-- Google Font -->
<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<!-- Dashboard CSS -->
<link
rel="stylesheet"
href="<?php echo SITE_URL; ?>/assets/css/dashboard.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background:#f5f6fa;
    overflow-x:hidden;
}

a{
    text-decoration:none;
}

.content-card{
    background:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    margin-bottom:25px;
}

.dashboard-card{
    color:#fff;
    padding:25px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
}

.dashboard-card h6{
    font-size:15px;
    opacity:.9;
}

.dashboard-card h2{
    margin-top:10px;
    font-weight:700;
}

.table img{
    object-fit:cover;
}

.page-header{
    margin-bottom:30px;
}

.page-header h2{
    font-weight:700;
}

.badge{
    font-size:13px;
}

.btn{
    border-radius:8px;
}

.form-control,
.form-select{
    border-radius:8px;
}

</style>

</head>

<body>

<div class="main-content">
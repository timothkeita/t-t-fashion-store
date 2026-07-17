<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if (!isset($_SESSION['admin'])) {
    header("Location:../login.php");
    exit();
}

$pageTitle = "Website Settings";

$message = "";
$error = "";

/* Load Settings */

$result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");

if(mysqli_num_rows($result)==0){

    mysqli_query($conn,"
    INSERT INTO settings(store_name)
    VALUES('T & T Fashion Store')
    ");

    $result = mysqli_query($conn,"SELECT * FROM settings LIMIT 1");
}

$settings = mysqli_fetch_assoc($result);
if(isset($_POST['save'])){

    $store_name=mysqli_real_escape_string($conn,$_POST['store_name']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $phone=mysqli_real_escape_string($conn,$_POST['phone']);
    $address=mysqli_real_escape_string($conn,$_POST['address']);

    $website=mysqli_real_escape_string($conn,$_POST['website']);

    $currency=mysqli_real_escape_string($conn,$_POST['currency']);

    $tax=$_POST['tax'];

    $shipping_fee=$_POST['shipping_fee'];

    $footer_text=mysqli_real_escape_string($conn,$_POST['footer_text']);

    mysqli_query($conn,"
        UPDATE settings SET

        store_name='$store_name',
        email='$email',
        phone='$phone',
        address='$address',
        website='$website',
        currency='$currency',
        tax='$tax',
        shipping_fee='$shipping_fee',
        footer_text='$footer_text'

        WHERE id='{$settings['id']}'
    ");

    $message="Settings updated successfully.";

    $result=mysqli_query($conn,"SELECT * FROM settings LIMIT 1");

    $settings=mysqli_fetch_assoc($result);
}
include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-gear-fill"></i>

Website Settings

</h2>

<p class="text-muted">

Manage your store information and business settings.

</p>

</div>
<?php if($message!=""){ ?>

<div class="alert alert-success alert-dismissible fade show">

<i class="bi bi-check-circle"></i>

<?php echo $message; ?>

<button class="btn-close" data-bs-dismiss="alert"></button>

</div>

<?php } ?>
<form method="POST" enctype="multipart/form-data">

<div class="card shadow-sm mb-4">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="bi bi-shop"></i>

Store Information

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">Store Name</label>

<input
type="text"
name="store_name"
class="form-control"
value="<?php echo htmlspecialchars($settings['store_name']); ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Email Address</label>

<input
type="email"
name="email"
class="form-control"
value="<?php echo htmlspecialchars($settings['email']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Phone Number</label>

<input
type="text"
name="phone"
class="form-control"
value="<?php echo htmlspecialchars($settings['phone']); ?>">

</div>

<div class="col-md-6 mb-3">

<label class="form-label">Website</label>

<input
type="text"
name="website"
class="form-control"
placeholder="https://www.ttfashionstore.com"
value="<?php echo htmlspecialchars($settings['website']); ?>">

</div>

<div class="col-12">

<label class="form-label">Store Address</label>

<textarea
name="address"
rows="3"
class="form-control"><?php echo htmlspecialchars($settings['address']); ?></textarea>

</div>

</div>

</div>

</div>
<div class="card shadow-sm mb-4">

<div class="card-header bg-success text-white">

<h5 class="mb-0">

<i class="bi bi-cash-stack"></i>

Business Settings

</h5>

</div>

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label class="form-label">

Currency

</label>

<input
type="text"
name="currency"
class="form-control"
value="<?php echo htmlspecialchars($settings['currency']); ?>">

</div>

<div class="col-md-4">

<label class="form-label">

Tax (%)

</label>

<input
type="number"
step="0.01"
name="tax"
class="form-control"
value="<?php echo $settings['tax']; ?>">

</div>

<div class="col-md-4">

<label class="form-label">

Shipping Fee

</label>

<input
type="number"
step="0.01"
name="shipping_fee"
class="form-control"
value="<?php echo $settings['shipping_fee']; ?>">

</div>

</div>

</div>

</div>
<div class="card shadow-sm mb-4">

<div class="card-header bg-warning">

<h5 class="mb-0">

<i class="bi bi-image-fill"></i>

Store Logo

</h5>

</div>

<div class="card-body">

<div class="row align-items-center">

<div class="col-md-3 text-center">

<?php

if(!empty($settings['logo']) && file_exists("../../assets/images/settings/".$settings['logo'])){

?>

<img
src="../../assets/images/settings/<?php echo htmlspecialchars($settings['logo']); ?>"
class="img-fluid rounded border p-2"
style="max-height:140px; object-fit:contain;">

<?php

}else{

?>

<img
src="../../assets/images/no-image.png"
class="img-fluid rounded border p-2"
style="max-height:140px; object-fit:contain;">

<?php

}

?>

</div>

<div class="col-md-9">

<label class="form-label">

Upload New Logo

</label>

<input
type="file"
name="logo"
class="form-control">

<small class="text-muted">

Recommended size:
400 × 400 PNG

</small>

</div>

</div>

</div>

</div>
<div class="card shadow-sm mb-4">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-layout-text-window"></i>

Footer Settings

</h5>

</div>

<div class="card-body">

<label class="form-label">

Footer Text

</label>

<textarea

name="footer_text"

rows="4"

class="form-control"><?php echo htmlspecialchars($settings['footer_text']); ?></textarea>

</div>

</div>
<div class="text-end mb-5">

<button
type="submit"
name="save"
class="btn btn-success btn-lg px-5">

<i class="bi bi-check-circle-fill"></i>

Save Settings

</button>

</div>

</form>
</div>

<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>
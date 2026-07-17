<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

$pageTitle = "Support Center";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");

/* ==========================================
SEARCH
========================================== */

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
}

/* ==========================================
STATISTICS
========================================== */

$totalMessages = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM contact_messages
"))['total'];

$newMessages = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM contact_messages
WHERE status='New'
"))['total'];

$readMessages = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM contact_messages
WHERE status='Read'
"))['total'];

$repliedMessages = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM contact_messages
WHERE status='Replied'
"))['total'];

/* ==========================================
MESSAGES
========================================== */

$sql = "SELECT * FROM contact_messages";

if($search!=""){

$sql .= "

 WHERE

 name LIKE '%$search%'

 OR email LIKE '%$search%'

 OR subject LIKE '%$search%'

";

}

$sql .= "

 ORDER BY id DESC

";

$messages = mysqli_query($conn,$sql);

?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-chat-dots-fill"></i>

Support Center

</h2>

<p class="text-muted">

Manage customer messages.

</p>

</div>

<div class="row mb-4">

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-primary">

<h6>Total Messages</h6>

<h2><?php echo $totalMessages; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-warning">

<h6>New</h6>

<h2><?php echo $newMessages; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-info">

<h6>Read</h6>

<h2><?php echo $readMessages; ?></h2>

</div>

</div>

<div class="col-lg-3 mb-3">

<div class="dashboard-card bg-success">

<h6>Replied</h6>

<h2><?php echo $repliedMessages; ?></h2>

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
placeholder="Search message..."
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

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Subject</th>

<th>Status</th>

<th>Date</th>

<th class="text-center">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($messages)>0){

while($row=mysqli_fetch_assoc($messages)){

?>

<tr>

<td>#<?php echo $row['id']; ?></td>

<td>

<strong><?php echo htmlspecialchars($row['name']); ?></strong>

<br>

<small class="text-muted">

<?php echo htmlspecialchars($row['email']); ?>

</small>

</td>

<td>

<?php echo htmlspecialchars($row['subject']); ?>

</td>

<td>

<?php

if($row['status']=="New"){

echo '<span class="badge bg-warning">New</span>';

}elseif($row['status']=="Read"){

echo '<span class="badge bg-info">Read</span>';

}else{

echo '<span class="badge bg-success">Replied</span>';

}

?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['created_at'])); ?>

</td>

<td class="text-center">

<a href="conversation.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">

<i class="bi bi-eye-fill"></i>

View

</a>

<a href="delete.php?id=<?php echo $row['id']; ?>"

class="btn btn-danger btn-sm"

onclick="return confirm('Delete this message?')">

<i class="bi bi-trash-fill"></i>

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="6">

<div class="alert alert-warning mb-0 text-center">

No messages found.

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

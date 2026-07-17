<?php
session_start();

include("../../config/config.php");
include("../../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location:index.php");
    exit();
}

$id=(int)$_GET['id'];

/* Get Message */

$message=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM contact_messages
WHERE id='$id'
"));

if(!$message){

    header("Location:index.php");
    exit();

}

/* Mark As Read */

if($message['status']=="New"){

mysqli_query($conn,"
UPDATE contact_messages
SET status='Read'
WHERE id='$id'
");

$message['status']="Read";

}

/* Reply */

if(isset($_POST['reply'])){

$reply=mysqli_real_escape_string($conn,$_POST['reply']);

$admin=$_SESSION['admin_name'];

mysqli_query($conn,"
UPDATE contact_messages
SET

admin_reply='$reply',

replied_by='$admin',

replied_at=NOW(),

status='Replied'

WHERE id='$id'
");

header("Location:conversation.php?id=".$id);

exit();

}

$pageTitle="Conversation";

include("../includes/header.php");
include("../includes/sidebar.php");
include("../includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2>

<i class="bi bi-chat-dots-fill"></i>

Conversation

</h2>

</div>

<div class="content-card">

<h5>

<?php echo htmlspecialchars($message['subject']); ?>

</h5>

<hr>

<p>

<strong>Customer:</strong>

<?php echo htmlspecialchars($message['name']); ?>

</p>

<p>

<strong>Email:</strong>

<?php echo htmlspecialchars($message['email']); ?>

</p>

<p>

<strong>Status:</strong>

<?php echo $message['status']; ?>

</p>

<p>

<strong>Date:</strong>

<?php echo date("d M Y H:i",strtotime($message['created_at'])); ?>

</p>

<hr>

<h5>

Customer Message

</h5>

<div class="alert alert-light">

<?php echo nl2br(htmlspecialchars($message['message'])); ?>

</div>

<hr>

<h5>

Admin Reply

</h5>

<form method="POST">

<textarea

name="reply"

rows="6"

class="form-control"

required><?php echo htmlspecialchars($message['admin_reply']); ?></textarea>

<br>

<button

name="reply"

class="btn btn-success">

<i class="bi bi-send-fill"></i>

Save Reply

</button>

<a

href="index.php"

class="btn btn-secondary">

Back

</a>

</form>

<?php

if($message['status']=="Replied"){

?>

<hr>

<p>

<strong>Replied By:</strong>

<?php echo $message['replied_by']; ?>

</p>

<p>

<strong>Reply Date:</strong>

<?php echo date("d M Y H:i",strtotime($message['replied_at'])); ?>

</p>

<?php

}

?>

</div>

</div>

<?php

include("../includes/footer.php");

include("../includes/scripts.php");

?>
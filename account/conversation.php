<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location:../login.php");
    exit();

}

$customer_id=$_SESSION['customer_id'];

if(!isset($_GET['id'])){

    header("Location:support.php");
    exit();

}

$ticket_id=(int)$_GET['id'];

/* ===========================
GET TICKET
=========================== */

$ticketQuery=mysqli_query($conn,"
SELECT *

FROM support_tickets

WHERE id='$ticket_id'

AND customer_id='$customer_id'

LIMIT 1
");

if(mysqli_num_rows($ticketQuery)==0){

die("Ticket not found.");

}

$ticket=mysqli_fetch_assoc($ticketQuery);

/* ===========================
SEND MESSAGE
=========================== */

if(isset($_POST['send'])){

$message=mysqli_real_escape_string(
$conn,
trim($_POST['message'])
);

if($message!=""){

mysqli_query($conn,"
INSERT INTO support_messages(

ticket_id,

sender,

sender_id,

message

)

VALUES(

'$ticket_id',

'Customer',

'$customer_id',

'$message'

)
");

mysqli_query($conn,"
UPDATE support_tickets

SET

status='Open'

WHERE id='$ticket_id'
");

header("Location:conversation.php?id=".$ticket_id);

exit();

}

}

/* ===========================
LOAD CHAT
=========================== */

$messages=mysqli_query($conn,"
SELECT *

FROM support_messages

WHERE ticket_id='$ticket_id'

ORDER BY created_at ASC
");

$pageTitle="Support Conversation";

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-lg-9">
<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4>

<?php echo htmlspecialchars($ticket['subject']); ?>

</h4>

<small>

Ticket:

<?php echo htmlspecialchars($ticket['ticket_number']); ?>

</small>

</div>

<div class="card-body">

<div

style="height:500px;
overflow-y:auto;
background:#f8f9fa;
padding:20px;
border-radius:10px;">

<?php

while($msg=mysqli_fetch_assoc($messages)){

$isCustomer=($msg['sender']=="Customer");

?>

<div class="mb-3 d-flex <?php echo $isCustomer ? "justify-content-end" : "justify-content-start"; ?>">

<div

class="<?php echo $isCustomer ? "bg-primary text-white" : "bg-white"; ?>"

style="

max-width:75%;
padding:15px;
border-radius:12px;
box-shadow:0 2px 8px rgba(0,0,0,.1);

">

<strong>

<?php echo htmlspecialchars($msg['sender']); ?>

</strong>

<hr>

<?php echo nl2br(htmlspecialchars($msg['message'])); ?>

<hr>

<small>

<?php echo date("d M Y H:i",strtotime($msg['created_at'])); ?>

</small>

</div>

</div>

<?php

}

?>

</div>
<?php if($ticket['status']=="Closed"){ ?>

<div class="alert alert-warning mt-4">

<i class="bi bi-lock-fill"></i>

This support ticket has been closed.

If you still need help, please create a new support ticket.

</div>

<?php }else{ ?>

<form method="POST" class="mt-4">

<div class="mb-3">

<textarea

name="message"

rows="4"

class="form-control"

placeholder="Type your message..."

required></textarea>

</div>

<div class="d-flex justify-content-between">

<a

href="support.php"

class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

<button

type="submit"

name="send"

class="btn btn-primary">

<i class="bi bi-send-fill"></i>

Send Message

</button>

</div>

</form>

<?php } ?>

</div>

</div>

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
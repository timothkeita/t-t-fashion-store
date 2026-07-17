<?php

session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['customer_id'])){

    header("Location:../login.php");
    exit();

}

$customer_id=$_SESSION['customer_id'];

$message="";
$type="";

/* ==========================================
CREATE NEW TICKET
========================================== */

if(isset($_POST['create_ticket'])){

$subject=mysqli_real_escape_string($conn,trim($_POST['subject']));
$ticket_message=mysqli_real_escape_string($conn,trim($_POST['message']));

if($subject!="" && $ticket_message!=""){

$ticket_number="SUP".date("Ymd").str_pad(mt_rand(1,9999),4,"0",STR_PAD_LEFT);

mysqli_begin_transaction($conn);

try{

mysqli_query($conn,"
INSERT INTO support_tickets(

ticket_number,

customer_id,

subject

)

VALUES(

'$ticket_number',

'$customer_id',

'$subject'

)
");

$ticket_id=mysqli_insert_id($conn);

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

'$ticket_message'

)
");

mysqli_commit($conn);

$message="Support ticket created successfully.";

$type="success";

}catch(Exception $e){

mysqli_rollback($conn);

$message="Unable to create ticket.";

$type="danger";

}

}

}

$tickets=mysqli_query($conn,"
SELECT *

FROM support_tickets

WHERE customer_id='$customer_id'

ORDER BY updated_at DESC
");

$pageTitle="Support Center";

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

<div class="row">

<div class="col-lg-5">
<div class="card shadow border-0">

<div class="card-header bg-dark text-white">

<h4>

<i class="bi bi-chat-dots-fill"></i>

Open Support Ticket

</h4>

</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>

Subject

</label>

<input

type="text"

name="subject"

class="form-control"

required>

</div>

<div class="mb-3">

<label>

Message

</label>

<textarea

name="message"

rows="6"

class="form-control"

required></textarea>

</div>

<button

type="submit"

name="create_ticket"

class="btn btn-dark w-100">

<i class="bi bi-send-fill"></i>

Create Ticket

</button>

</form>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="card shadow border-0">

<div class="card-header bg-primary text-white">

<h4>

My Support Tickets

</h4>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead>

<tr>

<th>Ticket</th>

<th>Subject</th>

<th>Status</th>

<th>Updated</th>

<th></th>

</tr>

</thead>

<tbody>
<?php

if(mysqli_num_rows($tickets)>0){

while($ticket=mysqli_fetch_assoc($tickets)){

?>

<tr>

<td>

<strong>

<?php echo htmlspecialchars($ticket['ticket_number']); ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($ticket['subject']); ?>

</td>

<td>

<?php

$statusColor="secondary";

if($ticket['status']=="Open"){

$statusColor="warning";

}elseif($ticket['status']=="Answered"){

$statusColor="success";

}elseif($ticket['status']=="Closed"){

$statusColor="dark";

}

?>

<span class="badge bg-<?php echo $statusColor; ?>">

<?php echo htmlspecialchars($ticket['status']); ?>

</span>

</td>

<td>

<?php echo date("d M Y H:i",strtotime($ticket['updated_at'])); ?>

</td>

<td>

<a

href="conversation.php?id=<?php echo $ticket['id']; ?>"

class="btn btn-sm btn-primary">

Open

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="5" class="text-center">

No support tickets found.

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

</div>

</div>

</div>

<?php

include("../includes/footer.php");
include("../includes/scripts.php");

?>
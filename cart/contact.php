<?php

session_start();

include("config/config.php");
include("config/db.php");

$pageTitle="Contact Us";

$message="";
$type="";

if(isset($_POST['send'])){

$name=mysqli_real_escape_string($conn,trim($_POST['name']));
$email=mysqli_real_escape_string($conn,trim($_POST['email']));
$subject=mysqli_real_escape_string($conn,trim($_POST['subject']));
$messageText=mysqli_real_escape_string($conn,trim($_POST['message']));

$insert=mysqli_query($conn,"
INSERT INTO contact_messages(

name,
email,
subject,
message

)

VALUES(

'$name',
'$email',
'$subject',
'$messageText'

)
");

if($insert){

$message="Thank you! Your message has been sent successfully.";

$type="success";

}else{

$message="Unable to send your message.";

$type="danger";

}

}

include("includes/header.php");
include("includes/navbar.php");

?>

<section class="bg-dark text-white py-5">

<div class="container text-center">

<h1 class="display-4 fw-bold">

Contact Us

</h1>

<p class="lead">

We're always here to help.

</p>

</div>

</section>

<div class="container py-5">

<div class="row">

<div class="col-lg-5">
<div class="card shadow border-0 mb-4">

<div class="card-body">

<h3 class="mb-4">

Get In Touch

</h3>

<p>

<i class="bi bi-geo-alt-fill text-danger"></i>

<strong>Address</strong>

<br>

Kigali, Rwanda

</p>

<hr>

<p>

<i class="bi bi-envelope-fill text-primary"></i>

<strong>Email</strong>

<br>

support@ttfashionstore.com

</p>

<hr>

<p>

<i class="bi bi-telephone-fill text-success"></i>

<strong>Phone</strong>

<br>

+250 788 000 000

</p>

<hr>

<p>

<i class="bi bi-clock-fill text-warning"></i>

<strong>Business Hours</strong>

<br>

Monday - Saturday

<br>

8:00 AM - 7:00 PM

</p>

<hr>

<h5>

Follow Us

</h5>

<a href="#" class="fs-3 me-3">

<i class="bi bi-facebook"></i>

</a>

<a href="#" class="fs-3 me-3">

<i class="bi bi-instagram"></i>

</a>

<a href="#" class="fs-3 me-3">

<i class="bi bi-tiktok"></i>

</a>

<a href="#" class="fs-3">

<i class="bi bi-twitter-x"></i>

</a>

</div>

</div>

<!-- Google Map -->

<div class="card shadow border-0">

<div class="card-body p-0">

<iframe

src="https://www.google.com/maps?q=Kigali,Rwanda&output=embed"

width="100%"

height="300"

style="border:0;"

loading="lazy">

</iframe>

</div>

</div>

</div>

<div class="col-lg-7">
<div class="card shadow border-0">

<div class="card-body">

<h3 class="mb-4">

Send Us A Message

</h3>

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label>

Full Name

</label>

<input

type="text"

name="name"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label>

Email Address

</label>

<input

type="email"

name="email"

class="form-control"

required>

</div>

<div class="col-12 mb-3">

<label>

Subject

</label>

<input

type="text"

name="subject"

class="form-control"

required>

</div>

<div class="col-12 mb-3">

<label>

Message

</label>

<textarea

name="message"

rows="6"

class="form-control"

required></textarea>

</div>

<div class="col-12">

<button

type="submit"

name="send"

class="btn btn-dark btn-lg">

<i class="bi bi-send-fill"></i>

Send Message

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>
<?php

include("includes/footer.php");

include("includes/scripts.php");

?>
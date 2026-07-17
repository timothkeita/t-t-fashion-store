<?php
session_start();

include("../config/config.php");
include("../config/db.php");

if(!isset($_SESSION['admin'])){
    header("Location:login.php");
    exit();
}

$pageTitle="Edit Profile";

$admin_id = $_SESSION['admin'];

$query = mysqli_query($conn,"
SELECT *
FROM admins
WHERE id='$admin_id'
LIMIT 1
");

$admin = mysqli_fetch_assoc($query);

if(!$admin){
    die("Administrator account not found.");
}

$message="";
$error="";

if(isset($_POST['save'])){

    $fullname=mysqli_real_escape_string($conn,$_POST['fullname']);
    $newUsername=mysqli_real_escape_string($conn,$_POST['username']);
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $phone=mysqli_real_escape_string($conn,$_POST['phone']);

    $photo=$admin['photo'];

    /* Upload Photo */

    if(isset($_FILES['photo']) && $_FILES['photo']['error']==0){

        $allowed=['jpg','jpeg','png','webp'];

        $extension=strtolower(pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION));

        if(in_array($extension,$allowed)){

            $newPhoto="admin_".time().".".$extension;

            $destination="../assets/images/admins/".$newPhoto;

            if(move_uploaded_file($_FILES['photo']['tmp_name'],$destination)){

                if(
                    !empty($admin['photo']) &&
                    $admin['photo']!="default.png" &&
                    file_exists("../assets/images/admins/".$admin['photo'])
                ){
                    unlink("../assets/images/admins/".$admin['photo']);
                }

                $photo=$newPhoto;
            }

        }else{

            $error="Only JPG, JPEG, PNG and WEBP images are allowed.";

        }

    }

    if($error==""){

        mysqli_query($conn,"
        UPDATE admins SET

        fullname='$fullname',
        username='$newUsername',
        email='$email',
        phone='$phone',
        photo='$photo'

        WHERE id='{$admin['id']}'
        ");

        $_SESSION['admin']=$admin['id'];
        $_SESSION['admin_name']=$fullname;
        $_SESSION['admin_photo']=$photo;

        $message="Profile updated successfully.";

        $query=mysqli_query($conn,"
        SELECT *
        FROM admins
        WHERE id='{$admin['id']}'
        ");

        $admin=mysqli_fetch_assoc($query);

    }

}

include("includes/header.php");
include("includes/sidebar.php");
include("includes/topbar.php");
?>

<div class="container-fluid">

<div class="page-header mb-4">

<h2 class="fw-bold">

<i class="bi bi-person-gear"></i>

Edit Profile

</h2>

</div>

<?php if($message!=""){ ?>

<div class="alert alert-success">

<?php echo $message; ?>

</div>

<?php } ?>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="card shadow-sm">

<div class="card-body">

<div class="row">

<div class="col-md-4 text-center">

<?php

$image="default.png";

if(!empty($admin['photo'])){

$image=$admin['photo'];

}

?>

<img

src="../assets/images/admins/<?php echo htmlspecialchars($image);?>"

class="rounded-circle shadow"

style="width:180px;height:180px;object-fit:cover;">

<div class="mt-3">

<input
type="file"
name="photo"
class="form-control">

</div>

</div>

<div class="col-md-8">

<div class="mb-3">

<label>Full Name</label>

<input

type="text"

name="fullname"

class="form-control"

value="<?php echo htmlspecialchars($admin['fullname']);?>"

required>

</div>

<div class="mb-3">

<label>Username</label>

<input

type="text"

name="username"

class="form-control"

value="<?php echo htmlspecialchars($admin['username']);?>"

required>

</div>

<div class="mb-3">

<label>Email</label>

<input

type="email"

name="email"

class="form-control"

value="<?php echo htmlspecialchars($admin['email']);?>"

required>

</div>

<div class="mb-3">

<label>Phone</label>

<input

type="text"

name="phone"

class="form-control"

value="<?php echo htmlspecialchars($admin['phone']);?>">

</div>

<button

class="btn btn-success"

name="save">

<i class="bi bi-check-circle-fill"></i>

Save Changes

</button>

<a

href="profile.php"

class="btn btn-secondary">

Cancel

</a>

</div>

</div>

</div>

</div>

</form>

</div>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>
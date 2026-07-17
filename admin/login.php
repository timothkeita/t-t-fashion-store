<?php
include("../config/db.php");
include("../config/config.php");

// If already logged in
if(isset($_SESSION['admin'])){
    header("Location: dashboard.php");
    exit();
}

$error = "";

if(isset($_POST['login'])){

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)){

        $error = "Please fill in all fields.";

    }else{

        $query = mysqli_query($conn,"SELECT * FROM admins WHERE username='$username' LIMIT 1");

        if(mysqli_num_rows($query) == 1){

            $admin = mysqli_fetch_assoc($query);

            if(password_verify($password,$admin['password'])){

                $_SESSION['admin'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['fullname'];
                $_SESSION['admin_photo'] = $admin['photo'];

                header("Location: dashboard.php");
                exit();

            }else{

                $error = "Invalid password.";

            }

        }else{

            $error = "Username not found.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Admin Login | T & T Fashion Store</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body>

<div class="login-container">

<div class="login-card">

<div class="text-center mb-4">

<img src="../assets/images/logo.png" width="90">

<h2>T & T Fashion Store</h2>

<p>Administrator Login</p>

</div>

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?php echo $error; ?>

</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
placeholder="Enter Username">

</div>

<div class="mb-3">

<label>Password</label>

<div class="input-group">

<input
type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter Password">

<button
class="btn btn-outline-secondary"
type="button"
onclick="togglePassword()">

<i class="bi bi-eye"></i>

</button>

</div>

</div>

<div class="form-check mb-3">

<input
class="form-check-input"
type="checkbox">

<label class="form-check-label">

Remember Me

</label>

</div>

<button
name="login"
class="btn btn-dark w-100">

<i class="bi bi-box-arrow-in-right"></i>

Login

</button>

</form>

</div>

</div>

<script>

function togglePassword(){

var x=document.getElementById("password");

if(x.type==="password"){

x.type="text";

}else{

x.type="password";

}

}

</script>

</body>

</html>
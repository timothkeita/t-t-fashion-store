<?php
include("config/db.php");

// Admin details
$fullname = "System Administrator";
$username = "admin";
$email = "admin@ttfashion.com";
$password = "admin123";

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM admins WHERE username='$username'");

if(mysqli_num_rows($check) > 0){

    echo "Admin account already exists.";

}else{

    $sql = "INSERT INTO admins(fullname, username, email, password)
            VALUES('$fullname','$username','$email','$hashedPassword')";

    if(mysqli_query($conn,$sql)){
        echo "Admin created successfully.";
    }else{
        echo "Error: ".mysqli_error($conn);
    }

}
?>
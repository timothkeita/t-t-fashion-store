<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

$message = "";
$message_type = "";

if(isset($_POST['save'])){

    $category_name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
    $description   = mysqli_real_escape_string($conn, trim($_POST['description']));
    $status        = $_POST['status'];

    if(empty($category_name)){

        $message = "Category name is required.";
        $message_type = "danger";

    }else{

        // Check duplicate category
        $check = mysqli_query($conn,"SELECT id FROM categories WHERE category_name='$category_name'");

        if(mysqli_num_rows($check) > 0){

            $message = "Category already exists.";
            $message_type = "warning";

        }else{

            // Default image
            $image = "default-category.png";

            // Upload image if selected
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

                $allowed = ['jpg','jpeg','png','webp'];

                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if(in_array($ext,$allowed)){

                    $image = "category_".time().".".$ext;

                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        "../../assets/images/categories/".$image
                    );

                }

            }

            $insert = mysqli_query($conn,"
                INSERT INTO categories
                (
                    category_name,
                    description,
                    image,
                    status
                )
                VALUES
                (
                    '$category_name',
                    '$description',
                    '$image',
                    '$status'
                )
            ");

            if($insert){

                $_SESSION['message'] = "Category added successfully.";
                $_SESSION['type'] = "success";
                header("Location:index.php");
                exit();

            }else{

                $message = "Database error: ".mysqli_error($conn);
                $message_type = "danger";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Add Category</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>

<?php include("../../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../../includes/topbar.php"); ?>

<div class="container-fluid mt-4">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold">

<i class="bi bi-plus-circle-fill text-primary"></i>

Add New Category

</h3>

<p class="text-muted">

Create a new product category.

</p>

</div>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<div class="content-card">

<?php if($message!=""){ ?>

<div class="alert alert-<?php echo $message_type; ?>">

<?php echo $message; ?>

</div>

<?php } ?>

<form method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Category Name

</label>

<input

type="text"

name="category_name"

class="form-control"

required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Status

</label>

<select

name="status"

class="form-select">

<option value="Active">

Active

</option>

<option value="Inactive">

Inactive

</option>

</select>

</div>

<div class="col-12 mb-3">

<label class="form-label">

Description

</label>

<textarea

name="description"

rows="4"

class="form-control"

placeholder="Enter category description..."></textarea>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Category Image

</label>

<input

type="file"

name="image"

id="image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp"

onchange="previewImage(event)">

</div>

<div class="col-md-6 mb-3 text-center">

<img

id="preview"

src="../../assets/images/categories/default-category.png"

style="width:180px;height:180px;object-fit:cover;border-radius:15px;border:2px dashed #ccc;">

</div>

</div>

<hr>

<button

type="submit"

name="save"

class="btn btn-primary">

<i class="bi bi-check-circle"></i>

Save Category

</button>

<a

href="index.php"

class="btn btn-outline-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<script>

function previewImage(event){

    const reader = new FileReader();

    reader.onload = function(){

        document.getElementById("preview").src = reader.result;

    }

    reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
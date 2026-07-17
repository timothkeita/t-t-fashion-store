<?php
include("../../config/config.php");
include("../../config/db.php");
include("../../config/auth.php");

/* ===========================
GET CATEGORY
=========================== */

if(!isset($_GET['id'])){

    header("Location:index.php");
    exit();

}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM categories WHERE id='$id'");

if(mysqli_num_rows($query)==0){

    $_SESSION['success']="Category not found.";

    header("Location:index.php");
    exit();

}

$category = mysqli_fetch_assoc($query);

$message="";
$message_type="";

/* ===========================
UPDATE CATEGORY
=========================== */

if(isset($_POST['update'])){

    $category_name = mysqli_real_escape_string($conn,trim($_POST['category_name']));
    $description   = mysqli_real_escape_string($conn,trim($_POST['description']));
    $status        = $_POST['status'];

    if(empty($category_name)){

        $message="Category name is required.";
        $message_type="danger";

    }else{

        $check=mysqli_query($conn,"
        SELECT id
        FROM categories
        WHERE category_name='$category_name'
        AND id!='$id'
        ");

        if(mysqli_num_rows($check)>0){

            $message="Category already exists.";
            $message_type="warning";

        }else{

            $image=$category['image'];

            if(isset($_FILES['image']) && $_FILES['image']['error']==0){

                $allowed=['jpg','jpeg','png','webp'];

                $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));

                if(in_array($ext,$allowed)){

                    if($image!="default-category.png"){

                        $old="../../assets/images/categories/".$image;

                        if(file_exists($old)){

                            unlink($old);

                        }

                    }

                    $image="category_".time().".".$ext;

                    move_uploaded_file(

                        $_FILES['image']['tmp_name'],

                        "../../assets/images/categories/".$image

                    );

                }

            }

            $update=mysqli_query($conn,"
            UPDATE categories
            SET

            category_name='$category_name',

            description='$description',

            image='$image',

            status='$status'

            WHERE id='$id'
            ");

            if($update){

                $_SESSION['message'] = "Category updated successfully.";
                $_SESSION['type'] = "success";

                header("Location:index.php");

                exit();

            }else{

                $message="Database error.";

                $message_type="danger";

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

<title>Edit Category</title>

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

<i class="bi bi-pencil-square text-warning"></i>

Edit Category

</h3>

<p class="text-muted">

Update category information

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

<label>Category Name</label>

<input

type="text"

name="category_name"

class="form-control"

value="<?php echo htmlspecialchars($category['category_name']); ?>"

required>

</div>

<div class="col-md-6 mb-3">

<label>Status</label>

<select

name="status"

class="form-select">

<option value="Active"

<?php if($category['status']=="Active") echo "selected"; ?>>

Active

</option>

<option value="Inactive"

<?php if($category['status']=="Inactive") echo "selected"; ?>>

Inactive

</option>

</select>

</div>

<div class="col-12 mb-3">

<label>Description</label>

<textarea

name="description"

rows="4"

class="form-control"><?php echo htmlspecialchars($category['description']); ?></textarea>

</div>

<div class="col-md-6">

<label>Category Image</label>

<input

type="file"

name="image"

class="form-control"

accept=".jpg,.jpeg,.png,.webp"

onchange="previewImage(event)">

</div>

<div class="col-md-6 text-center">

<img

id="preview"

src="../../assets/images/categories/<?php echo $category['image']; ?>"

style="width:180px;height:180px;border-radius:15px;object-fit:cover;">

</div>

</div>

<hr>

<button

type="submit"

name="update"

class="btn btn-warning">

<i class="bi bi-save"></i>

Update Category

</button>

<a href="index.php" class="btn btn-outline-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

<script>

function previewImage(event){

const reader=new FileReader();

reader.onload=function(){

document.getElementById("preview").src=reader.result;

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../../assets/js/dashboard.js"></script>

</body>

</html>
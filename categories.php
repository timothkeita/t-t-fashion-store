<?php

$pageTitle = "Categories";

include("includes/header.php");

/* =====================================================
GET ALL ACTIVE CATEGORIES
===================================================== */

$search = "";

if(isset($_GET['search'])){

    $search = mysqli_real_escape_string($conn, trim($_GET['search']));

}

$sql = "

SELECT

c.*,

(

SELECT COUNT(*)

FROM products p

WHERE p.category_id = c.id

AND p.status='Available'

) AS total_products

FROM categories c

WHERE c.status='Active'

";

if($search != ""){

    $sql .= "

    AND (

        c.category_name LIKE '%$search%'

        OR

        c.description LIKE '%$search%'

    )

    ";

}

$sql .= "

ORDER BY c.category_name ASC

";

$categoryQuery = mysqli_query($conn, $sql);

include("includes/navbar.php");

?>

<!-- =====================================================
PAGE HEADER
===================================================== -->

<section class="bg-light border-bottom py-5">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<h1 class="fw-bold display-5">

Shop By Categories

</h1>

<p class="lead text-muted">

Browse our latest fashion collections and discover premium products designed for every style.

</p>

</div>

<div class="col-lg-6 text-lg-end">

<nav>

<ol class="breadcrumb justify-content-lg-end">

<li class="breadcrumb-item">

<a href="index.php">

Home

</a>

</li>

<li class="breadcrumb-item active">

Categories

</li>

</ol>

</nav>

</div>

</div>

</div>

</section>

<!-- =====================================================
CATEGORY SECTION
===================================================== -->

<section class="py-5">

<div class="container">

<div class="text-center mb-5">

<h2 class="fw-bold">

Choose Your Favourite Category

</h2>

<p class="text-muted">

Search or select a category to start shopping.

</p>

<form method="GET" class="row justify-content-center mt-4">

    <div class="col-md-6">

        <div class="input-group">

            <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search categories..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

            <button
            type="submit"
            class="btn btn-warning">

                <i class="bi bi-search"></i>
                Search

            </button>

        </div>

    </div>

</form>

</div>

<div class="row">
<?php

if(mysqli_num_rows($categoryQuery)>0){

while($category=mysqli_fetch_assoc($categoryQuery)){

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 category-card">

<img

src="assets/images/categories/<?php echo $category['image']; ?>"

class="card-img-top"

style="height:250px;object-fit:cover;">

<div class="card-body text-center">

<h4 class="fw-bold">

<?php echo htmlspecialchars($category['category_name']); ?>

</h4>

<p class="text-muted">

<?php echo htmlspecialchars($category['description']); ?>

</p>

<span class="badge bg-dark mb-3">

<?php echo $category['total_products']; ?>

Products

</span>

<div class="d-grid">

<a

href="shop.php?category=<?php echo $category['id']; ?>"

class="btn btn-warning">

<i class="bi bi-bag-fill"></i>

Shop Now

</a>

</div>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="col-12">

<div class="alert alert-warning text-center p-5">

<i class="bi bi-folder-x display-1"></i>

<h3 class="mt-3">

No Categories Found

</h3>

<p>

Categories have not been added yet.

</p>

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<!-- ==========================================
WHY SHOP WITH US
========================================== -->

<section class="py-5 bg-light">

<div class="container">

<div class="row text-center">

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-truck display-4 text-warning"></i>

<h5 class="mt-3">

Fast Delivery

</h5>

<p class="text-muted">

Quick delivery across Rwanda.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-patch-check display-4 text-success"></i>

<h5 class="mt-3">

Quality Products

</h5>

<p class="text-muted">

Premium fashion from trusted brands.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-arrow-repeat display-4 text-primary"></i>

<h5 class="mt-3">

Easy Returns

</h5>

<p class="text-muted">

Simple exchange and return policy.

</p>

</div>

<div class="col-lg-3 col-md-6 mb-4">

<i class="bi bi-headset display-4 text-danger"></i>

<h5 class="mt-3">

24/7 Support

</h5>

<p class="text-muted">

We're always here to help.

</p>

</div>

</div>

</div>

</section>

<!-- ==========================================
NEWSLETTER
========================================== -->

<section class="py-5 bg-dark text-white">

<div class="container text-center">

<h2 class="fw-bold">

Subscribe To Our Newsletter

</h2>

<p>

Receive updates on new arrivals, discounts and fashion trends.

</p>

<form class="row justify-content-center">

<div class="col-md-5">

<input

type="email"

class="form-control"

placeholder="Enter your email">

</div>

<div class="col-md-2 mt-3 mt-md-0">

<button

type="button"

class="btn btn-warning w-100">

Subscribe

</button>

</div>

</form>

</div>

</section>

<?php

include("includes/footer.php");

include("includes/scripts.php");

?>
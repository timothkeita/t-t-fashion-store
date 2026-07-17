<?php

$pageTitle = "Shop";

include("includes/header.php");

/* =====================================================
   T & T FASHION STORE
   SHOP PAGE
======================================================*/

/* -----------------------------
   PAGINATION
------------------------------ */

$productsPerPage = 9;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if($page < 1){
    $page = 1;
}

$offset = ($page - 1) * $productsPerPage;

/* -----------------------------
   SEARCH
------------------------------ */

$search = "";

if(isset($_GET['search'])){

    $search = trim($_GET['search']);

    $search = mysqli_real_escape_string($conn,$search);

}

/* -----------------------------
   CATEGORY
------------------------------ */

$category = 0;

if(isset($_GET['category'])){

    $category = (int)$_GET['category'];

}

/* -----------------------------
   BRAND
------------------------------ */

$brand = "";

if(isset($_GET['brand'])){

    $brand = mysqli_real_escape_string(
        $conn,
        $_GET['brand']
    );

}

/* -----------------------------
   SORT
------------------------------ */

$sort = "latest";

if(isset($_GET['sort'])){

    $sort = $_GET['sort'];

}

/* -----------------------------
   PRICE FILTER
------------------------------ */

$min = 0;

$max = 100000000;

if(isset($_GET['min'])){

    $min = (int)$_GET['min'];

}

if(isset($_GET['max'])){

    $max = (int)$_GET['max'];

}

/* =====================================================
   LOAD CATEGORIES
====================================================== */

$categoryQuery = mysqli_query($conn,"
SELECT *
FROM categories
WHERE status='Active'
ORDER BY category_name ASC
");

/* =====================================================
   LOAD BRANDS
====================================================== */

$brandQuery = mysqli_query($conn,"
SELECT DISTINCT brand
FROM products
WHERE status='Available'
ORDER BY brand ASC
");

/* =====================================================
   PRODUCT SQL
====================================================== */

$sql = "

SELECT

p.*,

c.category_name

FROM products p

LEFT JOIN categories c

ON c.id = p.category_id

WHERE p.status='Available'

";

/* -----------------------------
   SEARCH FILTER
------------------------------ */

if($search!=""){

$sql.="

AND (

p.product_name LIKE '%$search%'

OR

p.brand LIKE '%$search%'

)

";

}

/* -----------------------------
   CATEGORY FILTER
------------------------------ */

if($category>0){

$sql.="

AND

p.category_id='$category'

";

}

/* -----------------------------
   BRAND FILTER
------------------------------ */

if($brand!=""){

$sql.="

AND

p.brand='$brand'

";

}

/* -----------------------------
   PRICE FILTER
------------------------------ */

$sql.="

AND

(price-discount)

BETWEEN

'$min'

AND

'$max'

";
/* -----------------------------
   SORT PRODUCTS
------------------------------ */

switch($sort){

    case "price_low":

        $sql .= "

        ORDER BY

        (price-discount)

        ASC

        ";

    break;

    case "price_high":

        $sql .= "

        ORDER BY

        (price-discount)

        DESC

        ";

    break;

    case "name":

        $sql .= "

        ORDER BY

        product_name ASC

        ";

    break;

    default:

        $sql .= "

        ORDER BY

        id DESC

        ";

}

/* -----------------------------
   COUNT PRODUCTS
------------------------------ */

$countQuery = mysqli_query($conn,$sql);

$totalProducts = mysqli_num_rows($countQuery);

$totalPages = ceil(

$totalProducts /

$productsPerPage

);

/* -----------------------------
   PAGINATION
------------------------------ */

$sql .= "

LIMIT

$offset,

$productsPerPage

";

$productQuery = mysqli_query($conn,$sql);

/* -----------------------------
   LOAD NAVBAR
------------------------------ */

include("includes/navbar.php");

?>

<!-- ===========================
PAGE HEADER
=========================== -->

<section class="bg-light border-bottom py-4">

<div class="container">

<div class="row align-items-center">

<div class="col-md-6">

<h1 class="fw-bold mb-2">

Fashion Collection

</h1>

<p class="text-muted mb-0">

Discover premium clothing, shoes, bags and accessories.

</p>

</div>

<div class="col-md-6 text-md-end">

<nav>

<ol class="breadcrumb justify-content-md-end mb-0">

<li class="breadcrumb-item">

<a
href="index.php">

Home

</a>

</li>

<li class="breadcrumb-item active">

Shop

</li>

</ol>

</nav>

</div>

</div>

</div>

</section>

<!-- ===========================
SHOP CONTENT
=========================== -->

<div class="container py-5">

<div class="row">

<!-- ===========================
LEFT SIDEBAR
=========================== -->

<div class="col-lg-3">

<div class="card shadow-sm border-0 mb-4">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-grid-fill"></i>

Categories

</h5>

</div>

<div class="list-group list-group-flush">

<a

href="shop.php"

class="list-group-item list-group-item-action <?php echo ($category==0)?'active':''; ?>">

All Products

</a>

<?php

mysqli_data_seek($categoryQuery,0);

while($cat=mysqli_fetch_assoc($categoryQuery)){

?>

<a

href="shop.php?category=<?php echo $cat['id']; ?>"

class="list-group-item list-group-item-action <?php echo ($category==$cat['id'])?'active':''; ?>">

<?php echo htmlspecialchars($cat['category_name']); ?>

</a>

<?php

}

?>

</div>

</div>

<!-- ===========================
BRANDS
=========================== -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-tags-fill"></i>

Brands

</h5>

</div>

<div class="list-group list-group-flush">

<a

href="shop.php"

class="list-group-item list-group-item-action">

All Brands

</a>

<?php

mysqli_data_seek($brandQuery,0);

while($b=mysqli_fetch_assoc($brandQuery)){

?>

<a

href="shop.php?brand=<?php echo urlencode($b['brand']); ?>"

class="list-group-item list-group-item-action">

<?php echo htmlspecialchars($b['brand']); ?>

</a>

<?php

}

?>

</div>

</div>

<!-- ===========================
SEARCH
=========================== -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-search"></i>

Search

</h5>

</div>

<div class="card-body">

<form method="GET">

<input

type="text"

name="search"

class="form-control mb-3"

placeholder="Search products..."

value="<?php echo htmlspecialchars($search); ?>">

<button

class="btn btn-dark w-100">

Search

</button>

</form>

</div>

</div>
<!-- ===========================
PRICE FILTER
=========================== -->

<div class="card shadow-sm border-0 mb-4">

<div class="card-header bg-dark text-white">

<h5 class="mb-0">

<i class="bi bi-cash-stack"></i>

Price Range

</h5>

</div>

<div class="card-body">

<form method="GET">

<input
type="hidden"
name="category"
value="<?php echo $category; ?>">

<input
type="hidden"
name="brand"
value="<?php echo htmlspecialchars($brand); ?>">

<input
type="number"
name="min"
class="form-control mb-3"
placeholder="Minimum Price"
value="<?php echo $min; ?>">

<input
type="number"
name="max"
class="form-control mb-3"
placeholder="Maximum Price"
value="<?php echo ($max==100000000 ? "" : $max); ?>">

<button
class="btn btn-warning w-100">

Apply Filter

</button>

</form>

</div>

</div>

</div>

<!-- ===========================
RIGHT CONTENT
=========================== -->

<div class="col-lg-9">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

<div>

<h3 class="fw-bold">

<?php
if($category > 0){

    $cat = mysqli_query($conn,"
    SELECT category_name
    FROM categories
    WHERE id='$category'
    ");

    $catName = mysqli_fetch_assoc($cat);

    echo $catName['category_name'];

}else{

    echo "Fashion Collection";

}
?>

</h3>

<p class="text-muted mb-0">

Showing

<strong>

<?php echo $totalProducts; ?>

</strong>

Products

</p>

</div>

<div>

<form method="GET">

<input
type="hidden"
name="search"
value="<?php echo htmlspecialchars($search); ?>">

<input
type="hidden"
name="category"
value="<?php echo $category; ?>">

<select
name="sort"
class="form-select"
onchange="this.form.submit()">

<option value="latest" <?php if($sort=="latest") echo "selected"; ?>>

Newest

</option>

<option value="price_low" <?php if($sort=="price_low") echo "selected"; ?>>

Price Low → High

</option>

<option value="price_high" <?php if($sort=="price_high") echo "selected"; ?>>

Price High → Low

</option>

<option value="name" <?php if($sort=="name") echo "selected"; ?>>

A → Z

</option>

</select>

</form>

</div>

</div>

<div class="row">

<?php

if(mysqli_num_rows($productQuery)>0){

while($product=mysqli_fetch_assoc($productQuery)){

$finalPrice=$product['price'];

if($product['discount']>0){

$finalPrice=$product['price']-$product['discount'];

}

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card border-0 shadow-sm h-100 product-card">

<div class="position-relative">

<img

src="assets/images/products/<?php echo $product['image']; ?>"

class="card-img-top"

style="height:280px;object-fit:cover;">

<?php if($product['discount']>0){ ?>

<span
class="badge bg-danger position-absolute top-0 start-0 m-2">

SALE

</span>

<?php } ?>

<?php if($product['stock']>0){ ?>

<span
class="badge bg-success position-absolute top-0 end-0 m-2">

In Stock

</span>

<?php }else{ ?>

<span
class="badge bg-secondary position-absolute top-0 end-0 m-2">

Out Of Stock

</span>

<?php } ?>

</div>

<div class="card-body d-flex flex-column">

<small class="text-muted">

<?php echo htmlspecialchars($product['brand']); ?>

</small>

<h5 class="fw-bold mt-2">

<?php echo htmlspecialchars($product['product_name']); ?>

</h5>

<p class="small text-muted">

<?php echo htmlspecialchars($product['category_name']); ?>

</p>

<div class="mb-2">

<span class="text-warning">

★★★★★

</span>

</div>

<?php if($product['discount']>0){ ?>

<p class="mb-3">

<del class="text-muted">

RWF <?php echo number_format($product['price']); ?>

</del>

<br>

<span class="fs-4 fw-bold text-danger">

RWF <?php echo number_format($finalPrice); ?>

</span>

</p>

<?php }else{ ?>

<p class="fs-4 fw-bold text-danger">

RWF <?php echo number_format($product['price']); ?>

</p>

<?php } ?>

<div class="mt-auto">

<div class="d-grid gap-2">

<a
href="product.php?id=<?php echo $product['id']; ?>"
class="btn btn-outline-dark">

<i class="bi bi-eye-fill"></i>

View Details

</a>

<?php if($product['stock']>0){ ?>

<form
action="cart/add.php"
method="POST">

<input
type="hidden"
name="product_id"
value="<?php echo $product['id']; ?>">

<input
type="hidden"
name="quantity"
value="1">

<button
type="submit"
class="btn btn-warning w-100">

<i class="bi bi-cart-plus-fill"></i>

Add To Cart

</button>

</form>

<?php }else{ ?>

<button
class="btn btn-secondary"
disabled>

Out Of Stock

</button>

<?php } ?>

</div>

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

<i class="bi bi-bag-x display-1"></i>

<h3 class="mt-3">

No Products Found

</h3>

<p>

Try changing your search or filters.

</p>

<a
href="shop.php"
class="btn btn-dark">

View All Products

</a>

</div>

</div>

<?php

}

?>

</div>
<!-- ===========================
PAGINATION
=========================== -->

<?php if($totalPages > 1){ ?>

<div class="row mt-5">

<div class="col-12">

<nav>

<ul class="pagination justify-content-center">

<!-- Previous -->

<li class="page-item <?php echo ($page<=1)?'disabled':''; ?>">

<a

class="page-link"

href="shop.php?page=<?php echo max(1,$page-1); ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&brand=<?php echo urlencode($brand); ?>&sort=<?php echo $sort; ?>&min=<?php echo $min; ?>&max=<?php echo $max; ?>">

<i class="bi bi-chevron-left"></i>

Previous

</a>

</li>

<?php

for($i=1;$i<=$totalPages;$i++){

?>

<li class="page-item <?php echo ($i==$page)?'active':''; ?>">

<a

class="page-link"

href="shop.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&brand=<?php echo urlencode($brand); ?>&sort=<?php echo $sort; ?>&min=<?php echo $min; ?>&max=<?php echo $max; ?>">

<?php echo $i; ?>

</a>

</li>

<?php

}

?>

<!-- Next -->

<li class="page-item <?php echo ($page>=$totalPages)?'disabled':''; ?>">

<a

class="page-link"

href="shop.php?page=<?php echo min($totalPages,$page+1); ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category; ?>&brand=<?php echo urlencode($brand); ?>&sort=<?php echo $sort; ?>&min=<?php echo $min; ?>&max=<?php echo $max; ?>">

Next

<i class="bi bi-chevron-right"></i>

</a>

</li>

</ul>

</nav>

</div>

</div>

<?php } ?>

<!-- ===========================
NEWSLETTER
=========================== -->

<section class="mt-5">

<div class="card border-0 shadow bg-dark text-white">

<div class="card-body text-center py-5">

<h2 class="fw-bold">

Stay Updated

</h2>

<p>

Subscribe to receive updates on new arrivals, discounts and exclusive fashion offers.

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

class="btn btn-warning w-100"

type="button">

Subscribe

</button>

</div>

</form>

</div>

</div>

</section>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>

<?php include("includes/scripts.php"); ?>
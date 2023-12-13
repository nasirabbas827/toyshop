<?php
include('config.php');

session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

$user_id = $_SESSION["id"];

$sql = "SELECT id, username, email, age FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $username, $email, $age);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$categoryQuery = "SELECT id, name FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);

$selectedCategory = -1;
$searchKeyword = "";

if (isset($_GET["category_id"]) && is_numeric($_GET["category_id"])) {
    $selectedCategory = $_GET["category_id"];
}

if (isset($_GET["search"])) {
    $searchKeyword = mysqli_real_escape_string($conn, $_GET["search"]);
}

$productsQuery = "SELECT * FROM gifts";

if ($selectedCategory != -1) {
    $productsQuery .= " WHERE category_id = $selectedCategory";
}

if (!empty($searchKeyword)) {
    if ($selectedCategory != -1) {
        $productsQuery .= " AND";
    } else {
        $productsQuery .= " WHERE";
    }

    $productsQuery .= " name LIKE '%$searchKeyword%'";
}

$productsResult = mysqli_query($conn, $productsQuery);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome, <?php echo $username; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $username; ?>!</h2>

        <h3 class="mt-4">Categories</h3>
        <form method="get">
            <div class="form-group">
                <label for="category">Select Category:</label>
                <select class="form-control" name="category_id">
                    <option value="-1">All Categories</option>
                    <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)) { ?>
                        <option value="<?php echo $categoryRow['id']; ?>" <?php if ($selectedCategory == $categoryRow['id']) echo 'selected'; ?>>
                            <?php echo $categoryRow['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <h3 class="mt-4">Search Products</h3>
        <form method="get">
            <div class="form-group">
                <label for="search">Search for products:</label>
                <input type="text" class="form-control" name="search" placeholder="Search for products">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if (mysqli_num_rows($productsResult) === 0) { ?>
            <p class="mt-4">No products available for the selected category or search query.</p>
        <?php } else { ?>
            <h3 class="mt-4">Products</h3>
            <div class="row">
                <?php while ($productRow = mysqli_fetch_assoc($productsResult)) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="./admin/uploads/<?php echo $productRow['image_url']; ?>" class="card-img-top" alt="Product Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $productRow['name']; ?></h5>
                                <p class="card-text"><?php echo $productRow['description']; ?></p>
                                <p class="card-text">Quantity: <?php echo $productRow['quantity']; ?></p>
                                <p class="card-text">Price: <?php echo $productRow['price']; ?></p>
                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="gift_id" value="<?php echo $productRow['gift_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

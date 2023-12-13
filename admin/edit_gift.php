<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if the gift ID is provided in the URL
if (!isset($_GET["gift_id"])) {
    header("Location: view_gifts.php");
    exit;
}

$gift_id = $_GET["gift_id"];

// Fetch gift details for the provided gift ID
$gift_query = "SELECT * FROM gifts WHERE gift_id = $gift_id";
$gift_result = mysqli_query($conn, $gift_query);
$giftRow = mysqli_fetch_assoc($gift_result);

// Handle gift update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"]; // Added field for quantity
    $category_id = $_POST["category_id"];
    
    // Perform basic data validation and sanitation
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $price = floatval($price); // Convert to float
    $quantity = intval($quantity); // Convert to integer
    $category_id = intval($category_id); // Convert to integer
    
    // Update the gift details in the database
    $update_query = "UPDATE gifts 
                     SET name = '$name', description = '$description', price = $price, quantity = $quantity, category_id = $category_id 
                     WHERE gift_id = $gift_id";
    
    if (mysqli_query($conn, $update_query)) {
        echo "Gift updated successfully!";
        header("Location: view_gifts.php");
        exit;
    } else {
        echo "Error updating gift: " . mysqli_error($conn);
    }
}

// Fetch categories from the Categories table
$categoryQuery = "SELECT id, name FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Gift</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php
include('admin_navbar.php');
?>
<div class="container mt-5">
    <h2>Edit Products</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?gift_id=$gift_id"; ?>">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $giftRow['name']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required><?php echo $giftRow['description']; ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo $giftRow['price']; ?>" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $giftRow['quantity']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <select class="form-control" id="category" name="category_id" required>
                <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)) { ?>
                    <option value="<?php echo $categoryRow['id']; ?>" <?php if ($categoryRow['id'] === $giftRow['category_id']) echo "selected"; ?>>
                        <?php echo $categoryRow['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

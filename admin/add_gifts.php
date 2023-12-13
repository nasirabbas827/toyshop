<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"]; // New field for quantity
    $category_id = $_POST["category_id"];
    
    // Handle image upload
    $image_path = "uploads/"; // Specify your upload directory
    $image_name = $_FILES["image"]["name"];
    $image_tmp = $_FILES["image"]["tmp_name"];
    $image_type = $_FILES["image"]["type"];
    
    // Perform basic data validation and sanitation
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $price = floatval($price); // Convert to float
    $quantity = intval($quantity); // Convert to integer
    $category_id = intval($category_id); // Convert to integer
    
    // Insert the gift into the database
    $query = "INSERT INTO gifts (name, description, price, quantity, category_id, image_url) 
              VALUES ('$name', '$description', $price, $quantity, $category_id, '$image_name')";
    
    if (mysqli_query($conn, $query)) {
        move_uploaded_file($image_tmp, $image_path . $image_name); // Move uploaded image to desired location
        echo "Product added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch categories from the Categories table
$categoryQuery = "SELECT id, name FROM categories";
$categoryResult = mysqli_query($conn, $categoryQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php
include('admin_navbar.php');
?>
<div class="container mt-5 mb-5">
    <h2>Add Products</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>
        
        <div class="form-group">
            <label for="category">Category:</label>
            <select class="form-control" id="category" name="category_id" required>
                <?php while ($categoryRow = mysqli_fetch_assoc($categoryResult)) { ?>
                    <option value="<?php echo $categoryRow['id']; ?>"><?php echo $categoryRow['name']; ?></option>
                <?php } ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="image">Image:</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/jpeg, image/png" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="view_gifts.php" class="btn btn-secondary">View Product</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

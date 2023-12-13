<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle gift deletion
if (isset($_GET["delete_id"])) {
    $delete_id = $_GET["delete_id"];
    $delete_query = "DELETE FROM gifts WHERE gift_id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        echo "Gift deleted successfully!";
    } else {
        echo "Error deleting gift: " . mysqli_error($conn);
    }
}

// Fetch gifts with category names from the database using a join
$gifts_query = "SELECT gifts.*, categories.name AS category_name FROM gifts
                LEFT JOIN categories ON gifts.category_id = categories.id";
$gifts_result = mysqli_query($conn, $gifts_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
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
    <h2>Manage Products</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($giftRow = mysqli_fetch_assoc($gifts_result)) { ?>
            <tr>
                <td><?php echo $giftRow['name']; ?></td>
                <td><?php echo $giftRow['description']; ?></td>
                <td><?php echo $giftRow['price']; ?></td>
                <td><?php echo $giftRow['category_name']; ?></td>
                <td><?php echo $giftRow['quantity']; ?></td>
                <td><img src="uploads/<?php echo $giftRow['image_url']; ?>" alt="Gift Image" height="50"></td>
                <td>
                    <a href="edit_gift.php?gift_id=<?php echo $giftRow['gift_id']; ?>" class="btn btn-primary">Edit</a>
                    <a href="view_gifts.php?delete_id=<?php echo $giftRow['gift_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Product?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


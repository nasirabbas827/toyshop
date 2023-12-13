<?php
session_start();

include('config.php');

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

// Check if the cart is empty
if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
    echo "Your shopping cart is empty.";
    exit;
}

// Fetch cart items from the database based on the gift IDs in the session cart
$giftIds = array_keys($_SESSION["cart"]);
$giftIdsString = implode(",", $giftIds);

$cartQuery = "SELECT * FROM gifts WHERE gift_id IN ($giftIdsString)";
$cartResult = mysqli_query($conn, $cartQuery);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Your Shopping Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Your Shopping Cart, <?php echo $username; ?>!</h2>

        <form method="post" action="update_cart.php">
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $overallTotal = 0;
                    while ($cartItem = mysqli_fetch_assoc($cartResult)) {
                        $giftId = $cartItem["gift_id"];
                        $quantity = $_SESSION["cart"][$giftId]["quantity"];
                        $total = $cartItem["price"] * $quantity;
                        $overallTotal += $total;
                    ?>
                        <tr>
                            <td><?php echo $cartItem["name"]; ?></td>
                            <td><?php echo $cartItem["price"]; ?></td>
                            <td>
                                <input class="form-control" type="number" name="quantity[<?php echo $giftId; ?>]" value="<?php echo $quantity; ?>" min="1">
                            </td>
                            <td><?php echo $total; ?></td>
                            <td>
                                <a href="remove_from_cart.php?gift_id=<?php echo $giftId; ?>" class="btn btn-danger">Remove</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div class="mt-3">
                <strong>Overall Cart Total: <?php echo $overallTotal; ?></strong>
            </div>

            <button type="submit" class="btn btn-primary" name="update_cart">Update Cart</button>
            <a href="place_order.php" class="btn btn-success">Place Order</a>

        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch user details from the database
$sql = "SELECT id, username, email, age FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $username, $email, $age);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $order_id = $_POST["order_id"];
    $feedback = mysqli_real_escape_string($conn, $_POST["feedback"]);
    $rating = $_POST["rating"];

    // Insert the feedback and rating into the database
    $insertFeedbackQuery = "INSERT INTO feedback (user_id, order_id, feedback, rating) VALUES ('$user_id', '$order_id', '$feedback', '$rating')";
    if (mysqli_query($conn, $insertFeedbackQuery)) {
        echo "Feedback submitted successfully.";
    } else {
        echo "Error submitting feedback: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Provide Feedback and Rating</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">

</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Provide Feedback and Rating</h2>

    <form method="post" action="">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id']; ?>">
        <input type="hidden" name="order_id" value="<?php echo $_GET['order_id']; ?>">
        
        <div class="form-group">
            <label for="feedback">Feedback:</label>
            <textarea class="form-control" name="feedback" rows="4" cols="50" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="rating">Rating:</label>
            <select class="form-control" name="rating">
                <option value="5">5 (Excellent)</option>
                <option value="4">4 (Very Good)</option>
                <option value="3">3 (Good)</option>
                <option value="2">2 (Fair)</option>
                <option value="1">1 (Poor)</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Feedback and Rating</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

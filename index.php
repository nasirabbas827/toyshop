<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        /* Custom CSS styles */
        body {
            background-color: aquamarine;

        }
        .course-card {
            margin-bottom: 20px;
        }
        /* Style for the carousel */
        .carousel-item {
            height: 500px; 
            position: relative;
        }
        .carousel-caption {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #fff;
            padding: 20px;
        }
    /* Add linear gradient overlay */
    .gradient-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 50%, rgba(0, 0, 0, 0) 100%);
    }
</style>
</head>
<body>
    <?php include('navbar.php'); ?>
 

    <div id="carouselExample" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExample" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExample" data-slide-to="1"></li>
        <li data-target="#carouselExample" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="./images/Pic1.png" class="d-block w-100" alt="Toy Slide 1">
            <div class="gradient-overlay"></div>  
            <div class="carousel-caption">
                <h3>Welcome to Online Toy Shop</h3>
                <p>Discover a world of fun and excitement with our diverse collection of toys.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="./images/Pic2.jpg" class="d-block w-100" alt="Toy Slide 2">
            <div class="gradient-overlay"></div>  
            <div class="carousel-caption">
                <h3>Explore Endless Adventures with Our Toys</h3>
                <p>Find unique and imaginative toys to spark creativity and joy.</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="./images/Pic3.jpg" class="d-block w-100" alt="Toy Slide 3">
            <div class="gradient-overlay"></div>  
            <div class="carousel-caption">
                <h3>Make Every Moment Special with Online Toy Shop</h3>
                <p>Discover a collection that brings smiles and laughter to every child.</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExample" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container mt-5">
        <h2>Select a Category</h2>
        <form action="index.php" method="GET">
            <div class="form-group">
                <label for="category">Choose a category:</label>
                <select class="form-control" id="category" name="category">
                    <option value="">All Categories</option>
                    <?php
                    include('config.php');
                    $categoryQuery = "SELECT * FROM categories";
                    $categoryResult = mysqli_query($conn, $categoryQuery);

                    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                        $categoryId = $categoryRow['id'];
                        $categoryName = $categoryRow['name'];
                        $selected = (isset($_GET['category']) && $_GET['category'] == $categoryId) ? 'selected' : '';
                        echo "<option value='$categoryId' $selected>$categoryName</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Browse Products</button>
        </form>

        <div class="row mt-5">
            <?php
            include('config.php');

            // Check if a category is selected
            $categoryFilter = isset($_GET['category']) ? $_GET['category'] : null;

            // Fetch gifts based on the selected category
            $giftsQuery = "SELECT g.*, c.name AS category_name FROM gifts g
                           INNER JOIN categories c ON g.category_id = c.id";

            if ($categoryFilter !== null && $categoryFilter !== "") {
                // If a category is selected, add a WHERE clause to the query
                $categoryFilter = mysqli_real_escape_string($conn, $categoryFilter);
                $giftsQuery .= " WHERE g.category_id = '$categoryFilter'";
            }

            $giftsResult = mysqli_query($conn, $giftsQuery);

            if(mysqli_num_rows($giftsResult) > 0) {
                while ($giftRow = mysqli_fetch_assoc($giftsResult)) {
                    // Your existing code to display gifts
                    $giftId = $giftRow['gift_id'];
                    $giftName = $giftRow['name'];
                    $giftDescription = $giftRow['description'];
                    $giftPrice = $giftRow['price'];
                    $giftquantity = $giftRow['quantity'];
                    $giftImage = $giftRow['image_url'];
                    $categoryName = $giftRow['category_name'];

                    echo "<div class='col-md-4'>
                            <div class='card'>
                                <img src='./admin/uploads/$giftImage' class='card-img-top' alt='$giftName'>
                                <div class='card-body'>
                                    <h5 class='card-title'>$giftName</h5>
                                    <p class='card-text'>$giftDescription</p>
                                    <p class='card-text'>Quantity: $giftquantity</p>
                                    <p class='card-text'>Price: Rs $giftPrice</p>
                                    <p class='card-text'>Category: $categoryName</p>
                                    <a href='login.php?gift_id=$giftId' class='btn btn-primary'>Add to Cart</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<div class='col-12 text-center'><p>No Products found for the selected category.</p></div>";
            }
            ?>
        </div>
    </div>

    <footer class="mt-5 py-3 bg-light">
        <div class="container text-center">
            <p>&copy; 2023 Toy Shop. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
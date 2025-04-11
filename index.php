<?php
session_start();

require './config.php';
// Initialize cart if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

 // Database connection
$host = "localhost";
$user = "root"; // Change if needed
$pass = ""; // Change if you have a password
$dbname = "sushruta_pharmacy"; // Change to your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch products category-wise (maximum 4 products per category)
$categories = ['Babys', 'Drinks', 'Personals', 'Womens'];


$products = [];
foreach ($categories as $category) {
  $stmt = $conn->prepare("SELECT * FROM all_data_products WHERE category = ? AND status = 'active' LIMIT 4");
  $stmt->bind_param("s", $category); // "s" means string type
  $stmt->execute();
  $result = $stmt->get_result();
  $products[$category] = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <style>
        /* 🎠 Improved Carousel */
        .carousel {
            position: relative;
        }
        .carousel-item {
            height: 500px;
        }
        .carousel-item img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            padding: 15px;
            border-radius: 10px;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(1);
        }


        /* 📱 Responsive */
        @media (max-width: 768px) {
            .carousel-item {
                height: 300px;
            }
            .carousel-caption {
                font-size: 14px;
            }
        }
        .product-img {
        height: 200px; /* Base height */
        object-fit: contain;
        padding: 15px;
        transition: transform 0.3s ease-in-out;
    }

    .product-img:hover {
        transform: scale(1.08);
    }

    .card {
        display: flex; /* Flexbox for consistent card height */
        flex-direction: column;
    }

    .card-body {
        flex-grow: 1; /* Allow card body to expand and fill available space */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Space out the content */
    }

    .view-product-btn,
    .add-to-cart-btn,
    .wishlist-btn {
        font-size: 0.9rem; /* Base font size */
        padding: 0.5rem 1rem; /* Base padding */
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .product-img {
            height: 180px;
        }

        .view-product-btn,
        .add-to-cart-btn,
        .wishlist-btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .product-img {
            height: 150px;
        }

        .view-product-btn,
        .add-to-cart-btn,
        .wishlist-btn {
            font-size: 0.75rem;
            padding: 0.3rem 0.6rem;
        }
    }
    </style>
</head>
<body>

<?php include "navbar.php"; ?>
<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$database = "sushruta_pharmacy";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only active carousel images
$query = "SELECT * FROM carousel WHERE status = 'active' ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Check if there are any active images
if ($result->num_rows > 0) {
    ?>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                echo '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $i . '" 
                class="' . ($i == 0 ? "active" : "") . '" aria-label="Slide ' . ($i + 1) . '"></button>';
                $i++;
            }
            ?>
        </div>
        <div class="carousel-inner">
            <?php
            $result->data_seek(0); // Reset pointer for the carousel items
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                echo '<div class="carousel-item ' . ($i == 0 ? "active" : "") . '">
                    <img src="' . $row["image_url"] . '" class="d-block w-100" alt="' . $row["alt_text"] . '">
                </div>';
                $i++;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <?php
} else {
    echo "<p>No active images found.</p>";
}

// ✅ Close the database connection
$conn->close();
?>


<!-- 🏷️ Display Product Categories -->
<?php foreach ($categories as $category): ?>
  <div class="container mt-4">
    <h2 class="fw-bold text-success"><?php echo htmlspecialchars($category); ?> Care</h2>
    <div class="row justify-content-center">
        <?php foreach ($products[$category] as $product): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                    <a href="productPage.php?id=<?php echo $product['id']; ?>">
                        <img src="assets/uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top product-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-success fw-bold">₹<?php echo number_format($product['price'], 2); ?></p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-success  px-3 shadow-sm d-flex align-items-center gap-2 view-product-btn">
                                <i class="bi bi-eye"></i>
                            </a>

                            <form method="post" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-success add-to-cart-btn">Add+</button>
                            </form>
                            <button onclick="toggleWishlist(this, <?php echo $product['id']; ?>)" class="btn btn-danger  px-3 wishlist-btn shadow-sm">
                                <i class="bi bi-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<hr>
<?php endforeach; ?>

<?php include "footer.php"; ?>
<script src="bootstrap.bundle.min.js"></script>

</body>
</html>

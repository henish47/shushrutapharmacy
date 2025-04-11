<?php
session_start();
include "config.php"; // Ensure your database connection

// Get the category from the URL
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;

// Fetch products based on the selected category
$query = "SELECT * FROM all_data_products WHERE category = '$category'";
$result = mysqli_query($conn, $query);
$products = [];

while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Initialize wishlist if not set
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA - <?php echo htmlspecialchars($category); ?></title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>

    <style>
        /* Product Image */
        .product-img {
            width: 100%;
            height: 150px; /* Adjusted height */
            object-fit: contain; 
            padding: 10px;
            transition: transform 0.3s ease-in-out;
        }

        .product-img:hover {
            transform: scale(1.05);
        }

        /* Card Styling */
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        /* Button Styling */
        .btn {
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #004d00;
        }

        .btn-danger:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
<?php include_once "navbar.php"; ?>

<div class="container mt-4">
    <h1 class="text-center fw-bold"><span style="color:green;"><?php echo htmlspecialchars($category); ?></span> Care</h1>

    <?php if (empty($products)) : ?>
        <p class="text-center text-muted">No products found for this category.</p>
    <?php else : ?>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                        <div class="position-relative text-center p-2">
                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="d-block">
                                <img src="assets/uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                    class="product-img"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    onerror="this.onerror=null; this.src='assets/default-placeholder.png';">
                            </a>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted">
                                Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span>
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <!-- View Button -->
                                <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-success  px-3">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Add to Cart Button -->
                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-success  px-3">
                                        <i class="bi bi-cart"></i> Add+
                                    </button>
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
    <?php endif; ?>
</div>

<?php include_once "footer.php"; ?>
</body>
</html>

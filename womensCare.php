<?php
session_start();
include "./config.php"; // Ensure you have a database connection file

// Fetch only active Baby's Care products from the database
$query = "SELECT * FROM all_data_products WHERE category = 'Womens' AND status = 'active'";
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
    <title>SHUSHRUTA - Baby's Care</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .product-img {
            height: 200px;
            object-fit: contain;
            padding: 15px;
            transition: transform 0.3s ease-in-out;
        }

        .product-img:hover {
            transform: scale(1.08);
        }

        .card {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease-in-out;
            border-radius: 15px;
        }

        .card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            transform: translateY(-5px);
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #2c3e50;
        }

        .card-text {
            font-size: 1.1rem;
            color: #28a745;
            font-weight: 600;
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease-in-out;
            padding: 0.5rem 1rem; /* Adjust padding for responsiveness */
            font-size: 1rem; /* Adjust font size for responsiveness */
        }


        .btn-primary {
            background-color: #28a745;
            border: none;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
            transition: all 0.3s ease-in-out;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        h1 {
            text-align: center;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 30px;
        }

        h1 span {
            color: green;
        }

        .wishlist-btn i.active {
            color: red;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .product-img {
                height: 150px;
            }
            h1 {
                font-size: 1.8rem;
            }
            .btn {
                font-size: 0.9rem; /* Smaller font on medium screens */
                padding: 0.4rem 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .product-img {
                height: 120px;
            }
            h1 {
                font-size: 1.6rem;
            }
            .btn {
                font-size: 0.85rem; /* Even smaller font on small screens */
                padding: 0.3rem 0.6rem;
            }
        }
    </style>
</head>
<body>

<?php include_once "navbar.php"; ?>

<div class="container mt-5">
    <h1><span>Women's</span> Care</h1>
    <div class="row justify-content-center">
        <?php if (empty($products)) : ?>
            <p class="text-center text-muted">No Womens Care products available.</p>
        <?php else : ?>
            <?php foreach ($products as $product) : ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                        <div class="position-relative d-flex justify-content-center">
                            <a href="productPage.php?id=<?php echo $product['id']; ?>">
                                <img src="<?php echo (!empty($product['image'])) ? 'assets/uploads/' . htmlspecialchars($product['image']) : 'assets/uploads/default.jpg'; ?>" 
                                     class="card-img-top product-img" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">₹<?php echo number_format($product['price'], 2); ?></p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-success px-3 shadow-sm">
                                    <i class="bi bi-eye"></i> 
                                </a>

                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-success  px-3 shadow-sm">
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
        <?php endif; ?>
    </div>
</div>

<script>
    function toggleWishlist(button, productId) {
        const icon = button.querySelector("i");
        icon.classList.toggle("bi-heart-fill");
        icon.classList.toggle("bi-heart");
        icon.classList.toggle("active");

        fetch("wishlist.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "product_id=" + productId
        });
    }
</script>

<?php include_once "footer.php"; ?>
</body>
</html>
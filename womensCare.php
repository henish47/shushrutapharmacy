<?php
session_start();

// Product data
$products = [
    ['id' => 13, 'name' => 'Protinex Milk Powder', 'price' => 901, 'image' => './assets/Womens care/protinex_tablet\'s.png'],
    ['id' => 14, 'name' => 'Ultra-Q300', 'price' => 737, 'image' => './assets/Womens care/Ultra_Q300 tablets.png'],
    ['id' => 15, 'name' => 'Sanitary Pads', 'price' => 1229, 'image' => './assets/Womens care/whisper_pads.png'],
    ['id' => 16, 'name' => 'Oziva Protien & Herbs', 'price' => 4099, 'image' => './assets/Womens care/picture-11 (7).webp'],
    ['id' => 17, 'name' => 'Revital H', 'price' => 901, 'image' => './assets/Womens care/women11.webp'],
    ['id' => 18, 'name' => 'Tracnil sachet', 'price' => 737, 'image' => './assets/Womens care/women21.webp'],
    ['id' => 19, 'name' => 'Normoz', 'price' => 1229, 'image' => './assets/Womens care/women31.webp'],
    ['id' => 20, 'name' => 'Centrum', 'price' => 4099, 'image' => './assets/Womens care/women42.webp'],
];

// Store products in session
$_SESSION['products'] = $products;

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
    <title>SHUSHRUTA - Women's Care</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once "navbar.php"; ?>

    <div class="container mt-4">
        <h1><span style="color:green;">Women's</span> Care</h1>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                        <div class="position-relative">
                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top img-fluid product-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </a>
                            <!-- Wishlist Button -->
                            <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" 
                                onclick="toggleWishlist(<?php echo $product['id']; ?>, this)">
                                <i class="bi <?php echo in_array($product['id'], $_SESSION['wishlist']) ? 'bi-heart-fill text-danger' : 'bi-heart'; ?>"></i>
                            </button>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-primary rounded-pill px-3"><i class="bi bi-eye"></i></a>
                                <form method="post" action="add_to_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-primary rounded-pill px-3"><i class="bi bi-cart"></i> Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleWishlist(productId, button) {
            fetch("wishlist.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "product_id=" + productId
            })
            .then(response => response.text())
            .then(data => {
                const icon = button.querySelector("i");
                if (data.trim() === "added") {
                    icon.classList.remove("bi-heart");
                    icon.classList.add("bi-heart-fill", "text-danger");
                } else if (data.trim() === "removed") {
                    icon.classList.remove("bi-heart-fill", "text-danger");
                    icon.classList.add("bi-heart");
                }
            });
        }
    </script>

    <?php include_once "footer.php"; ?>
</body>
</html>

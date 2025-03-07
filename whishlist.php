<?php
session_start();

// Sample product list (can be replaced with dynamic data)
$products = [
    ["id" => 1, "name" => "Pain Relief Tablet", "price" => "₹299", "image" => "./assets/product1.jpg"],
    ["id" => 2, "name" => "Vitamin C Tablets", "price" => "₹199", "image" => "./assets/product2.jpg"],
    ["id" => 3, "name" => "Cough Syrup", "price" => "₹349", "image" => "./assets/product3.jpg"],
];

// Initialize wishlist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Add to wishlist
if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];

    // Check if product is already in wishlist
    if (!in_array($product_id, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $product_id;
    }
}

// Remove from wishlist
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$product_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist - Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wishlist-container { max-width: 800px; margin: auto; padding: 30px; }
        .wishlist-item { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.2s ease; }
        .wishlist-item:hover { transform: translateY(-5px); }
    </style>
</head>
<body>

<?php include_once "navbar.php"; ?>

<div class="container wishlist-container">
    <h2 class="text-center mb-4 text-success">My Wishlist</h2>

    <div class="row">
    <?php if (!empty($_SESSION['wishlist'])): ?>
        <?php foreach ($_SESSION['wishlist'] as $wishlist_id): ?>
            <?php
            // Find product details by ID
            $filteredProducts = array_filter($products, fn($p) => $p['id'] == $wishlist_id);
            $product = !empty($filteredProducts) ? reset($filteredProducts) : null;
            
            // Check if product exists before displaying it
            if ($product):
            ?>
            <div class="col-md-6">
                <div class="wishlist-item d-flex align-items-center p-3">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="rounded me-3" style="width: 80px; height: 80px;">
                    <div>
                        <h5><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="text-muted"><?php echo htmlspecialchars($product['price']); ?></p>
                        <a href="?remove=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger">Remove</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-muted">Your wishlist is empty.</p>
    <?php endif; ?>
</div>

</div>

<?php include_once "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

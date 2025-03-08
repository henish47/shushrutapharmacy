<?php
session_start();

$products = [
    ['id' => 1, 'name' => 'Cetaphil Soap', 'price' => 901, 'image' => './assets/personal cares/cetaphil_cleaning soap.png'],
    ['id' => 2, 'name' => 'Seba-med CARE GEL', 'price' => 737, 'image' => './assets/personal cares/sabamed_Facewash.png'],
    ['id' => 3, 'name' => 'Sunscreen Gel', 'price' => 1229, 'image' => './assets/personal cares/picture-11 (4).webp'],
    ['id' => 4, 'name' => '8X Shampoo', 'price' => 4099, 'image' => './assets/personal cares/8X_shampoo.png'],
    ['id' => 5, 'name' => 'Garnier Gel', 'price' => 901, 'image' => './assets/personal cares/personal11.webp'],
    ['id' => 6, 'name' => 'Nivie Natural Glow', 'price' => 737, 'image' => './assets/personal cares/personal21.webp'],
    ['id' => 7, 'name' => 'Pears Body wash', 'price' => 1229, 'image' => './assets/personal cares/personal31.webp'],
    ['id' => 8, 'name' => 'vaseline', 'price' => 4099, 'image' => './assets/personal cares/personal41.webp'],
];

// Initialize cart if not already initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $product = array_filter($products, fn($p) => $p['id'] === $productId);

    if (!empty($product)) {
        $product = array_values($product)[0];
        $product['quantity'] = 1;

        // Check if the product is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['id'] === $product['id']) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }

        // If not found, add new product to the cart
        if (!$found) {
            $_SESSION['cart'][] = $product;
        }
    }
    echo "success"; // Respond with success message for AJAX
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="./all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="boostrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        
    </style>
</head>
<body>
<?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>
    
    <div class="container mt-4">
    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Personal's</span> Care</h1>
    <div class="row">
        <?php foreach ($products as $product) : ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="position-relative">
                        <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                            <img 
                                src="<?php echo $product['image']; ?>" 
                                class="card-img-top img-fluid product-img" 
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                            >
                        </a>
                        <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-primary rounded-pill px-3 no-hover">
                                <i class="bi bi-eye"></i> 
                            </a>
                           <!-- Add to Cart Button -->
                           <form method="post" action="add_to_cart.php" class="add-to-cart-form">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
    <input type="hidden" name="product_image" value="<?php echo $product['image']; ?>">
    <button type="submit" name="add_to_cart" class="btn btn-primary">
        Add to Cart
    </button>
</form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
    <br>
    <?php include_once "footer.php"; ?>
    <script src="./bootstrap.bundle.min.js"></script>
    <script>
                    function toggleWishlist(button) {
                        const icon = button.querySelector("i");
                        if (icon.classList.contains("bi-heart")) {
                            icon.classList.remove("bi-heart");
                            icon.classList.add("bi-heart-fill");
                            icon.classList.add("text-danger");
                        } else {
                            icon.classList.remove("bi-heart-fill");
                            icon.classList.add("bi-heart");
                            icon.classList.remove("text-danger");
                        }
                    }
                </script>
</body>
</html>

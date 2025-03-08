<?php
session_start();

// Sample products array with 12 unique items
$products = [
    ['id' => 1, 'name' => 'Horlicus', 'price' => 1639, 'image' => './assets/Drinks & supplements/nestle_nangrow.png'],
    ['id' => 2, 'name' => 'Zincovit Opex', 'price' => 450, 'image' => './assets/Drinks & supplements/tablets_15\'s.png'],
    ['id' => 3, 'name' => 'Uprise-D3', 'price' => 327, 'image' => './assets/Drinks & supplements/Uprise-D3.png'],
    ['id' => 4, 'name' => 'PediaSure Milk Powder', 'price' => 573, 'image' => './assets/Drinks & supplements/picture-11 (2).webp'],
    ['id' => 5, 'name' => 'Horlicks Milk ', 'price' => 1639, 'image' => './assets/Drinks & supplements/drinks11.webp'],
    ['id' => 6, 'name' => 'Boost 3x Stamina', 'price' => 450, 'image' => './assets/Drinks & supplements/drinks21.webp'],
    ['id' => 7, 'name' => 'Horlicks choco', 'price' => 327, 'image' => './assets/Drinks & supplements/drinks31.webp'],
    ['id' => 8, 'name' => 'Apta Grow', 'price' => 573, 'image' => './assets/Drinks & supplements/drinks41.webp'],
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="./all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="boostrap.min.css">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<body>
    <?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>
    
    <div class="container mt-4">
    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Drinks's</span> Care</h1>
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
                            <form method="post" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-primary rounded-pill px-3">
                                    <i class="bi bi-cart"></i> Add to Cart
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

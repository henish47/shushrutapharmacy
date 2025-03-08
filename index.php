<?php
session_start();
$products = [
    ['id' => 1, 'name' => 'Pampers Diaper  ', 'price' => 990, 'image' => './assets/Baby\'s care/image_1.png', 'category' => 'babysCare'],
    ['id' => 2, 'name' => 'Nestle Cerelac', 'price' => 330, 'image' => './assets/Baby\'s care/image_2.png', 'category' => 'babysCare'],
    ['id' => 3, 'name' => 'Baby Lotion', 'price' => 800, 'image' => './assets/Baby\'s care/image_3.png', 'category' => 'babysCare'],
    ['id' => 4, 'name' => 'Nangrow Milk Lotion', 'price' => 1320, 'image' => './assets/Baby\'s care/image_4.webp', 'category' => 'babysCare'],
    ['id' => 5, 'name' => 'Horlicus', 'price' => 1639, 'image' => './assets/Drinks & supplements/drinks11.webp', 'category' => 'drinksSupplements'],
    ['id' => 6, 'name' => 'Zincovit Opex', 'price' => 450, 'image' => './assets/Drinks & supplements/tablets_15\'s.png', 'category' => 'drinksSupplements'],
    ['id' => 7, 'name' => 'Uprise-D3', 'price' => 300, 'image' => './assets/Drinks & supplements/Uprise-D3.png', 'category' => 'drinksSupplements'],
    ['id' => 8, 'name' => 'PediaSure Milk Powder', 'price' => 573, 'image' => './assets/Drinks & supplements/picture-11 (2).webp', 'category' => 'drinksSupplements'],
    ['id' => 9, 'name' => 'Cetaphil Soap', 'price' => 901, 'image' => './assets/personal cares/cetaphil_cleaning soap.png', 'category' => 'personalsCare'],
    ['id' => 10, 'name' => 'Seba-med CARE GEL', 'price' => 737, 'image' => './assets/personal cares/sabamed_Facewash.png', 'category' => 'personalsCare'],
    ['id' => 11, 'name' => 'Sunscreen Gel', 'price' => 1229, 'image' => './assets/personal cares/picture-11 (4).webp', 'category' => 'personalsCare'],
    ['id' => 12, 'name' => '8X Shampoo ', 'price' => 4099, 'image' => './assets/personal cares/8X_shampoo.png', 'category' => 'personalsCare'],
    ['id' => 13, 'name' => 'Protinex Milk Powder', 'price' => 901, 'image' => './assets/Womens care/protinex_tablet\'s.png', 'category' => 'womensCare'],
    ['id' => 14, 'name' => 'Ultra-Q300', 'price' => 737, 'image' => './assets/Womens care/Ultra_Q300 tablets.png', 'category' => 'womensCare'],
    ['id' => 15, 'name' => 'Sanitary Pads', 'price' => 1229, 'image' => './assets/Womens care/whisper_pads.png', 'category' => 'womensCare'],
    ['id' => 16, 'name' => 'Oziva Protien & Herbs', 'price' => 4099, 'image' => './assets/Womens care/picture-11 (7).webp', 'category' => 'womensCare']
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
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="boostrap.min.css">
        <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

        <style>
            .carousel-inner img {
              height: 800px;
              object-fit: cover; 
          }
          .product-img {
                transition: transform 0.3s ease-in-out;
            }
            .product-img:hover {
                transform: scale(1.05);
            }
            .wishlist-btn {
                z-index: 10;
                display: block; /* Always visible */
                
            }
          /* Ensure responsiveness */
          @media (max-width: 768px) {
              .carousel-inner img {
                  height: 500px; 
              }
          }
        </style>
    </head>
    <body>
        <?php include_once "navbar.php"; ?>
            <?php include_once "hero.php"; ?>
                <br>
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="./assets/slider1.jpeg" class="d-block w-100 slider" alt="Baby Shopping">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/slider2.jpg" class="d-block w-100" alt="Baby Products">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/slider3.jpg" class="d-block w-100" alt="Healthy Baby Food">
                            <!-- Fix missing image -->
                        </div>
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
                <div class="container mt-4">
                    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Baby's</span> Care</h1>
                    <div class="row">
    <?php foreach ($products as $product): ?>
        <?php if ($product['category'] === 'babysCare'): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                    <!-- Product Image -->
                    <div class="position-relative">
                        <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                            <img 
                                src="<?php echo $product['image']; ?>" 
                                class="card-img-top img-fluid product-img"
                                alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                        <!-- Wishlist Button -->
                        <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>

                        <!-- Button Group -->
                        <div class="d-flex justify-content-center gap-2">
                            <!-- View Product Button -->
                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-primary rounded-pill px-3">
                                <i class="bi bi-eye"></i> 
                            </a>
                            <!-- add to cart btn  -->
                            <form class="add-to-cart-form" method="POST" action="add_to_cart.php">
    <input type="hidden" name="product_id" value="1">
    <input type="hidden" name="product_name" value="T-Shirt">
    <input type="hidden" name="product_price" value="499">
    <input type="hidden" name="product_image" value="images/tshirt.jpg"> <!-- Ensure correct path -->
    <button type="submit" class="btn btn-primary">Add to Cart</button>
</form>


                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

                    <div class="text-center mt-3">
                        <a href="baby'sCare.php" class="btn btn-success btn-lg">View More</a>
                    </div>
                </div>
                <hr>
                <div class="container mt-4">
                    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Drink's and supplement's</span> Care</h1>
                    <br>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <?php if ($product['category'] === 'drinksSupplements'): ?>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                                        <!-- Product Image -->
                                        <div class="position-relative">
                                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                             <img 
                                  src="<?php echo $product['image']; ?>" 
                                         class="card-img-top img-fluid product-img"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     >
                                            </a>
                                            <!-- Wishlist Button (Always Visible) -->
                                            <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                        <!-- Card Body -->
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                                            <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                                            <!-- Button Group (Always Visible) -->
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- View Product Button (No Hover Effect) -->
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
                                <?php endif; ?>
                                    <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="baby'sCare.php" class="btn btn-success btn-lg">View More</a>
                    </div>
                </div>
                <hr>
                <div class="container mt-4">
                    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Personal's</span> Care</h1>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <?php if ($product['category'] === 'personalsCare'): ?>

                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                                        <!-- Product Image -->
                                        <div class="position-relative">
                                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                             <img 
                                  src="<?php echo $product['image']; ?>" 
                                         class="card-img-top img-fluid product-img"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     >
                                            </a>
                                            <!-- Wishlist Button (Always Visible) -->
                                            <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                        <!-- Card Body -->
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                                            <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                                            <!-- Button Group (Always Visible) -->
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- View Product Button (No Hover Effect) -->
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
                                <?php endif; ?>
                                    <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="baby'sCare.php" class="btn btn-success btn-lg">View More</a>
                    </div>
                </div>
                <hr>
                <div class="container mt-4">
                    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Women's</span> Care</h1>
                    <br>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <?php if ($product['category'] === 'womensCare'): ?>

                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                                    <div class="card w-100 shadow-lg border-0 rounded-3 overflow-hidden">
                                        <!-- Product Image -->
                                        <div class="position-relative">
                                            <a href="productPage.php?id=<?php echo $product['id']; ?>" class="card-img-wrapper d-flex justify-content-center">
                             <img 
                                  src="<?php echo $product['image']; ?>" 
                                         class="card-img-top img-fluid product-img"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     >
                                            </a>
                                            <!-- Wishlist Button (Always Visible) -->
                                            <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                        <!-- Card Body -->
                                        <div class="card-body text-center">
                                            <h5 class="card-title fw-bold text-dark"><?php echo htmlspecialchars($product['name']); ?></h5>
                                            <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                                            <!-- Button Group (Always Visible) -->
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- View Product Button (No Hover Effect) -->
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
                                <?php endif; ?>
                                    <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="baby'sCare.php" class="btn btn-success btn-lg">View More</a>
                    </div>
                </div>
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
                <br>
                <?php include_once "footer.php"; ?>
            <script src="./bootstrap.bundle.min.js"></script>
    </body>

    </html>
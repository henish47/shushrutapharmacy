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

</head>
<body>
    <?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>
    
    <div class="container mt-4">
    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Drink's and supplement's</span> Care</h1> <br>
    <div class="row">
    <?php foreach ($products as $product) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
            <div class="card w-100 shadow-sm">
                <div class="card-img-wrapper d-flex justify-content-center">
                    <a href="productPage.php">
                        <img 
                            src="<?php echo $product['image']; ?>" 
                            class="card-img-top img-fluid" 
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                        >
                    </a>
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <p class="card-text">Price: ₹<?php echo number_format($product['price'], 2); ?></p>
                    <form method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn btn-primary w-100">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</div>
    <br>

    <?php include_once "footer.php"; ?>

    <script src="./bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();

$products = [
    ['id' => 1, 'name' => 'Pampers Diaper ', 'price' => 1065, 'image' => './assets/Baby\'s care/image_1.png'],
    ['id' => 2, 'name' => 'Nestle Cerelac', 'price' => 778, 'image' => './assets/Baby\'s care/image_2.png'],
    ['id' => 3, 'name' => 'Baby Lotion', 'price' => 655, 'image' => './assets/Baby\'s care/image_3.png'],
    ['id' => 4, 'name' => 'Nangrow Milk Lotion', 'price' => 1311, 'image' => './assets/Baby\'s care/image_4.webp'],
    ['id' => 5, 'name' => 'Himalaya Cream', 'price' => 1065, 'image' => './assets/Baby\'s care/himalaya.webp'],
    ['id' => 6, 'name' => 'Huggies Pants', 'price' => 778, 'image' => './assets/Baby\'s care/babyProduct21.webp'],
    ['id' => 7, 'name' => 'Pampers Diaper', 'price' => 655, 'image' => './assets/Baby\'s care/babyProduct11.webp'],
    ['id' => 8, 'name' => 'Pamper Active Baby', 'price' => 1311, 'image' => './assets/Baby\'s care/babyProduct31.webp'],
];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTAt</title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="./all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="boostrap.min.css">
    
</head>
<body>
    <?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>
    
    <div class="container mt-4">
    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Baby's</span> Care</h1>
    <div class="row">
    <?php foreach ($products as $product) : ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
            <div class="card w-100 shadow-sm">
                <div class="card-img-wrapper d-flex justify-content-center">
                <a href="productPage.php" class="card-img-wrapper d-flex justify-content-center">
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

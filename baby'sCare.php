<?php
session_start();

$products = [
    [
        'id' => 1, 'name' => 'Pampers Diaper', 'price' => 1065,'image' => './assets/Baby\'s care/image_1.png',
        'category' => 'babysCare',
        'description' => 'Pampers Diapers provide superior absorption, ensuring your baby stays dry and comfortable all day long.',
        'key_benefits' => [
            'Ultra-absorbent core for long-lasting dryness',
            'Soft and breathable material to prevent rashes',
            'Stretchy waistband for a snug fit',
            'Leak-proof protection for overnight use'
        ]
    ],
    [
        'id' => 2,
        'name' => 'Nestle Cerelac',
        'price' => 778,
        'image' => './assets/Baby\'s care/image_2.png',
        'category' => 'babysCare',
        'description' => 'Nestlé Cerelac is a nutritious and tasty cereal for babies, enriched with essential vitamins and minerals.',
        'key_benefits' => [
            'Rich in iron and essential nutrients for healthy growth',
            'Easy to digest, suitable for infants',
            'No added preservatives or artificial flavors',
            'Supports immunity with added probiotics'
        ]
    ],
    [
        'id' => 3,
        'name' => 'Baby Lotion',
        'price' => 655,
        'image' => './assets/Baby\'s care/image_3.png',
        'category' => 'babysCare',
        'description' => 'A gentle moisturizing lotion designed to keep baby’s delicate skin soft and hydrated.',
        'key_benefits' => [
            'Enriched with natural oils and Vitamin E',
            'Hypoallergenic and dermatologically tested',
            'Non-greasy formula for daily use',
            'Helps prevent dryness and irritation'
        ]
    ],
    [
        'id' => 4,
        'name' => 'Nangrow Milk Lotion',
        'price' => 1311,
        'image' => './assets/Baby\'s care/image_4.webp',
        'category' => 'babysCare',
        'description' => 'Nangrow Milk Lotion provides deep nourishment for baby’s sensitive skin, keeping it soft and smooth.',
        'key_benefits' => [
            'Contains milk proteins for skin hydration',
            'Non-sticky and lightweight formula',
            'Free from harsh chemicals and parabens',
            'Ideal for daily application after bath'
        ]
    ],
    [
        'id' => 5,
        'name' => 'Himalaya Cream',
        'price' => 1065,
        'image' => './assets/Baby\'s care/himalaya.webp',
        'category' => 'babysCare',
        'description' => 'Himalaya Baby Cream is infused with natural ingredients to protect and moisturize baby’s skin.',
        'key_benefits' => [
            'Enriched with Olive Oil and Aloe Vera',
            'Provides 24-hour moisture lock',
            'Prevents skin dryness and irritation',
            'Clinically tested for safety'
        ]
    ],
    [
        'id' => 6,
        'name' => 'Huggies Pants',
        'price' => 778,
        'image' => './assets/Baby\'s care/babyProduct21.webp',
        'category' => 'babysCare',
        'description' => 'Huggies Pants offer a perfect fit with soft elastic and superior leak protection.',
        'key_benefits' => [
            '360-degree stretchy waistband for a secure fit',
            'Absorbs wetness for up to 12 hours',
            'Made with breathable cotton-like material',
            'Easy-to-wear pull-up design'
        ]
    ],
    [
        'id' => 7,
        'name' => 'Pampers Diaper',
        'price' => 655,
        'image' => './assets/Baby\'s care/babyProduct11.webp',
        'category' => 'babysCare',
        'description' => 'Pampers Diapers offer overnight protection and extra comfort for active babies.',
        'key_benefits' => [
            'Ultra-thin absorbent layers keep baby dry',
            'Soft cotton-touch cover for sensitive skin',
            'Flexible fit that adapts to baby’s movement',
            'Wetness indicator for easy diaper change timing'
        ]
    ],
    [
        'id' => 8,
        'name' => 'Pamper Active Baby',
        'price' => 1311,
        'image' => './assets/Baby\'s care/babyProduct31.webp',
        'category' => 'babysCare',
        'description' => 'Pamper Active Baby diapers are designed for active infants, ensuring leak protection and comfort.',
        'key_benefits' => [
            'Dual leak guard barriers prevent leaks',
            'Soft stretch sides for flexibility',
            'Dermatologically tested for baby’s safety',
            'Super absorbent gel technology locks in moisture'
        ]
    ],
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>
    
    <div class="container mt-4">
    <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Baby's</span> Care</h1>
    <div class="row">
    <?php foreach ($products as $product) : ?>
        <?php if ($product['category'] === 'babysCare') : ?>
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
                    <!-- Wishlist Button -->
                    <button class="btn btn-light position-absolute top-0 end-0 m-2 rounded-circle shadow wishlist-btn" onclick="toggleWishlist(this)">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>
                <!-- Card Body -->
                <div class="card-body text-center">
                    <h5 class="card-title fw-bold text-dark"> <?php echo htmlspecialchars($product['name']); ?> </h5>
                    <p class="card-text text-muted">Price: <span class="fw-bold text-success">₹<?php echo number_format($product['price'], 2); ?></span></p>
                    <!-- Button Group -->
                    <div class="d-flex justify-content-center gap-2">
                        <!-- View Product Button -->
                        <a href="productPage.php?id=<?php echo $product['id']; ?>" class="btn btn-primary rounded-pill px-3 no-hover">
                            <i class="bi bi-eye"></i>
                        </a>
                        <!-- Add to Cart Button -->
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
        <?php endif; ?>
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

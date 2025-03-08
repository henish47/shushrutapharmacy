

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJr1B6ISv5rFJlI10wm3YX0Z5fGzN2yfgdT8qNzFf6An1hVgs5c0Wcv5dc5D" crossorigin="anonymous">
   <link rel="stylesheet" href="product.css">
</head>
<body>
    <?php include_once "navbar.php"; ?>

<div class="container mt-4">
        <!-- Product Name -->
        <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Baby's</span> Care</h1>

        <br>
        <div class="row">
            <!-- Product Image -->
            <div class="col-12 col-md-6">
                <div class="product-image-wrapper">
                    <img src="./assets/Baby's care/image_1.png" class="img-fluid rounded  small-img mt-5 product-img" alt="Baby Lotion">
                </div>
            </div>

            
            <!-- Product Details -->
            <div class="col-12 col-md-6">
                <div class="product-details card shadow-sm p-4">
                    <!-- Product Price -->
                    <h4 class="product-price mb-4 text-success">
                        <i class="bi bi-currency-dollar"></i> Price: ₹ 1065.00
                    </h4>
                    
                    <!-- Product Description -->
                    <h5 class="product-description-title mb-3 text-primary">Product Details</h5>
                    <p class="product-description mb-4">
                        Baby Lotion is a gentle product designed for your baby’s delicate skin. It moisturizes and softens the skin, keeping it hydrated all day long. Enriched with natural ingredients, it is hypoallergenic and free from harmful chemicals, making it safe for daily use. Ideal for sensitive skin, it helps to maintain smooth and healthy skin in babies.
                    </p>

                    <div class="product-description-features mb-4">
                        <h6><strong>Key Benefits:</strong></h6>
                        <ul>
                            <li>Hypoallergenic and dermatologist-tested</li>
                            <li>Free from parabens and sulfates</li>
                            <li>Suitable for all skin types, including sensitive skin</li>
                            <li>Provides long-lasting moisture</li>
                        </ul>
                    </div>

                    <!-- Add to Cart Form -->
                    <form method="post" action="add_to_cart.php" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="1">
                        <button type="submit" class="btn btn-primary w-100 py-3">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
</div>
   
    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-VkV0HUP2is3ypjzX8m49AdzOrwYYwbh0l6+gy8IFcM/gk9rKmv5WZhvT67nGy27w" crossorigin="anonymous"></script>
</body>
</html>

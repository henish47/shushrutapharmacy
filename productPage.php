

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJr1B6ISv5rFJlI10wm3YX0Z5fGzN2yfgdT8qNzFf6An1hVgs5c0Wcv5dc5D" crossorigin="anonymous">
    <style>
        /* General Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }

        h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 30px;
        }

        /* Product Image Styling */
        .product-image-wrapper {
            height: 96%;
            text-align: center;
            /* margin-bottom: 20px; */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .product-image-wrapper img {
            transition: transform 0.3s ease;
        }

        .product-image-wrapper img:hover {
            transform: scale(1.05);
        }

        /* Product Details Styling */
        .product-details {
            background-color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 25px;
            border-radius: 8px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #27ae60;
        }

        .product-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }

        .product-description-features ul {
            list-style-type: none;
            padding: 0;
        }

        .product-description-features li {
            font-size: 1rem;
            color: #34495e;
        }
        .product-img{
            justify-content: space-around;
            padding: 40px;
            
        }
        /* Add to Cart Button Styling */
        .add-to-cart-form button {
            font-size: 1.1rem;
            font-weight: bold;
            color: #fff;
            background-color: #2980b9;
            border: none;
            border-radius: 5px;
            padding: 12px 0;
            transition: background-color 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-form button:hover {
            background-color: #3498db;
        }

        /* Reviews Section Styling */
       /* General Styling for Product Reviews */
        .product-reviews {
             font-family: 'Arial', sans-serif; /* Change to your desired font */
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }             

        .card:hover {
            transform: translateY(-5px); /* Slightly raise the card on hover */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
        }

        .reviewer-info h4 {
            font-weight: bold;
            font-size: 1.2em;
        }

        .reviewer-info .rating {
         color: gold;
        }

        .reviewer-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }

        /* Style the individual review card */
        .review-item {
            background-color: #fff; /* White background for the card */
             border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Default shadow */
            transition: transform 0.3s, box-shadow 0.3s ease-in-out;
        }

        .review-item:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Increase shadow on hover */
            transform: translateY(-5px); /* Slightly raise the card */
        }

        /* Footer Styling */
        footer {
            background-color: #2c3e50;
            color: #fff;
            padding: 20px;
            text-align: center;
            margin-top: 50px;
            font-size: 1rem;
        }

        footer p {
            margin: 0;
        }

        footer a {
            color: #ecf0f1;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* Media Queries for Mobile Responsiveness */
        @media (max-width: 768px) {
            .product-price {
                font-size: 1.2rem;
            }

            .product-details {
                padding: 20px;
            }

            .product-image-wrapper img {
                max-width: 100%;
                height: auto;
            }

            .product-description {
                font-size: 0.9rem;
            }

            .reviewer-img {
                width: 50px;
                height: 50px;
            }
        }
    </style>
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
<br>
       <!-- Product Reviews -->
       <div class="product-reviews">
       <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Product</span> Review</h1>
    <br>
    <div class="row">
        <!-- Review 1 -->
        <div class="col-12 col-md-4">
            <div class="review-item card shadow-sm p-3 mb-4">
                <div class="reviewer-info">
                    <img src="./assets/rate-1.webp" class="reviewer-img" alt="John Doe"> <br>
                    <h4>John Doe <span class="rating">★★★★★</span></h4>
                </div>
                <p>This lotion is amazing! My baby's skin feels so soft and hydrated. I love that it's made with natural ingredients and doesn't cause any irritation. </p>
            </div>
        </div>
        
        <!-- Review 2 -->
        <div class="col-12 col-md-4">
            <div class="review-item card shadow-sm p-3 mb-4">
                <div class="reviewer-info">
                    <img src="./assets/rete-2.webp" class="reviewer-img" alt="Jane Smith"> <br>
                    <h4>Jane Smith <span class="rating">★★★★☆</span></h4>
                </div>
                <p>Great product! It absorbs quickly and doesn't leave a greasy residue. The only reason I gave it 4 stars is because of the price, but it's worth it for the quality.</p>
            </div>
        </div>
        
        <!-- Review 3 -->
        <div class="col-12 col-md-4">
            <div class="review-item card shadow-sm p-3 mb-4">
                <div class="reviewer-info">
                    <img src="./assets/rate-3.webp" class="reviewer-img" alt="Emily Johnson">
                    <h4>Emily Johnson <span class="rating">★★★★★</span></h4>
                </div>
                <p>I've tried many baby lotions, but this one is by far the best. It keeps my baby's skin moisturized all day, and the scent is very mild and pleasant. Love it!</p>
            </div>
        </div>
    </div>
</div>


    </div>

    <?php include_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-VkV0HUP2is3ypjzX8m49AdzOrwYYwbh0l6+gy8IFcM/gk9rKmv5WZhvT67nGy27w" crossorigin="anonymous"></script>
</body>
</html>

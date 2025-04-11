<?php
include "config.php"; // Database connection

// Fetch footer details
$query = "SELECT * FROM footer_info LIMIT 1";
$result = $conn->query($query);
$footer = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./styles.css">
    
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
<footer class="footer py-4  text-light">
    <div class="container">
        <div class="row text-center text-md-start">
            <!-- About Section -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5>About Us</h5>
                <p>
                    We are committed to providing quality products for your health and personal care needs.
                    Visit us for the best services and top-quality products.
                </p>
            </div>
            
            <!-- Contact Information -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5><a href="./contact_us.php" class="text-dark btn btn-success">Contact Us</a></h5>
                <p><i class="fas fa-map-marker-alt me-2"></i> <?= $footer['address'] ?></p>
                <p><i class="fas fa-phone-alt me-2"></i> <?= $footer['phone'] ?></p>
                <p><i class="fas fa-envelope me-2"></i> <?= $footer['email'] ?></p>
            </div>

            <!-- Social Media & Quick Links -->
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <div class="d-flex justify-content-center justify-content-md-start mb-3">
                    <a href="<?= $footer['facebook'] ?>" class="text-white me-3"><i class="fab fa-facebook fa-2x"></i></a>
                    <a href="<?= $footer['twitter'] ?>" class="text-white me-3"><i class="fab fa-twitter fa-2x"></i></a>
                    <a href="<?= $footer['instagram'] ?>" class="text-white me-3"><i class="fab fa-instagram fa-2x"></i></a>
                    <a href="<?= $footer['linkedin'] ?>" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                </div>

                <h5>Quick Links</h5>
                <ul class="list-unstyled text-center text-md-start">
                    <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                    <!-- <li><a href=".php" class="text-white text-decoration-none">Products</a></li> -->
                    <!-- <li><a href="about.php" class="text-white text-decoration-none">About Us</a></li> -->
                    <li><a href="contact_us.php" class="text-white text-decoration-none">Contact Us</a></li>
                </ul>
            </div>
        </div>

        <hr class="bg-light" />
        <div class="text-center">
            <p class="mb-0">© 2025 Sushruta Pharmacy. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./styles/navbar.css">
  
</head>

<body>
<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/logo.jpg" alt="Company Logo">
        </a>

        <!-- Navbar Toggler (For Mobile) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <div class="navbar-icons d-flex align-items-center">
                <!-- Wishlist Button -->
                <a href="wishlist.php" class="me-3">
                    <i class="fas fa-heart fs-5 text-danger"></i>
                </a>

                <!-- Shopping Cart Icon -->
                <a href="add_to_cart.php" class="me-3">
                    <i class="fas fa-shopping-cart fs-5"></i>
                </a>

                <!-- If User is Logged In -->
                <?php if ($isLoggedIn): ?>
                    <div class="dropdown">
                        <a href="#" id="profileDropdown" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= ($_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg') . '?t=' . time(); ?>" 
                                 alt="Profile" class="profile-pic rounded-circle" 
                                 style="width: 40px; height: 40px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-item text-center">
                                <img src="<?= ($_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg') . '?t=' . time(); ?>" 
                                     alt="Profile" class="rounded-circle border border-white" 
                                     style="width: 50px; height: 50px;">
                                <p class="mt-2 mb-1 fw-bold"><?= $_SESSION['user']['username'] ?></p>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="edit_profile.php">
                                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="./your_order.php">
                                    <i class="fas fa-box-open me-2"></i> Your Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="./change_password.php">
                                <i class="fas fa-key me-2"></i> Change Password

                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="./logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary ms-3">Login</a>
                    <a href="signup.php" class="btn btn-primary ms-2">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Database Connection -->
<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "sushruta_pharmacy"; 

// कनेक्शन बनाएं
$conn = new mysqli($servername, $username, $password, $database);

// कनेक्शन चेक करें
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// सही कॉलम (c_name) के साथ डेटा लाएं
$categoryQuery = "SELECT id, c_name FROM categoryy";
$categoryResult = $conn->query($categoryQuery);
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Navbar Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle bounceUp" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sushruta
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="./index.php">All Products</a></li>
                    <li><a class="dropdown-item" href="#">Baby's care</a></li>
                    <li><a class="dropdown-item" href="#">Drink & Supplements</a></li>
                    <li><a class="dropdown-item" href="#">Women Care</a></li>
                    <li><a class="dropdown-item" href="#">Personal care</a></li>
                    
                        <?php
                        if ($categoryResult->num_rows > 0) {
                            while ($row = $categoryResult->fetch_assoc()) {
                                echo '<li><a class="dropdown-item" href="index.php?category=' . urlencode($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#"></a></li>';
                        }
                        ?>

                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link colorSwing" href="baby'sCare.php">Baby Care</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link borderBounce" href="drinks.php">Drink & Supplements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link neonPulse" href="womensCare.php">Women Care</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bounceDown" href="personalCare.php">Personal Care</a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<?php
$conn->close();
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Ensure jQuery is loaded (optional) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
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
    <script src="./bootstrap.min.css"></script>    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/jquery/fontawesome.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./styles/navbar.css">
  
</head>

<body>
<!-- Bootstrap Navbar -->
<nav class="navbar navbar-light bg-light">
    <div class="container d-flex justify-content-between align-items-center flex-wrap">
        
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/logo.jpg" alt="Company Logo">
        </a>

        <!-- Icons and Profile Section -->
        <div class="d-flex align-items-center">
         <!-- Wishlist -->
<a href="./wishlist.php" class="me-3">
    <i class="bi bi-bookmark-heart-fill fs-2 text-success"></i>
</a>
<!-- Cart -->
<a href="add_to_cart.php" class="me-3">
    <i class="bi bi-cart-check-fill fs-2 text-success"></i>
</a>



            <!-- User Profile -->
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
                        <li><a class="dropdown-item" href="edit_profile.php"><i class="fas fa-user-edit me-2"></i> Edit Profile</a></li>
                        <li><a class="dropdown-item" href="./your_order.php"><i class="fas fa-box-open me-2"></i> Your Orders</a></li>
                        <li><a class="dropdown-item" href="./cp.php"><i class="fas fa-key me-2"></i> Change Password</a></li>
                        <li><a class="dropdown-item" href="./logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-large btn-success ms-3">
                    Sign in
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "sushruta_pharmacy"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only active categories
$categoryQuery = "SELECT id, c_name FROM add_category WHERE status = 'active'";
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
            <?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "sushruta_pharmacy";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch only active categories from database
$query = "SELECT * FROM categories WHERE status = 'active' ORDER BY id ASC";
$result = $conn->query($query);
?><?php while ($row = $result->fetch_assoc()) { ?>
    <li class="nav-item">
        <a class="nav-link <?= htmlspecialchars($row['css_class']) ?>" href="<?= htmlspecialchars($row['url']) ?>">
            <?= htmlspecialchars($row['name']) ?>
        </a>
    </li>
<?php } ?>
        <?php
$conn->close();
?>
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle bounceUp d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        View More
        <i class="bi bi-chevron-down ms-1"></i> <!-- Dropdown icon added -->
    </a>
  
<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
    <?php
    if ($categoryResult && mysqli_num_rows($categoryResult) > 0) {
        while ($row = mysqli_fetch_assoc($categoryResult)) {
            echo '<li><a class="dropdown-item" href="cares.php?category=' . urlencode($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . ' Care</a></li>';
        }
    } else {
        echo '<li><a class="dropdown-item text-muted" href="#">No Categories Available</a></li>';
    }
    ?>
</ul>
</li>    
            </ul>
        </div>
    </div>
</nav>






<!-- Ensure jQuery is loaded (optional) -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
</body>
</html>
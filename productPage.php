<?php
session_start();
// Database connection
$host = "localhost";
$user = "root"; // Change if needed
$pass = ""; // Change if you have a password
$dbname = "sushruta_pharmacy"; // Change to your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if `id` is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from the database
    $stmt = $conn->prepare("SELECT * FROM all_data_products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If product exists, fetch data
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<h2 class='text-center text-danger mt-5'>Product Not Found!</h2>";
        exit;
    }
} else {
    echo "<h2 class='text-center text-danger mt-5'>Invalid Product ID!</h2>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - SHUSHRUTA</title>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; color: #333; }
        .container { max-width: 1200px; }
        .product-image-wrapper { display: flex; justify-content: center; align-items: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background: #fff; padding: 15px; }
        .product-image-wrapper img { width: 100%; max-width: 400px; height: auto; object-fit: contain; }
        .product-details { border-radius: 12px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); padding: 2rem; }
        .product-price { font-size: 1.8rem; font-weight: bold; color: #28a745; text-align: center; }
        .benefits-list { list-style: none; padding: 0; }
        .benefits-list li { padding: 8px 0; font-size: 1rem; color: #155724; display: flex; align-items: center; }
        .benefits-list li i { margin-right: 10px; color: #28a745; font-size: 1.2rem; }
        .btn-success { font-size: 1.2rem; font-weight: bold; background-color: #28a745; border: none; padding: 1rem; }
        .btn-success:hover { background-color: #218838; transform: scale(1.05); }
    </style>
</head>
<body>
    <?php include_once "navbar.php"; ?>
    <div class="container mt-4">
        <h1 class="fw-bold text-center mb-4">
            <span class="text-success"><?php echo htmlspecialchars($product['category']); ?></span> Care - 
            <span class="text-dark"><?php echo htmlspecialchars($product['name']); ?></span>
        </h1>
        <div class="row">
            <div class="col-12 col-md-6 mb-4 mb-md-0">
                <div class="product-image-wrapper">
                    <?php $image_path = (!empty($product['image'])) ? 'assets/uploads/' . htmlspecialchars($product['image']) : 'assets/uploads/default.jpg'; ?>
                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="product-details">
                    <h2 class="text-center fw-bold text-dark mb-4"><?php echo htmlspecialchars($product['name']); ?></h2>
                    <h4 class="product-price mb-4"><i class="fas fa-rupee-sign"></i> ₹<?php echo number_format($product['price'], 2); ?></h4>
                    <h5 class="fw-bold text-dark mb-3">Product Details</h5>
                    <p class="mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                    <h5 class="fw-bold text-dark mb-3">Key Benefits:</h5>
                    <ul class="benefits-list">
                        <?php
                        $key_benefits = str_replace(["\r\n", "\r", "\n"], " ", $product['key_benefits']);
                        $benefits_array = array_filter(array_map('trim', explode('.', $key_benefits)));
                        foreach ($benefits_array as $benefit) {
                            echo "<li><i class='fas fa-check-circle text-success'></i> <span>" . htmlspecialchars($benefit) . "</span></li>";
                        }
                        ?>
                    </ul>
                    <h5 class="fw-bold text-dark mb-3">Available Stock:</h5>
                    <p class="text-muted">
    <?php 
    echo ($product['product_quantity'] > 1) 
        ? $product['product_quantity'] . " items available" 
        : (($product['product_quantity'] == 1) 
            ? "<span class='text-danger fw-bold'>Only 1 item left!</span>" 
            : "<span class='text-danger'>Out of stock</span>");
    ?>
</p>

                    <form method="post" action="add_to_cart.php" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn btn-success w-100 py-3" <?php echo ($product['product_quantity'] <= 0) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php include_once 'footer.php'; ?>
</body>
</html>

<?php
session_start();
include "./config.php";

if (!isset($_SESSION['user']['id'])) {
    die("Please log in to view wishlist.");
}

$user_id = $_SESSION['user']['id'];
$query = "SELECT p.id, p.name, p.image, p.price FROM wishlist w JOIN all_data_products p ON w.product_id = p.id WHERE w.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Wishlist</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-5">
        <h1>My Wishlist</h1>
        <div class="row">
            <?php while ($product = $result->fetch_assoc()) : ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="assets/uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">₹<?php echo number_format($product['price'], 2); ?></p>
                            <form action="wishlist.php" method="POST" onsubmit="event.preventDefault(); toggleWishlist(this, <?php echo $product['id']; ?>);">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-heart-fill active"></i> Remove
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <br>
    <?php include_once 'footer.php'; ?>
</body>
</html>


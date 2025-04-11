<?php
session_start();
include "./config.php";

if (!isset($_SESSION['user']['id'])) {
    die("Unauthorized Access!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user']['id'];
    $product_id = intval($_POST['product_id']);

    $query = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        echo "Product removed from wishlist.";
    } else {
        echo "Failed to remove product.";
    }
} else {
    echo "Invalid request.";
}
?>

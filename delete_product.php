<?php
require './config.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Delete product from the database
    $sql = "DELETE FROM all_data_products WHERE id = $product_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'Products.php';</script>";
    } else {
        echo "<script>alert('Error deleting product: " . $conn->error . "'); window.location.href = 'Products.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'Products.php';</script>";
}
?>
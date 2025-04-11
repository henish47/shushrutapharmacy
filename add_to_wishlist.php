<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user']['id'])) {
    die("Error: User is not logged in.");
}

$user_id = $_SESSION['user']['id'];
$product_id = $_POST['product_id'];

// Check if user_id exists in users table before inserting
$check_user = $conn->prepare("SELECT id FROM users WHERE id = ?");
$check_user->bind_param("i", $user_id);
$check_user->execute();
$result = $check_user->get_result();

if ($result->num_rows == 0) {
    die("Error: User does not exist in the database.");
}

// Insert into wishlist
$stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo "Product added to wishlist successfully!";
} else {
    echo "Error: " . $stmt->error;
}
    
?>
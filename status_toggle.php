<?php
require './config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['status'])) {
    $product_id = intval($_POST['product_id']);
    $new_status = $_POST['status'] === "active" ? "active" : "inactive"; // Validate status

    $update_sql = "UPDATE all_data_products SET status=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $product_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

$conn->close();
?>

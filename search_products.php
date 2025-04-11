<?php
require './config.php'; // Database connection

if (isset($_GET['query'])) {
    $searchTerm = "%" . $_GET['query'] . "%"; // Search pattern
    $sql = "SELECT name FROM all_data_products WHERE name LIKE ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['name'];
    }

    echo json_encode($suggestions);
}
?>

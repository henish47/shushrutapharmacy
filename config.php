<?php
// Increase memory limit to avoid exhaustion issues
ini_set('memory_limit', '1024M');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "sushruta_pharmacy"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inquiry_id'])) {
    $inquiry_id = $_POST['inquiry_id'];

    // Update status to 'read'
    $stmt = $conn->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>

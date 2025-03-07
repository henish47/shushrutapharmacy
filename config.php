<?php
// Increase memory limit to avoid exhaustion issues
ini_set('memory_limit', '1024M');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = "localhost"; // Change if using a remote database
$username = "root"; // Default in Laragon/XAMPP
$password = ""; // Empty by default in Laragon/XAMPP
$database = "shushruta_pharmacy"; // Ensure this is correct

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

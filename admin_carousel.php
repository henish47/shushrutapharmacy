<?php
session_start();
include "config.php";

// Fetch carousel data
$query = "SELECT * FROM carousel ORDER BY created_at DESC";
$result = $conn->query($query);

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM carousel WHERE id=?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_carousel.php");
    exit();
}

// Handle Status Toggle
if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];

    // Fetch current status
    $statusQuery = "SELECT status FROM carousel WHERE id=?";
    $stmt = $conn->prepare($statusQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Toggle status
    $newStatus = ($status === 'active') ? 'inactive' : 'active';

    $updateQuery = "UPDATE carousel SET status=? WHERE id=?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_carousel.php");
    exit();
}

// Handle Image Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $imageName = $_FILES["image"]["name"];
    $imageTmpName = $_FILES["image"]["tmp_name"];
    $altText = $_POST["alt_text"];

    $uploadDir = "uploads/";
    $imagePath = $uploadDir . basename($imageName);

    if (move_uploaded_file($imageTmpName, $imagePath)) {
        $insertQuery = "INSERT INTO carousel (image_url, alt_text, status) VALUES (?, ?, 'inactive')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $imagePath, $altText);
        $stmt->execute();
        $stmt->close();
        header("Location: admin_carousel.php");
        exit();
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <style>
        .content {
            margin-top: 90px;
            margin-left: 80px;
        }
        form {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .table {
            border: 1px solid #ccc;
        }
        .table th, .table td {
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body class="container box mt-4">
    <?php require_once './admin_navbar.php'; require_once './sidebar.php'; ?>
    <div class="content">      
        <h2 class="mb-4">Manage Carousel</h2>

        <!-- Upload New Image Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Select Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alt Text</label>
                <input type="text" name="alt_text" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Upload Image</button>
        </form>

        <hr>

        <!-- Display Carousel Images -->
        <h3>Current Images</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Alt Text</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><img src="<?= $row['image_url'] ?>" width="100" alt="<?= $row['alt_text'] ?>"></td>
                        <td><?= $row['alt_text'] ?></td>
                        <td>
    <?php $status = isset($row['status']) ? $row['status'] : 'inactive'; ?>
    <span class="badge bg-<?= $status === 'active' ? 'success' : 'danger' ?>">
        <?= ucfirst($status) ?>
    </span>
</td>

                        <td>
                            <a href="?toggle_status=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                <?= $row['status'] === 'active' ? 'Inactive' : 'Active' ?>
                            </a>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

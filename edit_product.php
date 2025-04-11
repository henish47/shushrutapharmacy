<?php
require './config.php';

// Fetch product details for editing
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Ensure it's an integer

    $sql = "SELECT * FROM all_data_products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Product not found.'); window.location.href = 'Products.php';</script>";
        exit();
    }
    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = 'Products.php';</script>";
    exit();
}

// Fetch categories from the `add_category` table
$category_query = "SELECT id, c_name FROM add_category";
$category_result = $conn->query($category_query);
$categories = [];

if ($category_result->num_rows > 0) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission for updating product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_product"])) {
    $product_name = trim($_POST["product_name"]);
    $product_price = floatval($_POST["product_price"]);
    $product_category = trim($_POST["product_category"]);
    $product_quantity = isset($_POST["product_quantity"]) && is_numeric($_POST["product_quantity"]) 
                        ? intval($_POST["product_quantity"]) : 1;
    $product_description = trim($_POST["product_description"]);
    $product_key_benefits = trim($_POST["product_key_benefits"]);

    // Handle product image upload
    $product_image = $_FILES["product_image"]["name"];
    $target_dir = "assets/uploads/";    
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);

    if (!empty($product_image)) {
        move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
    } else {
        $product_image = $product['image']; // Keep existing image
    }

    // Update product in the database
    $sql = "UPDATE all_data_products 
            SET name = ?, price = ?, category = ?, product_quantity = ?, description = ?, key_benefits = ?, image = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsisssi", $product_name, $product_price, $product_category, $product_quantity, $product_description, $product_key_benefits, $product_image, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href = 'Products.php';</script>";
    } else {
        echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="font.awesome.css">
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <script src="jquery-3.7.1.js"></script>
    <script src="jquery.validate.js"></script>
    
</head>

<body>
    <?php
    include_once './admin_navbar.php';
    include_once 'sidebar.php';
    ?>

    <div class="content">
        <div class="container mt-4">
            <h2>Edit Product</h2>
            <div class="card mb-4">
                <div class="card-header">Edit Product Details</div>
                <div class="card-body">
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label>Price</label>
                                <input type="number" class="form-control" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>" required min="1" step="0.01">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                            <label>Category</label>
                        <select class="form-control" name="product_category" required>
    <option value="">Select Category</option>
    <option value="Babys">Baby's Care</option>
    <option value="Drinks">Drink & Supplements</option>
    <option value="Womens">Women Care</option>
    <option value="Personals">Personal Care</option>
    <?php
    // Fetch categories where status is 'active'
    require './config.php'; // Include database connection

    $categories = [];
    $category_sql = "SELECT c_name FROM add_category WHERE status = 'active'";
    $category_result = $conn->query($category_sql);

    if ($category_result) {
        while ($row = $category_result->fetch_assoc()) {
            $categories[] = $row['c_name'];
        }
    } else {
        die("❌ Error fetching categories: " . $conn->error);
    }

    // Display fetched categories
    foreach ($categories as $category) { ?>
        <option value="<?= htmlspecialchars($category); ?>">
            <?= htmlspecialchars($category); ?>
        </option>
    <?php } ?>
</select>
                            </div>
                            <div class="col-md-6">
                                <label>Quantity</label>
                                <input type="number" class="form-control" name="product_quantity" value="<?php echo htmlspecialchars($product['product_quantity']); ?>" required min="1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Description</label>
                                <textarea class="form-control" name="product_description" rows="3" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>Key Benefits</label>
                                <textarea class="form-control" name="product_key_benefits" rows="3" required><?php echo htmlspecialchars($product['key_benefits']); ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Product Image</label>
                                <input type="file" class="form-control" name="product_image" accept="image/*">
                                <small>Current Image: <?php echo htmlspecialchars($product['image']); ?></small>
                            </div>
                        </div>
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

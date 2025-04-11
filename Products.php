<?php

require './config.php';

// Fetch categories where status is 'active'
$categories = [];
$category_sql = "SELECT status FROM add_category WHERE status = 'active'";
$category_result = $conn->query($category_sql);

if ($category_result) {
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row['status'];
    }
} else {
    die("❌ Error fetching categories: " . $conn->error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    
    // Check if all required fields are filled
    if (
        !empty($_POST["product_name"]) &&
        !empty($_POST["product_price"]) &&
        !empty($_POST["product_category"]) &&
        !empty($_POST["product_quantity"]) &&
        !empty($_POST["description"]) &&
        !empty($_POST["key_benefits"]) &&
        isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] === UPLOAD_ERR_OK
    ) {
        // Sanitize and escape input data
        $product_name = $conn->real_escape_string($_POST["product_name"]);
        $product_price = floatval($_POST["product_price"]);
        $product_category = $conn->real_escape_string($_POST["product_category"]);
        $product_quantity = intval($_POST["product_quantity"]);
        $description = $conn->real_escape_string($_POST["description"]);
        $key_benefits = $conn->real_escape_string($_POST["key_benefits"]);
        $status = "active"; // Default status

        // Handle image upload
        $image_name = $_FILES["product_image"]["name"];
        $image_tmp = $_FILES["product_image"]["tmp_name"];
        $image_error = $_FILES["product_image"]["error"];

        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($image_ext), $allowed_exts)) {
            die("❌ Invalid file type. Allowed types: JPG, JPEG, PNG, GIF");
        }

        $product_image = time() . "_" . uniqid() . "." . $image_ext;
        $target_dir = "assets/uploads/";

        // Ensure directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . $product_image;

        // Move uploaded file
        if (move_uploaded_file($image_tmp, $target_file)) {
            
            // Insert product into database using prepared statement
            $sql = "INSERT INTO all_data_products (name, price, category, product_quantity, description, key_benefits, image, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdsissss", $product_name, $product_price, $product_category, $product_quantity, $description, $key_benefits, $product_image, $status);

            if ($stmt->execute()) {
                echo "✅ Product added successfully!";
            } else {
                echo "❌ Database error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            die("❌ File upload failed! Check folder permissions.");
        }
    } else {
        die("❌ Please fill all fields and select a valid image.");
    }
}


// Handle edit product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_product"])) {
    $product_id = $_POST["product_id"];
    $product_name = $conn->real_escape_string($_POST["edit_product_name"]);
    $product_price = $conn->real_escape_string($_POST["edit_product_price"]);
    $product_category = $conn->real_escape_string($_POST["edit_product_category"]);
    $product_quantity = $conn->real_escape_string($_POST["edit_product_quantity"]);

    // Update product in the database using prepared statements
    $update_sql = "UPDATE all_data_products SET name=?, price=?, category=?, product_quantity=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sdsii", $product_name, $product_price, $product_category, $product_quantity, $product_id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete product
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM all_data_products WHERE id=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch products
$sql = "SELECT * FROM all_data_products ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="font.awesome.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <script src="jquery-3.7.1.js"></script>
    <script src="jquery.validate.js"></script>
    <style>
        body {
            background-color: rgb(240, 240, 240) !important;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                margin-top: 25px;
            }
        }

        .card.mb-4 {
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;
        }

        .card.mb-4:hover {
            box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1);
        }

        .card {
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;
        }

        .card:hover {
            box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1);
        }
    </style>

</head>

<body>
    <?php
    include_once './admin_navbar.php';
    include_once 'sidebar.php';
    ?>

<div class="content">
    <div class="container mt-4">
        <h2>Manage Products</h2>
        <div class="card mb-4">
            <div class="card-header">Add New Product</div>
            <div class="card-body">
            <form id="productForm" method="POST" enctype="multipart/form-data">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Product Name</label>
            <input type="text" class="form-control" name="product_name" id="product_name" required>
            <small class="error text-danger" id="productNameError"></small>
        </div>
        <div class="col-md-6">
            <label>Price</label>
            <input type="number" class="form-control" name="product_price" id="product_price" required min="1" step="0.01">
            <small class="error text-danger" id="priceError"></small>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label>Category</label>
            <select class="form-control" name="product_category" id="product_category" required>
                <option value="">Select Category</option>
                <option value="Babys">Baby's Care</option>
                <option value="Drinks">Drink & Supplements</option>
                <option value="Womens">Women Care</option>
                <option value="Personals">Personal Care</option>
                <?php
                require './config.php';
                $categories = [];
                $category_sql = "SELECT c_name FROM add_category WHERE status = 'active'";
                $category_result = $conn->query($category_sql);
                if ($category_result) {
                    while ($row = $category_result->fetch_assoc()) {
                        $categories[] = $row['c_name'];
                    }
                }
                foreach ($categories as $category) { ?>
                    <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                <?php } ?>
            </select>
            <small class="error text-danger" id="categoryError"></small>
        </div>
        <div class="col-md-6">
            <label>Quantity</label>
            <input type="number" class="form-control" name="product_quantity" id="product_quantity" required min="1">
            <small class="error text-danger" id="quantityError"></small>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label>Description</label>
            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            <small class="error text-danger" id="descriptionError"></small>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label>Key Benefits</label>
            <textarea class="form-control" name="key_benefits" id="key_benefits" rows="3" required></textarea>
            <small class="error text-danger" id="keyBenefitsError"></small>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label>Product Image</label>
            <input type="file" class="form-control" name="product_image" id="product_image" accept="image/*" required>
            <small class="error text-danger" id="imageError"></small>
        </div>
    </div>

    <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
</form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Existing Products</div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Product Name</th>
            <th>Price (₹)</th>
            <th>Category</th>
            <th>Description</th>
            <th>Key Benefits</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']); ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td>₹<?= number_format($row['price'], 2); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>
                <td><?= nl2br(htmlspecialchars($row['description'])); ?></td>
                <td><?= nl2br(htmlspecialchars($row['key_benefits'])); ?></td>
                <td>
                    <img src="<?= !empty($row['image']) ? 'assets/uploads/' . htmlspecialchars($row['image']) : 'assets/uploads/default.png'; ?>" width="50" class="rounded">
                </td>
                <td>
                    <button class="btn btn-sm toggle-status <?= $row['status'] === 'active' ? 'btn-success' : 'btn-danger'; ?>"
                        data-id="<?= htmlspecialchars($row['id']); ?>"
                        data-status="<?= htmlspecialchars($row['status']); ?>"
                        data-toggle="tooltip"
                        title="Click to toggle status">
                        <?= ucfirst(htmlspecialchars($row['status'])); ?>
                    </button>
                </td>
                <td>
                    <a href="edit_product.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="delete_product.php?id=<?= htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure you want to delete this product?');">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" class="text-center text-muted">No products found</td>
        </tr>
    <?php endif; ?>
</tbody>

</table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".toggle-status").click(function () {
        let button = $(this);
        let productId = button.data("id");
        let currentStatus = button.data("status");
        let newStatus = currentStatus === "active" ? "inactive" : "active"; 

        button.prop("disabled", true);

        $.ajax({
            url: "status_toggle.php",
            type: "POST",
            data: { product_id: productId, status: newStatus },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    button.removeClass("btn-success btn-danger")
                          .addClass(newStatus === "active" ? "btn-success" : "btn-danger")
                          .text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1))
                          .data("status", newStatus);
                } else {
                    alert("❌ Error: " + response.error);
                }
            },
            error: function (xhr) {
                alert("❌ AJAX Request Failed! " + xhr.responseText);
            },
            complete: function () {
                button.prop("disabled", false);
            }
        });
    });
});





</script>


<script>
document.getElementById("productForm").addEventListener("submit", function(event) {
    let isValid = true;

    // Clear previous error messages
    document.querySelectorAll(".error").forEach(el => el.innerText = "");

    const productName = document.getElementById("product_name").value.trim();
    const productPrice = document.getElementById("product_price").value.trim();
    const productCategory = document.getElementById("product_category").value;
    const productQuantity = document.getElementById("product_quantity").value.trim();
    const description = document.getElementById("description").value.trim();
    const keyBenefits = document.getElementById("key_benefits").value.trim();
    const productImage = document.getElementById("product_image").files[0];

    // Product Name Validation (Only letters & spaces, min 3 chars)
    if (productName === "") {
        document.getElementById("productNameError").innerText = "Product name is required.";
        isValid = false;
    } else if (productName.length < 3 || !/^[a-zA-Z\s]+$/.test(productName)) {
        document.getElementById("productNameError").innerText = "Product name must be at least 3 characters and contain only letters & spaces.";
        isValid = false;
    }

    // Price Validation (Must be positive)
    if (productPrice === "" || parseFloat(productPrice) <= 0) {
        document.getElementById("priceError").innerText = "Price must be greater than 0.";
        isValid = false;
    }

    // Category Validation (Cannot be empty)
    if (productCategory === "") {
        document.getElementById("categoryError").innerText = "Please select a category.";
        isValid = false;
    }

    // Quantity Validation (Must be at least 1)
    if (productQuantity === "" || parseInt(productQuantity) < 1) {
        document.getElementById("quantityError").innerText = "Quantity must be at least 1.";
        isValid = false;
    }

    // Description Validation (Minimum 10 characters)
    if (description.length < 10) {
        document.getElementById("descriptionError").innerText = "Description must be at least 10 characters long.";
        isValid = false;
    }

    // Key Benefits Validation (Minimum 10 characters)
    if (keyBenefits.length < 10) {
        document.getElementById("keyBenefitsError").innerText = "Key Benefits must be at least 10 characters long.";
        isValid = false;
    }

    // Product Image Validation (Must be an image file)
    if (!productImage) {
        document.getElementById("imageError").innerText = "Please upload a product image.";
        isValid = false;
    } else {
        const allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        const fileExtension = productImage.name.split('.').pop().toLowerCase();
        if (!allowedExtensions.includes(fileExtension)) {
            document.getElementById("imageError").innerText = "Only JPG, JPEG, PNG, and GIF files are allowed.";
            isValid = false;
        }
    }

    if (!isValid) {
        event.preventDefault(); // Prevent form submission if validation fails
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('productForm');
    
    // Add real-time validation for all fields
    document.getElementById('product_name').addEventListener('input', validateProductName);
    document.getElementById('product_price').addEventListener('input', validatePrice);
    document.getElementById('product_category').addEventListener('change', validateCategory);
    document.getElementById('product_quantity').addEventListener('input', validateQuantity);
    document.getElementById('description').addEventListener('input', validateDescription);
    document.getElementById('key_benefits').addEventListener('input', validateKeyBenefits);
    document.getElementById('product_image').addEventListener('change', validateImage);

    // Validation functions
    function validateProductName() {
        const input = document.getElementById('product_name');
        const value = input.value.trim();
        const error = document.getElementById('productNameError');
        
        if (!value) {
            showError(input, error, 'Product name is required');
            return false;
        }
        if (value.length < 3) {
            showError(input, error, 'Must be at least 3 characters');
            return false;
        }
        if (!/^[a-zA-Z0-9\s\-]+$/.test(value)) {
            showError(input, error, 'Only letters, numbers, spaces and hyphens allowed');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validatePrice() {
        const input = document.getElementById('product_price');
        const value = parseFloat(input.value);
        const error = document.getElementById('priceError');
        
        if (isNaN(value) || value <= 0) {
            showError(input, error, 'Price must be greater than 0');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validateCategory() {
        const input = document.getElementById('product_category');
        const error = document.getElementById('categoryError');
        
        if (input.value === "") {
            showError(input, error, 'Please select a category');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validateQuantity() {
        const input = document.getElementById('product_quantity');
        const value = parseInt(input.value);
        const error = document.getElementById('quantityError');
        
        if (isNaN(value) || value < 1) {
            showError(input, error, 'Quantity must be at least 1');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validateDescription() {
        const input = document.getElementById('description');
        const value = input.value.trim();
        const error = document.getElementById('descriptionError');
        
        if (value.length < 10) {
            showError(input, error, 'Description must be at least 10 characters');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validateKeyBenefits() {
        const input = document.getElementById('key_benefits');
        const value = input.value.trim();
        const error = document.getElementById('keyBenefitsError');
        
        if (value.length < 10) {
            showError(input, error, 'Key benefits must be at least 10 characters');
            return false;
        }
        showSuccess(input, error);
        return true;
    }

    function validateImage() {
        const input = document.getElementById('product_image');
        const error = document.getElementById('imageError');
        
        if (!input.files.length) {
            showError(input, error, 'Product image is required');
            return false;
        }
        
        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (!allowedTypes.includes(file.type)) {
            showError(input, error, 'Only JPG, PNG or GIF images allowed');
            return false;
        }
        
        if (file.size > maxSize) {
            showError(input, error, 'Image must be less than 2MB');
            return false;
        }
        
        showSuccess(input, error);
        return true;
    }

    // Helper functions
    function showError(input, errorElement, message) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        errorElement.textContent = message;
    }

    function showSuccess(input, errorElement) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        errorElement.textContent = '';
    }

    // Form submission validation
    form.addEventListener('submit', function(event) {
        const isValid = validateProductName() && 
                        validatePrice() && 
                        validateCategory() && 
                        validateQuantity() && 
                        validateDescription() && 
                        validateKeyBenefits() && 
                        validateImage();

        if (!isValid) {
            event.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
});
</script>
</body>

</html>

<?php
$conn->close();
?>
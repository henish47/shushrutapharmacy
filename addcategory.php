<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "sushruta_pharmacy");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}



// Add Category Logic
if (isset($_POST['add_category'])) {
    $category_name = mysqli_real_escape_string($con, $_POST['category_name']);
    $insert = "INSERT INTO add_category (c_name, status) VALUES ('$category_name', 'active')";
    if (mysqli_query($con, $insert)) {
        echo "<script>alert('Category added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding category: " . mysqli_error($con) . "');</script>";
    }
}

// Delete Category Logic
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $delete = "DELETE FROM add_category WHERE id = $id";
    if (mysqli_query($con, $delete)) {
        echo "<script>alert('Category deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting category: " . mysqli_error($con) . "');</script>";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Toggle Category Status Logic
if (isset($_GET['toggle_status'])) {
    $id = (int) $_GET['toggle_status'];
    $status = $_GET['status'];
    $new_status = ($status == "active") ? "inactive" : "active";
    $update = "UPDATE add_category SET status = '$new_status' WHERE id = $id";
    if (mysqli_query($con, $update)) {
        echo "<script>alert('Category status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating status: " . mysqli_error($con) . "');</script>";
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="font.awesome.css">
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <script src="jquery-3.7.1.js"></script>
    <script src="jquery.validate.js"></script>
    <script src="jquery/validation.js"></script>
    <style>
        body {
            background-color: rgb(240, 240, 240) !important;
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
            margin-top: 50px;
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .dashboard-stats .card {
            text-align: center;
        }

        .sidebar-collapsed {
            transform: translateX(-100%);
        }

        .content-expanded {
            margin-left: 0;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .content {
                margin-left: 0;
                padding: 20px;
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

        .badge {
            font-size: 14px;
            padding: 8px 12px;
        }

        .btn-status {
            width: 100px;
        }
    </style>
</head>
<body>
    <?php
    // Include the navbar.php and sidebar.php files
    include_once './admin_navbar.php';
    include_once 'sidebar.php';
    ?>

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-4">
            <h2>Add New Category</h2>

            <!-- Add New Category Form -->
            <div class="card mb-4">
                <div class="card-header">Category Information</div>
                <div class="card-body">
                    <form id="addcategory" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" 
                                   placeholder="Enter category name" required
                                   pattern="[A-Za-z\s]{2,50}" 
                                   title="Category name should be 2-50 alphabetic characters">
                            <div class="error-message" id="category_nameError"></div>
                        </div>
                        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="card">
                <div class="card-header">Existing Categories</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch categories from the database
                                $select = "SELECT * FROM add_category";
                                $table = mysqli_query($con, $select);
                                while ($row = $table->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['c_name'] ?></td>
                                        <td>
                                            <a href="?toggle_status=<?= $row['id'] ?>&status=<?= $row['status'] ?>"
                                                class="btn btn-status btn-<?= ($row['status'] == "active") ? 'success' : 'danger'; ?>">
                                                <?= $row['status'] ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this category?');">
                                                <i class="bi bi-trash3"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('addcategory');
            const categoryNameInput = document.getElementById('category_name');
            const categoryNameError = document.getElementById('category_nameError');

            // Validate category name (letters and spaces only, 2-50 chars)
            function validateCategoryName(name) {
                const re = /^[A-Za-z\s]{2,50}$/;
                return re.test(name);
            }

            // Real-time validation for category name
            categoryNameInput.addEventListener('input', function() {
                if (!this.value.trim()) {
                    categoryNameError.textContent = '';
                    this.classList.remove('is-invalid', 'is-valid');
                } else if (!validateCategoryName(this.value.trim())) {
                    categoryNameError.textContent = 'Category name must be 2-50 alphabetic characters';
                    this.classList.add('is-invalid');
                    this.classList.remove('is-valid');
                } else {
                    categoryNameError.textContent = '';
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                // Reset errors
                categoryNameError.textContent = '';
                categoryNameInput.classList.remove('is-invalid', 'is-valid');

                // Validate category name
                if (!categoryNameInput.value.trim()) {
                    categoryNameError.textContent = 'Category name is required';
                    categoryNameInput.classList.add('is-invalid');
                    isValid = false;
                } else if (!validateCategoryName(categoryNameInput.value.trim())) {
                    categoryNameError.textContent = 'Category name must be 2-50 alphabetic characters';
                    categoryNameInput.classList.add('is-invalid');
                    isValid = false;
                } else {
                    categoryNameInput.classList.add('is-valid');
                }

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>

    <style>
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #28a745 !important;
        }
        .form-text {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</body>
</html>
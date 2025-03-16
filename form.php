<?php
$con = mysqli_connect("localhost", "root", "", "demo");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle category addition
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $insert = "INSERT INTO add_category (c_name) VALUES ('$category_name')";
    mysqli_query($con, $insert);
}

// Fetch one record for status display
$status_query = "SELECT status FROM add_category LIMIT 1";
$result = mysqli_query($con, $status_query);
$row = mysqli_fetch_assoc($result);
$status = isset($row['status']) ? $row['status'] : 'inactive';
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
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
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

        .card {
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;
        }

        .card:hover {
            box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1);
        }
    </style>

    <script>
        $(document).ready(function () {
            $("#addcategory").validate({
                rules: {
                    category_name: {
                        required: true,
                        minlength: 2
                    },
                },
                messages: {
                    category_name: {
                        required: "Enter the category name",
                        minlength: "Category name must be at least 2 characters"
                    }
                },
                errorElement: "div",
                errorClass: "text-danger"
            });
        });
    </script>
</head>

<body>

    <div class="content">
        <div class="container mt-4">
            <h2>Add New Category</h2>

            <!-- Add New Category Form -->
            <div class="card mb-4">
                <div class="card-header">Category Information</div>
                <div class="card-body">
                    <form id="addcategory" method="POST" action="">
                        <div class="mb-3">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Display Status -->
            <td>
                <span class="badge bg <?= ($status == "inactive") ? "danger" : "success"; ?>">
                    <?= $status ?>
                </span>
            </td>

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
                                $select = "SELECT * FROM add_category";
                                $table = mysqli_query($con, $select);
                                while ($row = $table->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['c_name'] ?></td>
                                        <td>
                                            <button class="btn btn-<?= ($row['status'] == "active") ? 'success' : 'danger'; ?>">
                                                <?= $row['status'] ?>
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary me-3"><i class="bi bi-eye-fill">edit</i></button>
                                            <button class="btn btn-danger"><i class="bi bi-trash3">delete</i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

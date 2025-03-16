<?php
/*
session_start();

if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

// Add Product Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $product_name = htmlspecialchars(trim($_POST['product_name']));
    $product_price = floatval($_POST['product_price']);

    if (!empty($product_name) && $product_price > 0) {
        $_SESSION['products'][] = [
            'name' => $product_name,
            'price' => $product_price
        ];
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Delete Product Logic
if (isset($_GET['delete'])) {
    $index = (int) $_GET['delete'];
    if (isset($_SESSION['products'][$index])) {
        unset($_SESSION['products'][$index]);
        $_SESSION['products'] = array_values($_SESSION['products']);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
    */
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

        /* Shadow for the Manage Products form */
        .card.mb-4 {
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;
        }

        .card.mb-4:hover {
            box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1);
        }


        /* Remove shadow from other elements */
        .card {
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;
        }

        .card:hover {
            box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1);

        }
    </style>


    <!--- add category validation -->
    <script>
        $(document).ready(function () {
            $("#productForm").validate({
                rules:{
                    product_name:{
                        required: true,
                        minlength: 2
                    },
                    product_price:{
                        required: true,
                        number: true,
                        min: 1
                    },
                    product_category:{
                        required: true
                    },
                    product_quantity:{
                        required: true,
                        number: true,
                        min: 1
                    }
                },
                messages: {
                    product_name:{
                        required: "Please enter the product name",
                        minlength: "Product name must be at least 2 characters"
                    },
                    product_price: {
                        required: "Please enter the product price",
                        number: "Please enter a valid number",
                        min: "Price must be at least ₹1"
                    },
                    product_category: {
                        required: "Please select a category"
                    },
                    product_quantity: {
                        required: "Please enter the quantity",
                        number: "Please enter a valid number",
                        min: "Quantity must be at least 1"
                    }
                },
                errorElement: "div",
                errorClass: "text-danger"
            });
        });
    </script>
</head>

<body>
    <?php
    include_once './admin_navbar.php';
    include_once 'sidebar.php';
    ?>

    <div class="content">
        <div class="container mt-4">
            <!-- Manage Products -->
            <h2>Manage Products</h2>
            <div class="card mb-4">
                <div class="card-header">Add New Product</div>
                <div class="card-body">
                    <form id="productForm" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Product Name</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="col-md-6">
                                <label>Price</label>
                                <input type="number" class="form-control" name="product_price" required min="1"
                                    step="0.01">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Category</label>
                                <select class="form-control" name="product_category" required>
                                    <option value="">Select Category</option>
                                    <option value="Baby Care">Baby Care</option>
                                    <option value="Women Care">Women Care</option>
                                    <option value="Supplementaries">Supplementaries</option>
                                    <option value="Drinks and Groceries">Drinks and Groceries</option>
                                    <option value="Cosmetics">Cosmetics</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Quantity</label>
                                <input type="number" class="form-control" name="product_quantity" required min="1">
                            </div>
                            <div class="row mb-3">
                                <div class="row mb-3">
                                    <label>Product Image</label>
                                    <input type="file" class="form-control" name="product_image" accept="image/*"
                                        required>
                                </div>
                            </div>

                        </div>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>

            <!-- Products List -->
            <div class="card">
                <div class="card-header">Existing Products</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Price (₹)</th>
                                    <th>Category</th>
                                    <th>Image</th>
                                    <th>status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php /*foreach ($_SESSION['products'] as $index => $product): ?>
      <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($product['name']) ?></td>
          <td><?= number_format($product['price'], 2) ?></td>
          <td>
              <a href="?delete=<?= $index ?>" class="btn btn-sm btn-danger">Delete</a>
          </td>
      </tr>
  <?php endforeach; ?>
  <?php if (empty($_SESSION['products'])): ?>
      <tr><td colspan="4" class="text-center">No products added yet</td></tr>
  <?php endif; */ ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
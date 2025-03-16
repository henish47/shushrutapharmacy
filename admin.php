<?php
/*

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = htmlspecialchars(trim($_POST['product_name']));
    $price = (float) $_POST['product_price'];
    $category = htmlspecialchars(trim($_POST['product_category']));
    $quantity = (int) $_POST['product_quantity'];

    // Add the new product to the session
    $_SESSION['products'][] = [
        'name' => $name,
        'price' => $price,
        'category' => $category,
        'quantity' => $quantity
    ];

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $index = (int) $_GET['delete'];
    if (isset($_SESSION['products'][$index])) {
        unset($_SESSION['products'][$index]);
        // Reindex the array to maintain proper indexes
        $_SESSION['products'] = array_values($_SESSION['products']);
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// product editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $index = (int) $_POST['product_index'];
    $name = htmlspecialchars(trim($_POST['product_name']));
    $price = (float) $_POST['product_price'];
    $category = htmlspecialchars(trim($_POST['product_category']));
    $quantity = (int) $_POST['product_quantity'];

    // Update the product 
    $_SESSION['products'][$index] = [
        'name' => $name,
        'price' => $price,
        'category' => $category,
        'quantity' => $quantity
    ];

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
*/
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    // header("Location: index.php"); a
    exit();
}

?>
 <?php
 include_once './admin_navbar.php';
 include_once 'sidebar.php';
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <script src="jquery-3.7.1.js"></script>
    <script src="jquery.validate.js"></script>
    <style>
        html,
        body {
            background-color: rgb(240, 240, 240) !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .content {
            margin-left: 270px;
            /* Matches sidebar width */
            padding: 20px;
            margin-top: 45px;
            /* Matches navbar height */
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        a:link {
            color: black;
            background-color: transparent;
            text-decoration: none;
        }

        .dashboard-stats .card {
            text-align: center;
            box-shadow: 0 4px 8px rgba(71, 71, 71, 0.1), 0 6px 20px rgba(71, 71, 71, 0.1);
            border-radius: 8px;

        }

        .dashboard-stats .card:hover {
            /* box-shadow: 0 6px 12px rgba(44, 44, 44, 0.1), 0 8px 24px rgba(44, 44, 44, 0.1); */
            box-shadow: 0 6px 12px rgba(84, 147, 108, 0.1), 0 8px 24px rgba(84, 147, 108, 0.5);

        }

        .content-expanded {
            margin-left: 0;
        }



        .logo-img {
            height: 50px;
            width: fit-content;
            max-width: 200px;
            margin-right: 7px;
        }
        .card{
            padding: 7px;
        }

    </style>
    <!-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('open'); a
            });
        });
    </script> -->

    <!--- add category validation -->
    <script>
        $(document).ready(function () {
            $("#productForm").validate({
                rules: {
                    product_name: {
                        required: true,
                        minlength: 2
                    },
                    product_price: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    product_category: {
                        required: true
                    },
                    product_quantity: {
                        required: true,
                        number: true,
                        min: 1
                    }
                },
                messages: {
                    product_name: {
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

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-4">
            <h2>Admin Dashboard</h2>


            <!-- Dashboard Stats -->
            <div class="dashboard-stats mb-4">
                <div class="card">
                    <a href="users.php">
                        <div class="card-body">
                            <h5><i class="fas fa-user-circle" style="font-size: 20px; padding:7px;"></i>Total Users</h5>
                            <p class="display-6">120</p>
                        </div>
                    </a>
                </div>
                <div class="card">
                    <a href="Products.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-box" style="font-size: 18px; padding:7px;"></i>Total Products</h5>
                            <p class="display-6">450</p>
                        </div>
                    </a>
                </div>
                <div class="card">
                    <a href="orders.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-boxes-stacked" style="font-size: 18px; padding:7px;"></i>Total
                                Orders
                            </h5>
                            <p class="display-6">85</p>
                        </div>
                    </a>

                </div>
                <div class="card">
                    <a href="ActiveUsers.php">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-user-check" style="font-size: 18px; padding:7px;"></i>Active Users
                        </h5>
                        <p class="display-6">95</p>
                    </div>
                    </a>
                </div>
                  
             
                <div class="card">
                   <a href="InactiveUsers.php">
                   <div class="card-body">
                        <h5><i class="fa-solid fa-user-xmark" style="font-size: 18px; padding:7px;"></i>Inactive Users
                        </h5>
                        <p class="display-6">25</p>
                    </div>
                   </a>
                </div>
                <div class="card">
                    <a href="inquiries.php">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-address-book" style="font-size: 18px; padding:7px;"></i>Inquiries</h5>
                        <p class="display-6">30</p>
                    </div>
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-address-book" style="font-size: 18px; padding:7px;"></i>Pending Orders
                        </h5>
                        <p class="display-6">30</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-address-book" style="font-size: 14px; padding:7px;"></i>Completed
                            Orders</h5>
                       <p class="display-6">30</p>
                    </div>
                </div>

            </div><br>



            <!-- Product Table -->

            <tbody>
                <?php /*foreach ($_SESSION['products'] as $index => $product): ?>
<tr>
<td><?= $index + 1 ?></td>
<td><?= htmlspecialchars($product['name']) ?></td>
<td><?= htmlspecialchars($product['price']) ?></td>
<td><?= htmlspecialchars($product['category']) ?></td>
<td><?= htmlspecialchars($product['quantity']) ?></td>
<td>
    <a href="?delete=<?= $index ?>" class="btn btn-sm btn-danger">Delete</a>
    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditForm(<?= $index ?>, '<?= htmlspecialchars($product['name'], ENT_QUOTES) ?>', <?= $product['price'] ?>, '<?= htmlspecialchars($product['category'], ENT_QUOTES) ?>', <?= $product['quantity'] ?>)">Edit</button>
</td>
</tr>
<?php endforeach; */ ?>
            </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>

    <!-- Edit Product Modal
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" id="product_index" name="product_index">

                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_product_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_product_price" name="product_price" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_product_category" class="form-label">Category</label>
                        <select class="form-control" id="edit_product_category" name="product_category" required>
                            <option>Select Category</option>
                            <option value="Baby Care">Baby Care</option>
                            <option value="Women Care">Women Care</option>
                            <option value="Supplementaries">Supplementaries</option>
                            <option value="Drinks and Groceries">Drinks and Groceries</option>
                            <option value="Cosmetics">Cosmetics</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_product_quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="edit_product_quantity" name="product_quantity" required>
                    </div>

                    <button type="submit" name="edit_product" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
                                 -->
    <!--
<script>
        function populateEditForm(index, name, price, category, quantity) {
    document.getElementById('product_index').value = index;
    document.getElementById('edit_product_name').value = name;
    document.getElementById('edit_product_price').value = price;
    document.getElementById('edit_product_category').value = category;
    document.getElementById('edit_product_quantity').value = quantity;
}

    </script>
                                -->
</body>

</html>
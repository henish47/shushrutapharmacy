<?php
session_start();

// ✅ Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login page
    exit();
}

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
            background-color: #f4f4f4 !important;
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .content {
            margin-left: 0;
            padding: 20px;
            margin-top: 45px;
        }

        @media (min-width: 768px) {
            .content {
                margin-left: 270px;
            }
        }

        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .dashboard-stats .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            border: none;
        }

        .dashboard-stats .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .dashboard-stats .card h5 {
            font-size: 1.2rem;
            color: #333;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .dashboard-stats .card p {
            font-size: 2.5rem;
            font-weight: 700;
            color: #007bff;
            margin-bottom: 15px;
        }

        .dashboard-stats .card i {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 10px;
        }

        a:focus,
        button:focus {
            outline: none;
            box-shadow: none;
        }

        a {
            text-decoration: none !important;
            color: inherit;
        }

        .dashboard-stats .card.user-card {
            background-color: #e8f5e9; /* Light green for users */
            color: #2e7d32;
        }

        .dashboard-stats .card.product-card {
            background-color: #e3f2fd; /* Light blue for products */
            color: #1e88e5;
        }

        .dashboard-stats .card.order-card {
            background-color: #fff3e0; /* Light orange for orders */
            color: #f9a825;
        }

        .dashboard-stats .card.inquiry-card {
            background-color: #ede7f6; /* Light purple for inquiries */
            color: #5e35b1;
        }

        .dashboard-stats .card.pending-card {
            background-color: #ffe0b2;
            color: #ef6c00;
        }

        .dashboard-stats .card.completed-card {
            background-color: #c8e6c9;
            color: #388e3c;
        }
    </style>

</head>

<body>

    <div class="content">
        <div class="container mt-4">
            <h2>Admin Dashboard</h2>

            <div class="dashboard-stats mb-4">
                <div class="card user-card">
                    <a href="users.php">
                        <div class="card-body">
                            <h5><i class="fas fa-user-circle"></i> Total Users</h5>
                            <p class="display-6">120</p>
                        </div>
                    </a>
                </div>

                <div class="card product-card">
                    <a href="Products.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-box"></i> Total Products</h5>
                            <p class="display-6">450</p>
                        </div>
                    </a>
                </div>

                <div class="card order-card">
                    <a href="orders.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-boxes-stacked"></i> Total Orders</h5>
                            <p class="display-6">85</p>
                        </div>
                    </a>
                </div>

                <div class="card user-card">
                    <a href="users.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-user-check"></i> Active Users</h5>
                            <p class="display-6">95</p>
                        </div>
                    </a>
                </div>

               
                <div class="card inquiry-card">
    <a href="./addcategory.php">
        <div class="card-body">
            <h5><i class="fa-solid fa-folder"></i> Add Category</h5>
            <p class="display-6">30</p>
        </div>
    </a>
</div>


                <div class="card inquiry-card">
                    <a href="inquiries.php">
                        <div class="card-body">
                            <h5><i class="fa-solid fa-address-book"></i> Inquiries</h5>
                            <p class="display-6">30</p>
                        </div>
                    </a>
                </div>

                <div class="card pending-card">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-spinner"></i> Pending Orders</h5>
                        <p class="display-6">30</p>
                    </div>
                </div>

                <div class="card completed-card">
                    <div class="card-body">
                        <h5><i class="fa-solid fa-check-circle"></i> Completed Orders</h5>
                        <p class="display-6">30</p>
                    </div>
                </div>
              
            </div>
        </div>
    </div>
</body>

</html>
<?php
// Include the navbar.php file at the top
include_once './admin_navbar.php';
include_once 'sidebar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Order Records</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        /* Navbar and sidebar adjustments */
        main {
            margin-left: 0; /* Adjusted for responsiveness */
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
        }

        @media (min-width: 768px) {
            main {
                margin-left: 250px; /* Restore margin for larger screens */
            }
        }

        header {
            background: linear-gradient(90deg, #d6d6d6, #c9c9c9);
            color: black;
            text-align: center;
            padding: 1.5rem 0;
            margin-bottom: 1rem;
            margin-top: 65px;
        }

        header h1 {
            font-size: 2rem;
            margin: 0;
        }

        .container {
            max-width: 1200px;
            margin: 1rem auto; /* Adjusted margin for responsiveness */
            padding: 1rem; /* Adjusted padding for responsiveness */
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(240, 240, 240);
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th,
        table td {
            text-align: left;
            padding: 0.75rem; /* Adjusted padding for responsiveness */
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #d6d6d6;
            color: black;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: rgb(223, 223, 223) !important;
            color: #000 !important;
            transition: all 0.3s ease-in-out;
        }

        .status-pending {
            font-weight: bold;
            color: #ff9800;
        }

        .status-shipped {
            font-weight: bold;
            color: #4CAF50;
        }

        .status-cancelled {
            font-weight: bold;
            color: #e53935;
        }

        .btn-status {
            padding: 6px 12px; /* Adjusted padding for responsiveness */
            border: none;
            cursor: pointer;
            font-size: 0.8rem; /* Adjusted font size for responsiveness */
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-shipped {
            background-color: #4CAF50;
            color: white;
        }

        .btn-shipped:hover {
            background-color: #43a047;
        }

        .btn-cancelled {
            background-color: #e53935;
            color: white;
        }

        .btn-cancelled:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <main>
        <header>
            <h1>Order Records</h1>
        </header>
        <div class="container">
            <div class="table-container">
                <table id="orderTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <tr>
                            <td>1</td>
                            <td>#ORD001</td>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>2025-01-22 14:00</td>
                            <td class="status status-pending">Pending</td>
                            <td><button class="btn-status btn-shipped" onclick="toggleOrderStatus(this, 1)">Ship</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>#ORD002</td>
                            <td>Jane Smith</td>
                            <td>jane.smith@example.com</td>
                            <td>2025-01-22 13:45</td>
                            <td class="status status-shipped">Shipped</td>
                            <td><button class="btn-status btn-cancelled" onclick="toggleOrderStatus(this, 2)">Cancel</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>#ORD003</td>
                            <td>Sam Wilson</td>
                            <td>sam.wilson@example.com</td>
                            <td>2025-01-22 13:30</td>
                            <td class="status status-cancelled">Cancelled</td>
                            <td><button class="btn-status btn-shipped" onclick="toggleOrderStatus(this, 3)">Ship</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    </body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sidebar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>

    <style>
        body,
        .sidebar {
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 70px;
            left: 0;
            height: calc(150vh - 10px);
            height: 200%;
            background-color: #2C7A4B;
            color: white;
            padding: 15px;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
        }

        .sidebar a.active {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .sidebar a:hover {
            color: #A7D7C5;
        }


        .content {
            margin-left: 270px;
            /* Matches sidebar width */
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
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        });
    </script>
</head>

<body>


    <!--   sidebar -->
    <div class="sidebar">
        <h2>Categories</h2>
        <a href="addcategory.php"> <button class="btn mb-3"
                style="width: 78%; margin-left:-3px; background-color:rgb(84, 147, 108); color: white;">
                Add New Category</button></a>

        <h2 class="mt-4">Other Links</h2>

        <a href="admin.php"> <button class="btn mb-3"
                style="width: 78%; margin-left:-3px; background-color:rgb(84, 147, 108); color: white;">
                Dashboard</button></a>

                <a href="orders.php"> <button class="btn mb-3"
                style="width: 78%; margin-left:-3px; background-color:rgb(84, 147, 108); color: white;">
                Orders</button></a>

                <a href="users.php"> <button class="btn mb-3"
                style="width: 78%; margin-left:-3px; background-color:rgb(84, 147, 108); color: white;">
                Users</button></a>
  
                <a href="Products.php"> <button class="btn mb-3"
                style="width: 78%; margin-left:-3px; background-color:rgb(84, 147, 108); color: white;">
                Products</button></a>
        


    </div>
</body>

</html>
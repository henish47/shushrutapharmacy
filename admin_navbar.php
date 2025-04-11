<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Assuming user data is stored in the session
$user_image = isset($_SESSION['user']['profile_pic']) && !empty($_SESSION['user']['profile_pic']) 
    ? $_SESSION['user']['profile_pic'] 
    : "assets/people.png"; // Default avatar
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Shusruta Pharmacy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        body,
        .navbar {
            font-family: 'Inter', sans-serif;
        }

        .navbar {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            background-color: rgb(47, 82, 61) !important;
        }

        .navbar a:hover {
            color: #A7D7C5;
        }

        .navbar-toggler {
            border: none;
        }

        .logo-img {
            height: 50px;
            max-width: 200px;
            margin-right: 7px;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .navbar-brand {
                margin-left: 10px; /* Adjust logo spacing on small screens */
            }

            .navbar-nav {
                text-align: center; /* Center nav items on small screens */
            }

            .navbar-nav .nav-item {
                margin: 5px 0; /* Add spacing between nav items on small screens */
            }

            .navbar-nav.ms-auto {
                margin-top: 10px; /* Add space for profile dropdown on small screens */
            }

            .navbar-toggler {
                margin-right: 10px; /* Adjust toggler spacing on small screens */
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button id="sidebarToggle" class="navbar-toggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <img src="logo2-removebg-preview.png" alt="Shusruta Pharmacy Logo" class="logo-img">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Add your menu items here -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="admin.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="addcategory.php">Add Category</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php">Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="Products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="./inquiries.php">Inquires</a></li>
                    <li class="nav-item"><a class="nav-link" href="./admin_footer.php">Footer</a></li>
                    <li class="nav-item"><a class="nav-link" href="./admin_carousel.php">Coursel</a></li>
                    <li class="nav-item"><a class="nav-link" href="./admin_navigation.php">Navigation</a></li>
                </ul>
            </ul>

            <!-- Navbar Profile Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars($user_image); ?>?t=<?= time(); ?>" 
                             alt="Profile Picture" 
                             class="rounded-circle" 
                             width="30" 
                             height="30" 
                             onerror="this.onerror=null; this.src='assets/people.png';">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="./admin_changepass.php">Change Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
    

</body>

</html>
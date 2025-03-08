<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="stylesheet" href="styles.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=search" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="./all.min.css"> -->
     <link rel="stylesheet" href="./all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="bootstrap.min.css" />
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <style>
        /* Navbar Styles */
        .navbar {
            background-color: rgb(26, 84, 51);
            padding: 10px 20px;
        }

        .navbar-brand {
            font-family: "Edu AU VIC WA NT Dots", serif;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
        }

        .login-btn {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            color: #2c6e49;
            border: 1px solid #2c6e49;
            background-color: transparent;
        }

        .login-btn:hover {
            background-color: #2c6e49;
            color: white;
        }

        /* Flexbox for Navbar Icons and Buttons */
        .navbar-icons {
            display: flex;
            justify-content: flex-end;
        }

        /* Responsive adjustments for buttons */
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .login-btn {
                font-size: 0.75rem;
                padding: 3px 8px;
            }

            .navbar-icons {
                flex-direction: column;
                align-items: flex-end;
            }

            .login-btn + .login-btn {
                margin-top: 2px;
            }
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        /* Medium to Large Screen Styles (flex direction in row) */
        @media (min-width: 577px) {
            .navbar-icons {
                flex-direction: row;
                justify-content: flex-end;
            }

            .login-btn {
                font-size: 0.85rem;
                padding: 5px 12px;
            }
        }

        .dropdown-menu {
            min-width: 200px;
            text-align: center;
        }

        .dropdown-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        /* General Styling */
        .navbar-nav .nav-link {
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: capitalize;
            transition: all 0.3s ease-in-out;
            display: inline-block;
            position: relative;
        }


        

        /* Dropdown Menu Styling */
        .dropdown-menu {
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Profile Image */
        .profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        /* Username Styling */
        .username {
            font-weight: bold;
            color: #333;
        }

        /* Dropdown Items */
        .dropdown-item {
            color: #333;
            font-size: 1rem;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            transition: background 0.3s ease-in-out;
        }

        /* Icons inside Dropdown */
        .dropdown-item i {
            font-size: 1.2rem;
            color: #555;
        }

        /* Hover Effect */
        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        /* Divider Styling */
        .dropdown-divider {
            margin: 5px 0;
            border-top: 1px solid #ddd;
        }

        @media (max-width: 768px) {
        .navbar .d-flex {
            flex-wrap: nowrap !important;
            justify-content: center;
            gap: 10px;
        }

        .login-btn {
            white-space: nowrap; /* Prevents button text from wrapping */
            display: flex;
            align-items: center;
            gap: 5px; /* Ensures proper spacing */
        }
    }
    /* .navbar-brand img {
    mix-blend-mode: multiply;
    width: 100px;
    height: 67%;
    margin-top: -10px;
} */
.navbar-icons a i {
    color: #28a745; /* Bootstrap success green color */
}

 /* Add underline effect on hover */
.navbar-nav .nav-link {
        position: relative;
        display: inline-block;
        padding-bottom: 5px;
    }

    .navbar-nav .nav-link:hover {
    color:rgb(107, 171, 115) !important; /* Bootstrap's gray color */ 
   }

    .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        display: none;
        
       
    }

    .navbar-nav .nav-link:hover::after {
        width: 100%;
        left: 0;
    }
    .navbar-brand img {
    max-height: 50px; /* Default for large screens */
    width: auto;
    object-fit: contain;
}

@media (max-width: 768px) {
    .navbar-brand img {
        max-height: 60px; /* Reduce size for tablets */
    }

    .profile-pic {
        width: 30px !important;
        height: 30px !important;
    }

    .profile-img {
        width: 60px !important;
        height: 60px !important;
    }
    .navbar-brand img {
    height: 100px; /* Default height for larger screens */
    width: auto;
    
     /* Prevents image overflow */
}

    .login-btn span {
        font-size: 0.9rem;
    }

    .login-btn i {
        font-size: 16px;
    }

    .navbar {
        padding: 10px 15px; /* Adjust padding for smaller screens */
    }
    
}

    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src=".\assets\logo.jpg" alt="Company Logo">
</a>

        <!-- Navbar Icons -->
        <div class="navbar-icons d-flex align-items-center ms-auto">
            <!-- Shopping Cart Icon -->
            <a href="add_to_cart.php" class="me-3">
                <i class="fas fa-shopping-cart fs-5"></i>
            </a>

            <!-- If User is Logged In -->
            <?php if ($isLoggedIn): ?>
                <div class="dropdown">
                    <a href="#" id="profileDropdown" class="dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="<?= ($_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg') . '?t=' . time(); ?>" 
                             alt="Profile" class="profile-pic rounded-circle" 
                             style="width: 40px; height: 40px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!-- Profile Section -->
                        <li class="dropdown-item text-center">
                            <img src="<?= ($_SESSION['user']['profile_pic'] ?? 'assets/profile.jpg') . '?t=' . time(); ?>" 
                                 alt="Profile" class="rounded-circle border border-white" 
                                 style="width: 50px; height: 50px;">
                            <p class="mt-2 mb-1 fw-bold"><?= $_SESSION['user']['username'] ?></p>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <!-- Edit Profile -->
                        <li>
                            <a class="dropdown-item" href="edit_profile.php">
                                <i class="fas fa-user-edit me-2"></i> Edit Profile
                            </a>
                        </li>

                        <!-- Orders -->
                        <li>
                            <a class="dropdown-item" href="./your_order.php">
                                <i class="fas fa-box-open me-2"></i> Your Orders
                            </a>
                        </li>

                        <!-- Logout -->
                        <li>
                            <a class="dropdown-item" href="./logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

            <?php else: ?>
                <!-- If User is Not Logged In -->
                <a href="login.php" class="btn btn-outline-primary ms-3">Login</a>
                <a href="signup.php" class="btn btn-primary ms-2">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</nav>


<!-- Database Connection -->
<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "s_category"; 

// कनेक्शन बनाएं
$conn = new mysqli($servername, $username, $password, $database);

// कनेक्शन चेक करें
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// सही कॉलम (c_name) के साथ डेटा लाएं
$categoryQuery = "SELECT id, c_name FROM categoryy";
$categoryResult = $conn->query($categoryQuery);
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <!-- Navbar Toggler for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle bounceUp" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sushruta
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="./index.php">All Products</a></li>
                    <li><a class="dropdown-item" href="#">Baby's care</a></li>
                    <li><a class="dropdown-item" href="#">Drink & Supplements</a></li>
                    <li><a class="dropdown-item" href="#">Women Care</a></li>
                    <li><a class="dropdown-item" href="#">Personal care</a></li>
                    
                    
                        <?php
                        if ($categoryResult->num_rows > 0) {
                            while ($row = $categoryResult->fetch_assoc()) {
                                echo '<li><a class="dropdown-item" href="index.php?category=' . urlencode($row['c_name']) . '">' . htmlspecialchars($row['c_name']) . '</a></li>';
                            }
                        } else {
                            echo '<li><a class="dropdown-item" href="#"></a></li>';
                        }
                        ?>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link colorSwing" href="baby'sCare.php">Baby Care</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link borderBounce" href="drinks.php">Drink & Supplements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link neonPulse" href="womensCare.php">Women Care</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bounceDown" href="personalCare.php">Personal Care</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php

$conn->close();
?>

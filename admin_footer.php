<?php
session_start();

include "config.php"; // Database connection

// Fetch footer details
$query = "SELECT * FROM footer_info LIMIT 1";
$result = $conn->query($query);
$footer = $result->fetch_assoc();

// Update footer info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $facebook = $_POST['facebook'];
    $twitter = $_POST['twitter'];
    $instagram = $_POST['instagram'];
    $linkedin = $_POST['linkedin'];

    $updateQuery = "UPDATE footer_info SET address=?, phone=?, email=?, facebook=?, twitter=?, instagram=?, linkedin=? WHERE id=1";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssssss", $address, $phone, $email, $facebook, $twitter, $instagram, $linkedin);
    $stmt->execute();
    $stmt->close();
    
    header("Location: admin_footer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            flex-direction: column; /* Stack sidebar and content on small screens */
        }

        .sidebar {
            width: 100%; /* Full width on small screens */
            height: auto; /* Auto height on small screens */
            position: static; /* Static positioning on small screens */
            background: #2f5438;
            color: white;
            padding: 10px; /* Reduced padding on small screens */
        }

        @media (min-width: 768px) {
            .wrapper {
                flex-direction: row; /* Row layout on larger screens */
            }

            .sidebar {
                width: 250px;
                height: 100vh;
                position: fixed;
                padding-top: 20px;
            }
        }

        .content {
            margin-left: 0; /* Reset margin on small screens */
            padding: 10px; /* Reduced padding on small screens */
            width: 100%; /* Full width on small screens */
            margin-top: 50px;
        }

        @media (min-width: 768px) {
            .content {
                margin-left: 250px;
                padding: 20px;
                width: calc(100% - 250px);
            }
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0; /* Adjust for small screens */
            width: 100%; /* Full width on small screens */
            background: #2f5438;
            color: white;
            z-index: 1000;
        }

        @media (min-width: 768px) {
            .navbar {
                left: 250px;
                width: calc(100% - 250px);
            }
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 15px; /* Reduced padding on small screens */
            background: #fff;
        }

        .footer {
            position: static; /* Static positioning on small screens */
            width: 100%; /* Full width on small screens */
            background: #2f5438;
            color: white;
            text-align: center;
            padding: 5px; /* Reduced padding on small screens */
        }

        @media (min-width: 768px) {
            .footer {
                position: fixed;
                bottom: 0;
                left: 250px;
                width: calc(100% - 250px);
                padding: 10px;
            }
        }

        .container {
            margin-top: 60px; /* Adjusted margin on small screens */
            padding: 0 15px; /* Added padding on small screens */
        }

        @media (min-width: 768px) {
            .container {
                margin-top: 150px;
            }
        }
    </style>
</head>
<div class="wrapper">
    <div class="sidebar">
        <?php include_once 'sidebar.php'; ?>
    </div>

    <div class="content">
        <?php include_once './admin_navbar.php'; ?>

        <div class="container box mt-5">
            <div class="card p-4">
                <h2 class="mb-4 text-success">Edit Footer Information</h2>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Address</label>
                        <input type="text" name="address" class="form-control" value="<?= $footer['address'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-phone"></i> Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= $footer['phone'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $footer['email'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-facebook"></i> Facebook Link</label>
                        <input type="text" name="facebook" class="form-control" value="<?= $footer['facebook'] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-twitter"></i> Twitter Link</label>
                        <input type="text" name="twitter" class="form-control" value="<?= $footer['twitter'] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-instagram"></i> Instagram Link</label>
                        <input type="text" name="instagram" class="form-control" value="<?= $footer['instagram'] ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><i class="fab fa-linkedin"></i> LinkedIn Link</label>
                        <input type="text" name="linkedin" class="form-control" value="<?= $footer['linkedin'] ?>">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Footer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dummy order data (Replace this with actual database query)
$orders = isset($_SESSION['orders']) ? $_SESSION['orders'] : [];

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['star'])) {
    $productId = intval($_POST['product_id']);
    $rating = intval($_POST['star']);

    // Store rating in session (Replace this with database update in real project)
    $_SESSION['orders'][$productId]['rating'] = $rating;

    // Redirect to prevent form resubmission
    header("Location: your_orders.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA - Your Orders</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./additional-methods.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .order-container {
            max-width: 400px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-title {
            color: #28a745;
            font-weight: bold;
        }
        .card-text {
            font-size: 18px;
            color: #555;
        }
        .rating {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 30px;
            color: gray;
            cursor: pointer;
            transition: color 0.3s;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>

    <?php include_once "navbar.php"; ?>

    <div class="container mt-4">
        <h1 style="font-weight:700;">
            <span style="color:green; font-weight:700">Your</span> Orders
        </h1>

        <div class="row">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="col-md-4 d-flex justify-content-center">
                        <div class="card order-container">
                            <img src="<?php echo htmlspecialchars($order['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($order['name']); ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($order['name']); ?></h5>
                                <p class="card-text">Price: <strong>₹<?php echo number_format($order['price'], 2); ?></strong></p>

                                <div class="rating">
                                    <form method="post">
                                        <input type="hidden" name="product_id" value="<?php echo $order['id']; ?>">
                                        
                                        <?php for ($i = 5; $i >= 1; $i--): ?>
                                            <input type="radio" name="star" id="star<?php echo $i . '_' . $order['id']; ?>" value="<?php echo $i; ?>"
                                                <?php echo isset($order['rating']) && $order['rating'] == $i ? 'checked' : ''; ?>>
                                            <label for="star<?php echo $i . '_' . $order['id']; ?>">★</label>
                                        <?php endfor; ?>

                                        <button type="submit" class="btn btn-success btn-sm mt-3">Submit Rating</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No orders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include_once "footer.php"; ?>
    
</body>
</html>

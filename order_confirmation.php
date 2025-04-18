<?php
session_start();
include "./config.php";

// Security: Prevent direct access
define('IN_CART', true);

// Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

// Check if order_id is set
if (!isset($_SESSION['order_id'])) {
    header("Location: add_to_cart.php");
    exit();
}

$order_id = $_SESSION['order_id'];
$user_id = $_SESSION['user']['id'];

// Fetch Order Details
$stmt = $conn->prepare("SELECT o.*, p.transaction_id, p.payment_date 
                        FROM orders o 
                        LEFT JOIN payments p ON o.id = p.order_id 
                        WHERE o.id = ? AND o.user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    header("Location: add_to_cart.php");
    exit();
}

// Fetch Order Items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image 
                        FROM order_items oi 
                        JOIN all_data_products p ON oi.product_id = p.id 
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = [];

while ($row = $result->fetch_assoc()) {
    $row['image'] = !empty($row['image']) ? 'assets/uploads/' . $row['image'] : 'assets/images/default-product.jpg';
    $order_items[] = $row;
}

// Calculate estimated delivery date (3-5 business days from now)
$delivery_date = date('Y-m-d', strtotime('+3 to 5 weekdays'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .confirmation-container {
            max-width: 800px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .summary {
            background: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-step {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-step::before {
            content: '';
            position: absolute;
            left: -30px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #198754;
            border: 3px solid #fff;
        }
        .timeline-step.completed::before {
            background: #198754;
        }
        .timeline-step.current::before {
            background: #ffc107;
        }
        .timeline-step.pending::before {
            background: #dee2e6;
        }
    </style>
</head>
<body>
    <?php include_once "navbar.php"; ?>

    <div class="container my-5">
        <div class="confirmation-container mx-auto">
            <div class="text-center mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                <h2 class="mt-3">Order Confirmed!</h2>
                <p class="lead">Thank you for your purchase</p>
                <p>Your order #<?php echo $order_id; ?> has been placed successfully.</p>
                
                <?php if ($order['payment_method'] === 'online_payment'): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-credit-card"></i> Payment of ₹<?php echo number_format($order['total_amount'], 2); ?> was successful.
                        <?php if (!empty($order['transaction_id'])): ?>
                            <br>Transaction ID: <?php echo $order['transaction_id']; ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="bi bi-cash"></i> You chose Cash on Delivery. Please keep ₹<?php echo number_format($order['total_amount'], 2); ?> ready for payment upon delivery.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-truck"></i> Delivery Information</h5>
                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="mb-1"><strong><?php echo htmlspecialchars($order['shipping_name']); ?></strong></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' - ' . $order['shipping_pincode']); ?></p>
                            <p class="mb-1">Phone: <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                            <p class="mb-0">Email: <?php echo htmlspecialchars($order['shipping_email']); ?></p>
                        </div>
                    </div>
                    
                    <h5><i class="bi bi-clock-history"></i> Order Status</h5>
                    <div class="timeline">
                        <div class="timeline-step <?php echo $order['status'] !== 'pending' ? 'completed' : 'current'; ?>">
                            <h6>Order Placed</h6>
                            <p class="text-muted small mb-0"><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
                        </div>
                        <div class="timeline-step <?php echo $order['status'] === 'processing' ? 'current' : ($order['status'] === 'shipped' || $order['status'] === 'delivered' ? 'completed' : 'pending'); ?>">
                            <h6>Processing</h6>
                            <p class="text-muted small mb-0">We're preparing your order</p>
                        </div>
                        <div class="timeline-step <?php echo $order['status'] === 'shipped' ? 'current' : ($order['status'] === 'delivered' ? 'completed' : 'pending'); ?>">
                            <h6>Shipped</h6>
                            <p class="text-muted small mb-0">Estimated: <?php echo date('d M Y', strtotime($delivery_date)); ?></p>
                        </div>
                        <div class="timeline-step <?php echo $order['status'] === 'delivered' ? 'completed' : 'pending'; ?>">
                            <h6>Delivered</h6>
                            <p class="text-muted small mb-0">Your order will arrive soon</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h5><i class="bi bi-receipt"></i> Order Summary</h5>
                    <div class="summary mb-4">
                        <div class="order-items" style="max-height: 200px; overflow-y: auto;">
                            <?php foreach ($order_items as $item): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             onerror="this.onerror=null; this.src='assets/images/default-product.jpg';"
                                             class="product-img me-3">
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <small class="text-muted">₹<?php echo number_format($item['price'], 2); ?> × <?php echo $item['quantity']; ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹<?php echo number_format($order['total_amount'] + $order['discount_amount'], 2); ?></span>
                            </div>
                            
                            <?php if ($order['discount_amount'] > 0): ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Discount:</span>
                                    <span class="text-danger">-₹<?php echo number_format($order['discount_amount'], 2); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>FREE</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <h5>Total:</h5>
                                <h5>₹<?php echo number_format($order['total_amount'], 2); ?></h5>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="order_details.php?id=<?php echo $order_id; ?>" class="btn btn-outline-primary">
                            <i class="bi bi-file-text"></i> View Order Details
                        </a>
                        <a href="products.php" class="btn btn-success">
                            <i class="bi bi-bag"></i> Continue Shopping
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted small">Need help? <a href="contact.php">Contact us</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "footer.php"; ?>
</body>
</html>
<?php
// Clear the order_id from session after displaying
unset($_SESSION['order_id']);
?>
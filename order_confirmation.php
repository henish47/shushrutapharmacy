<?php
session_start();
include "./config.php";

// Security: Prevent direct access
define('IN_CART', true);

// Check if user is logged in and has a valid order ID
if (!isset($_SESSION['user']['id']) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['order_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$order_id = $_SESSION['order_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT o.*, u.email as user_email 
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        WHERE o.id = ? AND o.user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Order not found or doesn't belong to user
    unset($_SESSION['order_id']);
    $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> Order not found.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
    header("Location: index.php");
    exit();
}

$order = $result->fetch_assoc();
$payment_details = json_decode($order['payment_details'], true);

// Fetch order items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image 
                        FROM order_items oi
                        JOIN all_data_products p ON oi.product_id = p.id
                        WHERE oi.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Format dates
$order_date = date('d M Y, h:i A', strtotime($order['created_at']));
$estimated_delivery_date = date('d M Y', strtotime($order['created_at'] . ' + 3 days'));

// Clear the order ID from session so the confirmation only shows once
unset($_SESSION['order_id']);
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
        .success-icon {
            font-size: 5rem;
            color: #198754;
        }
        .order-item-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .timeline {
            position: relative;
            padding-left: 50px;
            margin: 30px 0;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-step {
            position: relative;
            margin-bottom: 30px;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            position: absolute;
            left: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateX(-50%);
            margin-left: 15px;
        }
        .timeline-content {
            padding-left: 20px;
        }
        .payment-method-icon {
            font-size: 2rem;
            margin-right: 15px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include_once "navbar.php"; ?>
    
    <div class="container my-5">
        <div class="confirmation-container mx-auto text-center">
            <div class="success-icon mb-4">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h2 class="mb-3">Thank You for Your Order!</h2>
            <p class="lead mb-4">Your order has been placed successfully. We've sent a confirmation email to <strong><?php echo htmlspecialchars($order['user_email']); ?></strong>.</p>
            
            <div class="alert alert-success mb-4">
                <h5 class="mb-0">Order #<?php echo $order_id; ?></h5>
                <p class="mb-0">Placed on <?php echo $order_date; ?></p>
            </div>
            
            <div class="d-flex justify-content-center gap-3 mb-5">
                <a href="order_details.php?id=<?php echo $order_id; ?>" class="btn btn-outline-primary">
                    <i class="bi bi-receipt"></i> View Order Details
                </a>
                <a href="products.php" class="btn btn-primary">
                    <i class="bi bi-bag"></i> Continue Shopping
                </a>
            </div>
            
            <div class="row text-start">
                <!-- Order Summary -->
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3 border-bottom pb-2">Order Summary</h5>
                    
                    <div class="order-items mb-3" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($order_items as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo !empty($item['image']) ? 'assets/uploads/' . htmlspecialchars($item['image']) : 'assets/images/default-product.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="order-item-img me-3">
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Qty: <?php echo $item['quantity']; ?></small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <strong>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="border-top pt-3">
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
                        
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top fw-bold">
                            <span>Total:</span>
                            <span>₹<?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping & Payment Info -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Shipping Information</h5>
                        <p class="mb-1"><strong><?php echo htmlspecialchars($order['shipping_name']); ?></strong></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                        <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city']) . ', ' . htmlspecialchars($order['shipping_state']) . ' - ' . htmlspecialchars($order['shipping_pincode']); ?></p>
                        <p class="mb-0">Phone: <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="mb-3 border-bottom pb-2">Payment Method</h5>
                        <div class="d-flex align-items-center">
                            <?php if ($order['payment_method'] === 'online_payment'): ?>
                                <i class="bi bi-credit-card payment-method-icon"></i>
                                <div>
                                    <p class="mb-1"><strong>Online Payment</strong></p>
                                    <?php if ($payment_details['payment_type'] === 'card'): ?>
                                        <p class="mb-0 small text-muted">Card ending with <?php echo substr($payment_details['card_number'], -4); ?></p>
                                    <?php elseif ($payment_details['payment_type'] === 'upi'): ?>
                                        <p class="mb-0 small text-muted">UPI ID: <?php echo htmlspecialchars($payment_details['upi_id']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <i class="bi bi-cash payment-method-icon"></i>
                                <div>
                                    <p class="mb-1"><strong>Cash on Delivery</strong></p>
                                    <p class="mb-0 small text-muted">Pay when you receive your order</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="mb-3 border-bottom pb-2">Delivery Status</h5>
                        <div class="timeline">
                            <div class="timeline-step">
                                <div class="timeline-icon">
                                    <i class="bi bi-check"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Confirmed</h6>
                                    <p class="small text-muted mb-0"><?php echo $order_date; ?></p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon" style="background: #6c757d;">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Processing</h6>
                                    <p class="small text-muted mb-0">We're preparing your order</p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon" style="background: #6c757d;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Shipped</h6>
                                    <p class="small text-muted mb-0">Estimated by <?php echo $estimated_delivery_date; ?></p>
                                </div>
                            </div>
                            <div class="timeline-step">
                                <div class="timeline-icon" style="background: #6c757d;">
                                    <i class="bi bi-house-door"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Delivered</h6>
                                    <p class="small text-muted mb-0">Will be updated when delivered</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-top">
                <h5 class="mb-3">Need Help?</h5>
                <p class="mb-4">If you have any questions about your order, please contact our customer support.</p>
                <a href="contact.php" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-headset"></i> Contact Support
                </a>
                <a href="faq.php" class="btn btn-outline-secondary">
                    <i class="bi bi-question-circle"></i> FAQ
                </a>
            </div>
        </div>
    </div>
    
    <?php include_once "footer.php"; ?>
</body>
</html>
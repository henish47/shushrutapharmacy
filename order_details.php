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

// Check if order_id is provided
if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
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
    header("Location: orders.php");
    exit();
}

// Fetch Order Items
$stmt = $conn->prepare("SELECT oi.*, p.name, p.image, p.description 
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

// Decode payment details
$payment_details = json_decode($order['payment_details'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .order-container {
            max-width: 1000px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 5px;
            border: 1px solid #ddd;
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
        <div class="order-container mx-auto">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Order #<?php echo $order_id; ?></h2>
                <span class="status-badge 
                    <?php echo $order['status'] === 'pending' ? 'bg-warning' : 
                          ($order['status'] === 'processing' ? 'bg-info' : 
                          ($order['status'] === 'shipped' ? 'bg-primary' : 
                          ($order['status'] === 'delivered' ? 'bg-success' : 'bg-secondary'))); ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-truck"></i> Shipping Information</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong><?php echo htmlspecialchars($order['shipping_name']); ?></strong></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                            <p class="mb-1"><?php echo htmlspecialchars($order['shipping_city'] . ', ' . $order['shipping_state'] . ' - ' . $order['shipping_pincode']); ?></p>
                            <p class="mb-1">Phone: <?php echo htmlspecialchars($order['shipping_phone']); ?></p>
                            <p class="mb-0">Email: <?php echo htmlspecialchars($order['shipping_email']); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment Information</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Method:</strong> 
                                <?php echo $order['payment_method'] === 'online_payment' ? 'Online Payment' : 'Cash on Delivery'; ?>
                            </p>
                            
                            <?php if ($order['payment_method'] === 'online_payment'): ?>
                                <?php if (!empty($order['transaction_id'])): ?>
                                    <p class="mb-1"><strong>Transaction ID:</strong> <?php echo $order['transaction_id']; ?></p>
                                <?php endif; ?>
                                <?php if (!empty($order['payment_date'])): ?>
                                    <p class="mb-1"><strong>Paid on:</strong> <?php echo date('d M Y, h:i A', strtotime($order['payment_date'])); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($payment_details['payment_type'])): ?>
                                    <p class="mb-1"><strong>Payment Type:</strong> 
                                        <?php echo ucfirst($payment_details['payment_type']); ?>
                                    </p>
                                    <?php if ($payment_details['payment_type'] === 'card' && !empty($payment_details['card_number'])): ?>
                                        <p class="mb-1"><strong>Card:</strong> **** **** **** <?php echo $payment_details['card_number']; ?></p>
                                    <?php elseif ($payment_details['payment_type'] === 'upi' && !empty($payment_details['upi_id'])): ?>
                                        <p class="mb-0"><strong>UPI ID:</strong> <?php echo htmlspecialchars($payment_details['upi_id']); ?></p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="mb-0">Pay with cash when your order is delivered.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Order Status</h5>
                </div>
                <div class="card-body">
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
                            <p class="text-muted small mb-0">
                                <?php if ($order['status'] === 'shipped' || $order['status'] === 'delivered'): ?>
                                    Shipped on <?php echo date('d M Y', strtotime($order['updated_at'])); ?>
                                <?php else: ?>
                                    Estimated: <?php echo date('d M Y', strtotime('+3 to 5 weekdays', strtotime($order['created_at']))); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="timeline-step <?php echo $order['status'] === 'delivered' ? 'completed' : 'pending'; ?>">
                            <h6>Delivered</h6>
                            <p class="text-muted small mb-0">
                                <?php if ($order['status'] === 'delivered'): ?>
                                    Delivered on <?php echo date('d M Y', strtotime($order['updated_at'])); ?>
                                <?php else: ?>
                                    Your order will arrive soon
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-3"><i class="bi bi-cart-check"></i> Order Items</h5>
            <div class="table-responsive mb-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             onerror="this.onerror=null; this.src='assets/images/default-product.jpg';"
                                             class="product-img me-3">
                                        <div>
                                            <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                            <small class="text-muted"><?php echo substr(htmlspecialchars($item['description']), 0, 50); ?>...</small>
                                        </div>
                                    </div>
                                </td>
                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
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
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="orders.php" class="btn btn-outline-secondary me-md-2">
                    <i class="bi bi-arrow-left"></i> Back to Orders
                </a>
                <a href="products.php" class="btn btn-success">
                    <i class="bi bi-bag"></i> Shop Again
                </a>
            </div>
        </div>
    </div>

    <?php include_once "footer.php"; ?>
</body>
</html>
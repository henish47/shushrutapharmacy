<?php
session_start();
include "./config.php";

// Security: Prevent direct access
define('IN_CART', true);

// ✅ Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    $_SESSION['alert'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> Please log in to proceed to checkout.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
    header("Location: login.php");
    exit();
}

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user']['id'];
$cart_items = [];
$subtotal = 0;
$discount_percentage = 0;
$valid_coupons = [
    "DISCOUNT10" => 10,
    "DISCOUNT20" => 20,
    "DISCOUNT30" => 30
];

// ✅ Fetch User Details (fixed from orders to users table)
$stmt = $conn->prepare("SELECT name, email, phone, address, city, state, pincode FROM orders WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ✅ Fetch Cart Items
$stmt = $conn->prepare("SELECT c.product_id, p.name, p.price, p.image, c.quantity 
                        FROM cart c 
                        JOIN all_data_products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['image'] = !empty($row['image']) ? 'assets/uploads/' . $row['image'] : 'assets/images/default-product.jpg';
    $cart_items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
}

// ✅ Apply Discount if Coupon is Set
if (isset($_SESSION['coupon_code']) && array_key_exists($_SESSION['coupon_code'], $valid_coupons)) {
    $discount_percentage = $valid_coupons[$_SESSION['coupon_code']];
}

// ✅ Calculate Final Total
$discount_amount = ($subtotal * $discount_percentage) / 100;
$total = $subtotal - $discount_amount;

// ✅ Handle Checkout Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    // Validate required fields
    $required_fields = ['name', 'email', 'phone', 'address', 'city', 'state', 'pincode', 'payment_method'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Please fill all required fields.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
            header("Location: checkout.php");
            exit();
        }
    }
    
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $pincode = trim($_POST['pincode']);
    $payment_method = $_POST['payment_method'];
    $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
    
    // Additional payment details
    $payment_details = [];
    if ($payment_method === 'online_payment') {
        $payment_details['payment_type'] = $_POST['payment_type'] ?? '';
        $payment_details['card_number'] = isset($_POST['card_number']) ? str_replace(' ', '', $_POST['card_number']) : '';
        $payment_details['card_expiry'] = $_POST['card_expiry'] ?? '';
        $payment_details['card_cvv'] = $_POST['card_cvv'] ?? '';
        $payment_details['upi_id'] = $_POST['upi_id'] ?? '';
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Invalid email address.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
        header("Location: checkout.php");
        exit();
    }
    
    // Validate phone number (Indian format)
    if (!preg_match('/^[6-9]\d{9}$/', $phone)) {
        $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Please enter a valid 10-digit Indian phone number.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
        header("Location: checkout.php");
        exit();
    }
    
    // Validate pincode (Indian format)
    if (!preg_match('/^\d{6}$/', $pincode)) {
        $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> Please enter a valid 6-digit pincode.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
        header("Location: checkout.php");
        exit();
    }
    
    // Validate payment details if online payment
    if ($payment_method === 'online_payment') {
        if (empty($payment_details['payment_type'])) {
            $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Please select a payment type.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
            header("Location: checkout.php");
            exit();
        }
        
        // Validate card details if card payment
        if ($payment_details['payment_type'] === 'card') {
            if (empty($payment_details['card_number']) || !preg_match('/^\d{16}$/', $payment_details['card_number'])) {
                $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> Please enter a valid 16-digit card number.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>';
                header("Location: checkout.php");
                exit();
            }
            
            if (empty($payment_details['card_expiry']) || !preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{2})$/', $payment_details['card_expiry'])) {
                $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> Please enter a valid expiry date (MM/YY).
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>';
                header("Location: checkout.php");
                exit();
            }
            
            if (empty($payment_details['card_cvv']) || !preg_match('/^\d{3,4}$/', $payment_details['card_cvv'])) {
                $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> Please enter a valid CVV (3 or 4 digits).
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                      </div>';
                header("Location: checkout.php");
                exit();
            }
        }
        
        // Validate UPI ID if UPI payment
        if ($payment_details['payment_type'] === 'upi' && !preg_match('/^[\w.-]+@[\w]+$/', $payment_details['upi_id'])) {
            $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Please enter a valid UPI ID (e.g., example@upi).
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
            header("Location: checkout.php");
            exit();
        }
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, discount_amount, payment_method, payment_details, shipping_name, shipping_email, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_pincode, notes, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $payment_details_json = json_encode($payment_details);
        $stmt->bind_param("iddssssssssss", $user_id, $total, $discount_amount, $payment_method, $payment_details_json, $name, $email, $phone, $address, $city, $state, $pincode, $notes);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        
        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        // Clear coupon if used
        if (isset($_SESSION['coupon_code'])) {
            unset($_SESSION['coupon_code']);
        }
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to order confirmation
        $_SESSION['order_id'] = $order_id;
        header("Location: order_confirmation.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        
        // Log the error
        error_log("Order processing error: " . $e->getMessage());
        
        $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> There was a problem processing your order. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
        header("Location: checkout.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .checkout-container {
            max-width: 1200px;
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
        .form-control:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
        .payment-method {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-method:hover {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.05);
        }
        .payment-method.selected {
            border-color: #198754;
            background-color: rgba(25, 135, 84, 0.1);
        }
        .payment-method input[type="radio"] {
            margin-right: 10px;
        }
        .payment-details {
            display: none;
            padding: 15px;
            margin-top: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .payment-details.active {
            display: block;
        }
        .card-input-container {
            position: relative;
        }
        .card-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 25px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .card-number {
            padding-right: 60px;
        }
    </style>
</head>
<body>
    <?php include_once "navbar.php"; ?>
    
    <!-- Display alerts if any -->
    <?php if (isset($_SESSION['alert'])): ?>
        <div class="container mt-3">
            <?php echo $_SESSION['alert']; unset($_SESSION['alert']); ?>
        </div>
    <?php endif; ?>

    <div class="container my-5">
        <?php if (!empty($cart_items)) : ?>
            <form action="checkout.php" method="POST" class="checkout-container mx-auto" id="checkoutForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="row">
                    <!-- Shipping Information -->
                    <div class="col-md-7">
                        <h2 class="mb-4"><i class="bi bi-truck"></i> Shipping Information</h2>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" 
                                       pattern="[6-9]\d{9}" title="Enter a valid 10-digit Indian phone number" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" 
                                       value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="state" name="state" 
                                       value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="pincode" name="pincode" 
                                       value="<?php echo htmlspecialchars($user['pincode'] ?? ''); ?>" 
                                       pattern="\d{6}" title="Enter a valid 6-digit pincode" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Any special instructions for your order..."></textarea>
                        </div>
                        
                        <h2 class="mb-4 mt-5"><i class="bi bi-credit-card"></i> Payment Method</h2>
                        
                        <div class="payment-method">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked required>
                                <label class="form-check-label" for="cod">
                                    <strong>Cash on Delivery (COD)</strong>
                                </label>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Pay in cash when your order is delivered.</p>
                        </div>
                        
                        <div class="payment-method">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="online" value="online_payment" required>
                                <label class="form-check-label" for="online">
                                    <strong>Online Payment</strong>
                                </label>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Pay securely with UPI, Credit/Debit Card, or Net Banking.</p>
                            
                            <div class="payment-details" id="onlinePaymentDetails">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type" id="cardPayment" value="card">
                                        <label class="form-check-label" for="cardPayment">Credit/Debit Card</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_type" id="upiPayment" value="upi">
                                        <label class="form-check-label" for="upiPayment">UPI</label>
                                    </div>
                                </div>
                                
                                <div class="card-details">
                                    <div class="mb-3 card-input-container">
                                        <label for="cardNumber" class="form-label">Card Number</label>
                                        <input type="text" class="form-control card-number" id="cardNumber" name="card_number" 
                                               placeholder="1234 5678 9012 3456" maxlength="19">
                                        <div class="card-icon" id="cardTypeIcon"></div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cardExpiry" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="cardExpiry" name="card_expiry" 
                                                   placeholder="MM/YY" maxlength="5">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cardCvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cardCvv" name="card_cvv" 
                                                   placeholder="123" maxlength="4">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="upi-details" style="display: none;">
                                    <div class="mb-3">
                                        <label for="upiId" class="form-label">UPI ID</label>
                                        <input type="text" class="form-control" id="upiId" name="upi_id" 
                                               placeholder="example@upi">
                                    </div>
                                    <p class="text-muted small">Popular UPI apps: Google Pay, PhonePe, Paytm, BHIM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="col-md-5">
                        <h2 class="mb-4"><i class="bi bi-cart-check"></i> Order Summary</h2>
                        
                        <div class="summary">
                            <h5 class="mb-3">Your Items</h5>
                            <div class="order-items" style="max-height: 300px; overflow-y: auto;">
                                <?php foreach ($cart_items as $item): ?>
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
                            
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>₹<?php echo number_format($subtotal, 2); ?></span>
                                </div>
                                
                                <?php if ($discount_percentage > 0): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount (<?php echo $discount_percentage; ?>%):</span>
                                        <span class="text-danger">-₹<?php echo number_format($discount_amount, 2); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>FREE</span>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                    <h5>Total:</h5>
                                    <h5>₹<?php echo number_format($total, 2); ?></h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="" id="termsCheck" required>
                            <label class="form-check-label" for="termsCheck">
                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                            </label>
                        </div>
                        
                        <div class="d-grid mt-3">
                            <a href="order_confirmation.php">
                            <button type="submit" name="place_order" class="btn btn-success btn-lg py-3">
                                <i class="bi bi-check-circle"></i> Place Order
                            </button>
                            </a>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="add_to_cart.php" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Return to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        <?php else: ?>
            <div class="checkout-container mx-auto text-center py-5">
                <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
                <h3 class="mt-3">Your cart is empty</h3>
                <p class="text-muted">There are no items in your cart to checkout.</p>
                <a href="products.php" class="btn btn-primary mt-3">
                    <i class="bi bi-bag"></i> Browse Products
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Order Acceptance</h6>
                    <p>Your receipt of an electronic or other form of order confirmation does not signify our acceptance of your order, nor does it constitute confirmation of our offer to sell. Sushruta Pharmacy reserves the right at any time after receipt of your order to accept or decline your order for any reason.</p>
                    
                    <h6>2. Pricing and Payment</h6>
                    <p>All prices are in Indian Rupees (₹). We accept payments via Credit/Debit Cards, UPI, and Cash on Delivery. For online payments, your card details are securely processed through our payment gateway and are not stored by us.</p>
                    
                    <h6>3. Shipping Policy</h6>
                    <p>We aim to dispatch all orders within 24-48 hours. Delivery times may vary depending on your location. Prescription medicines will only be dispatched after verification by our pharmacists.</p>
                    
                    <h6>4. Returns and Refunds</h6>
                    <p>Due to the nature of pharmaceutical products, we cannot accept returns of medicines unless they are damaged or defective. In such cases, please contact us within 24 hours of delivery.</p>
                    
                    <h6>5. Privacy Policy</h6>
                    <p>We respect your privacy and are committed to protecting your personal information. Your data will only be used to process your order and provide you with the best possible service.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Highlight selected payment method
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('selected');
                });
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Show/hide payment details
                if (radio.id === 'online') {
                    document.getElementById('onlinePaymentDetails').classList.add('active');
                } else {
                    document.getElementById('onlinePaymentDetails').classList.remove('active');
                }
            });
            
            // Check if this method is selected by default
            const radio = method.querySelector('input[type="radio"]');
            if (radio.checked) {
                method.classList.add('selected');
                if (radio.id === 'online') {
                    document.getElementById('onlinePaymentDetails').classList.add('active');
                }
            }
        });

        // Toggle between card and UPI payment details
        document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'card') {
                    document.querySelector('.card-details').style.display = 'block';
                    document.querySelector('.upi-details').style.display = 'none';
                } else if (this.value === 'upi') {
                    document.querySelector('.card-details').style.display = 'none';
                    document.querySelector('.upi-details').style.display = 'block';
                }
            });
        });

        // Format card number with spaces
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            // Remove all non-digits
            let value = this.value.replace(/\D/g, '');
            
            // Add space after every 4 digits
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            
            // Update the input value
            this.value = value;
            
            // Detect card type and show icon
            detectCardType(value.replace(/\s/g, ''));
        });

        // Format expiry date
        document.getElementById('cardExpiry').addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            this.value = value;
        });

        // Detect card type and show appropriate icon
        function detectCardType(cardNumber) {
            const cardIcon = document.getElementById('cardTypeIcon');
            cardIcon.style.backgroundImage = 'none';
            
            // Visa
            if (/^4/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/visa.png")';
            } 
            // Mastercard
            else if (/^5[1-5]/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/mastercard.png")';
            } 
            // American Express
            else if (/^3[47]/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/amex.png")';
            } 
            // Discover
            else if (/^6(?:011|5)/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/discover.png")';
            } 
            // Diners Club
            else if (/^3(?:0[0-5]|[68])/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/diners.png")';
            } 
            // JCB
            else if (/^35(2[89]|[3-8])/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/jcb.png")';
            } 
            // Maestro
            else if (/^(5018|5020|5038|6304|6759|676[1-3])/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/maestro.png")';
            } 
            // RuPay
            else if (/^6[0-9]/.test(cardNumber)) {
                cardIcon.style.backgroundImage = 'url("assets/images/rupay.png")';
            }
        }

        // Form validation before submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (paymentMethod === 'online_payment') {
                const paymentType = document.querySelector('input[name="payment_type"]:checked');
                
                if (!paymentType) {
                    e.preventDefault();
                    alert('Please select a payment type (Card or UPI)');
                    return;
                }
                
                if (paymentType.value === 'card') {
                    const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
                    const cardExpiry = document.getElementById('cardExpiry').value;
                    const cardCvv = document.getElementById('cardCvv').value;
                    
                    if (cardNumber.length !== 16 || !/^\d+$/.test(cardNumber)) {
                        e.preventDefault();
                        alert('Please enter a valid 16-digit card number');
                        return;
                    }
                    
                    if (!/^(0[1-9]|1[0-2])\/?([0-9]{2})$/.test(cardExpiry)) {
                        e.preventDefault();
                        alert('Please enter a valid expiry date in MM/YY format');
                        return;
                    }
                    
                    if (!/^\d{3,4}$/.test(cardCvv)) {
                        e.preventDefault();
                        alert('Please enter a valid CVV (3 or 4 digits)');
                        return;
                    }
                } else if (paymentType.value === 'upi') {
                    const upiId = document.getElementById('upiId').value;
                    
                    if (!/^[\w.-]+@[\w]+$/.test(upiId)) {
                        e.preventDefault();
                        alert('Please enter a valid UPI ID (e.g., example@upi)');
                        return;
                    }
                }
            }
            
            if (!document.getElementById('termsCheck').checked) {
                e.preventDefault();
                alert('Please agree to the Terms and Conditions');
            }
        });
    </script>
</body>
</html>
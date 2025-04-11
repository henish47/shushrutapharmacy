<?php
session_start();
include "./config.php";

// Security: Prevent direct access
define('IN_CART', true);

// ✅ Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    $_SESSION['alert'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> Please log in to add items to your cart.
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

// ✅ Handle Adding a Product to the Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && !isset($_POST['action'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    $product_id = (int)$_POST['product_id'];
    
    // Verify product exists
    $stmt = $conn->prepare("SELECT id FROM all_data_products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    if (!$stmt->get_result()->num_rows) {
        die("Invalid product");
    }

    // Check if product already exists in cart
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Product exists, increase quantity
        $quantity = $row['quantity'] + 1;
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $stmt->execute();
    } else {
        // Insert new product into cart
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
    }

    $_SESSION['alert'] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Product added to cart.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';

    header("Location: add_to_cart.php");
    exit();
}

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

// ✅ Handle Quantity Updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && isset($_POST['product_id'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    // Get current quantity
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $quantity = $row['quantity'];

    if ($action == "increase") {
        $quantity++;
    } elseif ($action == "decrease" && $quantity > 1) {
        $quantity--;
    }

    // Update quantity in database
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    $stmt->execute();

    header("Location: add_to_cart.php");
    exit();
}

// ✅ Handle Removing from Cart
if (isset($_GET['remove'])) {
    // Validate CSRF token
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    $product_id = (int)$_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Removed!</strong> Product has been removed from your cart.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
    header("Location: add_to_cart.php");
    exit();
}

// ✅ Handle Coupon Application
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_coupon'])) {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    if (isset($_POST['coupon_code'])) {
        $coupon_code = trim($_POST['coupon_code']);
        
        if (array_key_exists($coupon_code, $valid_coupons)) {
            $_SESSION['coupon_code'] = $coupon_code;
            $_SESSION['alert'] = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> Coupon applied successfully.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
        } else {
            $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> Invalid coupon code.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>';
        }
    } elseif (isset($_POST['remove_coupon'])) {
        unset($_SESSION['coupon_code']);
        $_SESSION['alert'] = '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong>Info!</strong> Coupon removed.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                              </div>';
    }

    header("Location: add_to_cart.php");
    exit();
}

// ✅ Apply Discount if Coupon is Set
if (isset($_SESSION['coupon_code']) && array_key_exists($_SESSION['coupon_code'], $valid_coupons)) {
    $discount_percentage = $valid_coupons[$_SESSION['coupon_code']];
}

// ✅ Calculate Final Total
$discount_amount = ($subtotal * $discount_percentage) / 100;
$total = $subtotal - $discount_amount;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Sushruta Pharmacy</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .cart-container {
            max-width: 1000px;
            background: #fff;
            padding: 20px;
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
        .btn-outline-secondary, .btn-danger {
            border-radius: 5px;
        }
        .summary {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
        }
        .quantity-control {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
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
        <div class="cart-container mx-auto">
            <h1 class="mb-4 text-center"><i class="bi bi-cart"></i> Shopping Cart</h1>

            <?php if (!empty($cart_items)) : ?>

                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead class="table-success">
                            <tr>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             onerror="this.onerror=null; this.src='assets/images/default-product.jpg';"
                                             class="product-img">
                                    </td>
                                    <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                    <td>
                                        <form action="add_to_cart.php" method="POST" class="d-flex justify-content-center">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary btn-sm quantity-control">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="text" class="form-control text-center mx-2" value="<?php echo $item['quantity']; ?>" readonly style="width: 50px;">
                                            <button type="submit" name="action" value="increase" class="btn btn-outline-secondary btn-sm quantity-control">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <a href="add_to_cart.php?remove=<?php echo $item['product_id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="bi bi-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Coupon Code Section -->
                <form method="POST" class="mt-3">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div class="d-flex align-items-center">
                        <select class="form-select me-2" name="coupon_code">
                            <option value="">Select a Coupon</option>
                            <option value="DISCOUNT10" <?php echo (isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] === 'DISCOUNT10' ? 'selected' : ''); ?>>10% Off (DISCOUNT10)</option>
                            <option value="DISCOUNT20" <?php echo (isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] === 'DISCOUNT20' ? 'selected' : ''); ?>>20% Off (DISCOUNT20)</option>
                            <option value="DISCOUNT30" <?php echo (isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] === 'DISCOUNT30' ? 'selected' : ''); ?>>30% Off (DISCOUNT30)</option>
                        </select>
                        <?php if (isset($_SESSION['coupon_code'])): ?>
                            <button type="submit" name="remove_coupon" class="btn btn-danger me-2">Remove</button>
                        <?php endif; ?>
                        <button type="submit" name="apply_coupon" class="btn btn-success">Apply</button>
                    </div>
                </form>

                <!-- Cart Summary -->
                <div class="summary mt-4">
                    <p><strong>Subtotal:</strong> ₹<?php echo number_format($subtotal, 2); ?></p>
                    <?php if ($discount_percentage > 0) : ?>
                        <p><strong>Discount (<?php echo $discount_percentage; ?>%):</strong> -₹<?php echo number_format($discount_amount, 2); ?></p>
                        <p><strong>Coupon Code:</strong> <?php echo $_SESSION['coupon_code']; ?></p>
                    <?php endif; ?>
                    <p class="total-amount"><strong>Total:</strong> ₹<?php echo number_format($total, 2); ?></p>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                    <a href="checkout.php" class="btn btn-primary">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </a>
                </div>

            <?php else : ?>
                <div class="text-center py-4">
                    <i class="bi bi-cart-x" style="font-size: 3rem; color: #6c757d;"></i>
                    <h4 class="mt-3">Your cart is empty</h4>
                    <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                    <a href="products.php" class="btn btn-primary mt-3">
                        <i class="bi bi-bag"></i> Browse Products
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
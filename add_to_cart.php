<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $productName = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $productPrice = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
    $productImage = filter_input(INPUT_POST, 'product_image', FILTER_SANITIZE_URL);

    if ($productId && $productName && $productPrice) {
        // Check if the product is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['id'] === $productId) {
                $cartItem['quantity']++;
                $found = true;
                break;
            }
        }

        // If not found, add new product to the cart
        if (!$found) {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => 1,
                'image' => !empty($productImage) ? $productImage : 'default.jpg', // Use default image if empty
            ];
        }

        // Check if the request is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(["status" => "success", "message" => "Product added to cart"]);
            exit;
        } else {
            header("Location: add_to_cart.php"); // Redirect to cart page if not AJAX
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid product data"]);
        exit;
    }
}

// Normal page rendering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body>
    <?php include_once "navbar.php"; ?>
    <?php include_once "hero.php"; ?>

    <div class="container my-5">
        <h1 class="mb-4">Shopping Cart</h1>
        <?php if (!empty($_SESSION['cart'])) : ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
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
                        <?php foreach ($_SESSION['cart'] as $id => $item) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>
    <img src="<?php echo htmlspecialchars($item['image']); ?>" 
         alt="<?php echo htmlspecialchars($item['name']); ?>" 
         class="img-fluid" 
         style="width: 100px; height: auto;">
</td>

                                <td>₹<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-from-cart" data-id="<?php echo $id; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <a href="billing.php" class="btn btn-primary">Proceed to Billing</a>
        <?php else : ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <?php include_once "footer.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".add-to-cart-form").on("submit", function(e) {
                e.preventDefault(); // Prevent default form submission

                let form = $(this);
                let formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: "POST",
                    url: "add_to_cart.php", // Using the same file for AJAX
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Product added to cart! 🛒");
                            location.reload(); // Reload the cart page
                        } else {
                            alert("Failed to add product. ❌");
                        }
                    },
                    error: function() {
                        alert("Something went wrong. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>

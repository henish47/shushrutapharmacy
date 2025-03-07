<?php
session_start();


// Sample product data (ensure the image paths are correct)
$products = [
    1 => ["id" => 1, "name" => "Baby Lotion", "price" => 1065, "image" => "./assets/Baby's care/image_1.png"],
    2 => ["id" => 2, "name" => "Baby Shampoo", "price" => 778, "image" => "./assets/Baby's care/image_2.png"],
    3 => ["id" => 3, "name" => "Baby Powder", "price" => 655, "image" => "./assets/Baby's care/image_3.png"],
    // You can add more products here.
];
?>


<?php include "navbar.php"; ?>

<h1>Shopping Cart</h1>
<div class="container my-5">
    <h1 class="mb-4">Shopping Cart</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">Product</th>
                    <th class="text-center">Image</th>
                    <th class="text-center">Price</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_amount = 0;
                foreach ($_SESSION['cart'] as $product_id => $item): 
                    $total_amount += $item['price'] * $item['quantity'];
                ?>
                    <tr>
                        <td class="align-middle"><?php echo $item['name']; ?></td>
                        <td class="align-middle text-center">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid" style="width: 100px; height: auto;">
                        </td>
                        <td class="align-middle">₹<?php echo number_format($item['price'], 2); ?></td>
                        <td class="align-middle"><?php echo $item['quantity']; ?></td>
                        <td class="align-middle">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                        <td class="align-middle text-center">
                            <a href="?remove=<?php echo $product_id; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="my-4">
        <h4>Total</h4>
        <p><strong>Total Amount: ₹<?php echo number_format($total_amount, 2); ?></strong></p>
    </div>

    <div class="container">
        <a href="billing.php" class="btn btn-primary">Proceed to Billing</a>
    </div>
</div>

<?php
// Remove product from cart
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}
?>

<?php include_once "footer.php"; ?>
</body>
</html>

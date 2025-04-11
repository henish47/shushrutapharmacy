<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA - Your Orders</title>
   
    <link rel="stylesheet" href="./bootstrap.min.css">
    <script src="./bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./jquery/bootstrap-icons.css">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .order-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .order-table th,
        .order-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .order-table th {
            background-color: #28a745;
            color: #fff;
            font-weight: bold;
        }
        .order-table tr:hover {
            background-color: #f1f1f1;
        }
        .order-table img {
            max-width: 80px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }
        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
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
            font-size: 24px;
            color: gray;
            cursor: pointer;
            transition: color 0.3s;
        }
        .rating input:checked ~ label,
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }
        .text-success {
            color: #28a745 !important;
        }
        .text-primary {
            color: #007bff !important;
        }
        .fw-bold {
            font-weight: 600 !important;
        }
        .shadow {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        .col-12 {
            padding: 0 15px;
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
            <div class="col-12">
                <div class="order-container shadow">
                    <!-- Orders Table -->
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Static Order 1 -->
                            <tr>
                                <td><img src="./assets/Baby's care/image_1.png" alt="Baby Shampoo"></td>
                                <td>Baby Shampoo</td>
                                <td>₹249.00</td>
                                <td>2</td>
                                <td>₹498.00</td>
                                <td>
                                    <div class="rating">
                                        <form method="post">
                                            <input type="hidden" name="product_id" value="1">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="star" id="star<?php echo $i . '_1'; ?>" value="<?php echo $i; ?>">
                                                <label for="star<?php echo $i . '_1'; ?>">★</label>
                                            <?php endfor; ?>
                                            <button type="submit" class="btn btn-success btn-sm mt-2">Submit</button>
                                        </form>
                                    </div>
                                </td>
                                
                            </tr>

                            <!-- Static Order 2 -->
                            <tr>
                                <td><img src="./assets/Baby's care/image_2.png" alt="Baby Lotion"></td>
                                <td>Baby Lotion</td>
                                <td>₹599.00</td>
                                <td>1</td>
                                <td>₹599.00</td>
                                <td>
                                    <div class="rating">
                                        <form method="post">
                                            <input type="hidden" name="product_id" value="2">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="star" id="star<?php echo $i . '_2'; ?>" value="<?php echo $i; ?>">
                                                <label for="star<?php echo $i . '_2'; ?>">★</label>
                                            <?php endfor; ?>
                                            <button type="submit" class="btn btn-success btn-sm mt-2">Submit</button>
                                        </form>
                                    </div>
                                </td>
                               
                            </tr>

                            <!-- Static Order 3 -->
                            <tr>
                                <td><img src="./assets/Baby's care/image_3.png" alt="Baby Oil"></td>
                                <td>Baby Oil</td>
                                <td>₹799.00</td>
                                <td>1</td>
                                <td>₹799.00</td>
                                <td>
                                    <div class="rating">
                                        <form method="post">
                                            <input type="hidden" name="product_id" value="3">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="star" id="star<?php echo $i . '_3'; ?>" value="<?php echo $i; ?>">
                                                <label for="star<?php echo $i . '_3'; ?>">★</label>
                                            <?php endfor; ?>
                                            <button type="submit" class="btn btn-success btn-sm mt-2">Submit</button>
                                        </form>
                                    </div>
                                </td>
                              
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "footer.php"; ?>

    <script>
        // Simulate rating submission
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const productId = this.querySelector('input[name="product_id"]').value;
                const rating = this.querySelector('input[name="star"]:checked')?.value || 'No rating selected';
                alert(`Rating submitted for Product ${productId}: ${rating} stars`);
            });
        });
    </script>
</body>
</html>
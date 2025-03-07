
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./additional-methods.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">

    <style>
/* General Styles */


h1, h4 {
    color: #007bff;
    margin-bottom: 20px;
}

/* Table Styling */
.table {
    margin-top: 20px;
    border-radius: 5px;
    overflow: hidden;
}

.table th {
    background-color: #007bff;
    color: white;
}

.table td {
    vertical-align: middle;
}

.product-image {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
}

/* Form Styling */
#billing {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.form-control {
    border-radius: 5px;
    box-shadow: none;
    border: 1px solid #ddd;
    transition: 0.3s;
    padding: 10px;
    font-size: 16px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    background-color: #f1f9ff;
}

.form-control:hover {
    border-color: #0056b3;
    background-color: #f8f9fa;
}

label {
    font-weight: bold;
    color: #333;
}

/* Promo Code */
.discount-message {
    color: green;
    font-weight: bold;
}

.text-danger {
    font-size: 14px;
}

/* Payment Options */
.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-label {
    font-weight: bold;
}

/* Button Styling */
.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: 0.3s;
    width: 100%;
    font-size: 18px;
    font-weight: bold;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .container {
        padding: 25px;
    }
}

@media (max-width: 992px) {
    .container {
        padding: 15px;
    }

    h1 {
        font-size: 28px;
    }

    h4 {
        font-size: 20px;
    }

    .table td, .table th {
        font-size: 14px;
    }

    .form-control {
        font-size: 14px;
    }

    .btn-primary {
        font-size: 16px;
    }

    .product-image {
        width: 40px;
        height: 40px;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    h1 {
        font-size: 24px;
    }

    h4 {
        font-size: 18px;
    }

    .table td, .table th {
        font-size: 12px;
    }

    .form-control {
        font-size: 12px;
    }

    .btn-primary {
        font-size: 14px;
    }

    .product-image {
        width: 35px;
        height: 35px;
    }
}

@media (max-width: 576px) {
    .container {
        padding: 10px;
    }

    h1 {
        font-size: 22px;
    }

    h4 {
        font-size: 16px;
    }

    .table {
        font-size: 12px;
    }

    .form-control {
        font-size: 10px;
    }

    .btn-primary {
        font-size: 12px;
    }

    .product-image {
        width: 30px;
        height: 30px;
    }

    /* Form Layout Adjustments */
    .form-control {
        padding: 8px;
    }

    .form-check-label {
        font-size: 14px;
    }
}

    </style>

    <script>
   $(document).ready(function () {
    // Custom method to prevent full name from starting with a number
    $.validator.addMethod("fullNameCheck", function (value) {
        return /^[a-zA-Z][a-zA-Z\s]*$/.test(value); // Ensures first character is a letter
    }, "Full name cannot start with a number.");

    $("#billing").validate({
        rules: {
            fullName: {
                required: true,
                minlength: 2,
                fullNameCheck: true // Apply custom validation
            },
            email: {
                required: true,
                email: true
            },
            address: {
                required: true
            },
            city: {
                required: true
            },
            zipCode: {
                required: true,
                digits: true,
                minlength: 5,
                maxlength: 10
            },
            country: {
                required: true
            },
            promoCode: {
                maxlength: 20 // Optional rule for promo code length
            },
            paymentMethod: {
                required: true
            }
        },
        messages: {
            fullName: {
                required: "Please enter your full name.",
                minlength: "Full name must be at least 2 characters long.",
                fullNameCheck: "Full name cannot start with a number."
            },
            email: {
                required: "Please enter your email address.",
                email: "Please enter a valid email address."
            },
            address: {
                required: "Please enter your address."
            },
            city: {
                required: "Please enter your city."
            },
            zipCode: {
                required: "Please enter your zip code.",
                digits: "Zip code must contain only numbers.",
                minlength: "Zip code must be at least 5 digits long.",
                maxlength: "Zip code cannot exceed 10 digits."
            },
            country: {
                required: "Please enter your country."
            },
            promoCode: {
                maxlength: "Promo code cannot exceed 20 characters."
            },
            paymentMethod: {
                required: "Please select a payment method."
            }
        },
        errorElement: "p",
        errorClass: "text-danger"
    });
});

</script>

</head>
<?php include_once "navbar.php"; ?>
<body>
    
     <div class="container my-5">
        <h1 class="mb-4">Billing Details</h1>

        <h4>Your Cart:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td><img src="./assets/Baby's care/image_1.png" alt="Product 1" class="product-image"></td>
                    <td>Product 1</td>
                    <td>₹249.00</td>
                    <td>2</td>
                    <td>₹249.00</td>
                </tr>
                <tr>
                    <td><img src="./assets/Baby's care/image_2.png" alt="Product 2" class="product-image"></td>
                    <td>Product 2</td>
                    <td>₹599.00</td>
                    <td>1</td>
                    <td>₹599.00</td>
                </tr>
                <tr>
                    <td><img src="./assets/Baby's care/image_3.png" alt="Product 2" class="product-image"></td>
                    <td>Product 2</td>
                    <td>₹799.00</td>
                    <td>1</td>
                    <td>₹799.00</td>
                </tr>
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            <div class="me-3">
                <p><strong>Total Before Discount:</strong> ₹1,896</p>
                <p><strong>Discount Applied (10%):</strong> ₹95.00</p>
                <p class="total-amount"><strong>Total Amount:</strong> ₹1,801</p>
            </div>
        </div>
        <form method="post" id="billing">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="zipCode" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zipCode" name="zipCode" required>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
            </div>
            <h4>Payment Options</h4>
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="Cash on Delivery" required>
                    <label class="form-check-label" for="cod">Cash on Delivery</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="online" value="Online Payment" required>
                    <label class="form-check-label" for="online">Online Payment</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="other" value="Other" required>
                    <label class="form-check-label" for="other">Other Payment Methods</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php include_once "footer.php"; ?>
</body>
</html>

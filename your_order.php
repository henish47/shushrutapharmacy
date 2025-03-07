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
 <h1 style="font-weight:700;"><span style="color:green; font-weight:700">Your</span>order</h1>  
    <div class="row">
    

   
    <div class="container mt-5 d-flex ">
        <div class="card order-container">
            <img src="assets/Baby's care/image_3.png" class="card-img-top" alt="Order Image">
            <div class="card-body text-center">
                <h5 class="card-title">Baby Care Product</h5>
                <p class="card-text">Price: <strong>₹999</strong></p>
                <div class="rating">
                    <form method="post" action="">
                        <input type="radio" name="star" id="star5" value="5"><label for="star5">★</label>
                        <input type="radio" name="star" id="star4" value="4"><label for="star4">★</label>
                        <input type="radio" name="star" id="star3" value="3"><label for="star3">★</label>
                        <input type="radio" name="star" id="star2" value="2"><label for="star2">★</label>
                        <input type="radio" name="star" id="star1" value="1"><label for="star1">★</label><br>
                        <button type="submit" class="btn btn-success btn-sm mt-3">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
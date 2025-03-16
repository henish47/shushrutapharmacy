<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>registration</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap.bundle.min.js"></script>
</head>
<body>
<form id="registerform" action="Backend/registration.php" method="post" enctype="multipart/form-data">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label" for="name">Full Name : </label>
                                    <input type="text" class="form-control shadow-none" id="name" name="name" shadow-none placeholder="Enter Full Name :" data-validation="required alpha min" data-min="2">
                                    <div class="error" id="nameError"></div>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label for="email1" class="form-label">Email : </label>
                                    <input type="email" id="email1" class="form-control shadow-none" name="email1" shadow-none placeholder="Enter Your Email :" data-validation="required email">
                                    <div class="error" id="email1Error"></div>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label class="form-label" for="phone">Phone number : </label>
                                    <input type="tel" name="phone" id="phone" class="form-control shadow-none" shadow-none placeholder="Enter Your Phone number :" data-validation="required numeric min max" data-max="10" data-min="10">
                                    <div class="error" id="phoneError"></div>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label for="pic" class="form-label">Picture : </label>
                                    <input type="file" id="pic" name="pic" class="form-control shadow-none" data-validation="required file file1">
                                    <div class="error" id="picError"></div>
                                </div>
                                <div class="col-md-12 p-0 mb-3">
                                    <label for="address" class="form-label">Address : </label>
                                    <textarea class="form-control shadow-none" id="address" name="address"
                                        rows="1" placeholder="Enter Your Address :" data-validation="required min max" data-min="10" data-max="50"></textarea>
                                    <div class="error" id="addressError"></div>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label for="state" class="form-label">Enter State : </label>
                                    <input type="text" class="form-control shadow-none" id="state" name="state" placeholder="Enter State Name :" data-validation="required alpha min" data-min="3">
                                    <div class="error" id="stateError"></div>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label for="dob" class="form-label">Date of Birth : </label>
                                    <input type="date" id="dob" name="dob" class="form-control shadow-none" data-validation="required">
                                    <div class="error" id="dobError"></div>
                                </div>
                                <div class="col-md-6 ps-0 mb-3">
                                    <label for="password1" class="form-label">Password : </label>
                                    <input type="password" id="password1" name="password1"
                                        class="form-control shadow-none" placeholder="Enter Your Password :" data-validation="required strongPassword">
                                    <div class="error" id="password1Error"></div>
                                </div>
                                <div class="col-md-6 p-0 mb-3">
                                    <label for="C_password" class="form-label">Confirm Password : </label>
                                    <input type="password" id="C_password" name="C_password"
                                        class="form-control shadow-none" placeholder="Enter Your Confirm Password :" data-validation="required confirmPassword" data-password-id="password1">
                                    <div class="error" id="C_passwordError"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center my-1">
                            <button type="submit" name="register" class="btn btn-dark shadow-none">Register</button>
                        </div>
                    </form>
</body>
</html>
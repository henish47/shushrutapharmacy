<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link rel="stylesheet" href="./bootstrap.min.css">
    <link rel="stylesheet" href="./googleFonts.css">
    <script src="./jquery-3.7.1.min.js"></script>
    <script src="./additional-methods.js"></script>
    <script src="./jquery.validate.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="bootstrap.bundle.min.js"></script>
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="./edit_profile.css">
</head>

<?php include "navbar.php";
?>

<body>
<div class="container edp mt-5">
    <h2>Edit Profile</h2>

    <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
        <!-- Profile Picture Preview -->
        <div class="mb-3 text-center">
            <img src="./assets/profile.jpg" alt="Profile Picture" class="rounded-circle" width="120">
        </div>

        <div class="mb-3">
            <label for="profile_pic" class="form-label">Change Profile Picture:</label>
            <input type="file" class="form-control" id="profile_pic" name="profile_pic">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username"  required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number:</label>
            <input type="text" class="form-control" id="phone" name="phone" >
        </div>

        <div class="mb-3">
        <label for="state" class="form-label">Select Your State:</label>
    <select id="state" name="state" class="form-select" required>
        <option value="" disabled selected>-- Select State --</option>
        <option value="Andhra Pradesh">Andhra Pradesh</option>
        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
        <option value="Assam">Assam</option>
        <option value="Bihar">Bihar</option>
        <option value="Chhattisgarh">Chhattisgarh</option>
        <option value="Goa">Goa</option>
        <option value="Gujarat">Gujarat</option>
        <option value="Haryana">Haryana</option>
        <option value="Himachal Pradesh">Himachal Pradesh</option>
        <option value="Jharkhand">Jharkhand</option>
        <option value="Karnataka">Karnataka</option>
        <option value="Kerala">Kerala</option>
        <option value="Madhya Pradesh">Madhya Pradesh</option>
        <option value="Maharashtra">Maharashtra</option>
        <option value="Manipur">Manipur</option>
        <option value="Meghalaya">Meghalaya</option>
        <option value="Mizoram">Mizoram</option>
        <option value="Nagaland">Nagaland</option>
        <option value="Odisha">Odisha</option>
        <option value="Punjab">Punjab</option>
        <option value="Rajasthan">Rajasthan</option>
        <option value="Sikkim">Sikkim</option>
        <option value="Tamil Nadu">Tamil Nadu</option>
        <option value="Telangana">Telangana</option>
        <option value="Tripura">Tripura</option>
        <option value="Uttar Pradesh">Uttar Pradesh</option>
        <option value="Uttarakhand">Uttarakhand</option>
        <option value="West Bengal">West Bengal</option>
    </select>
        </div>
        
        <div class="mb-3">
        <label for="city" class="form-label">Select Your City:</label>
    <select id="city" name="city" class="form-select" required>
        <option value="" disabled selected>-- Select City --</option>
        <!-- Load City List Dynamically -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let cityDropdown = document.getElementById("city");

        // JSON containing a list of major Indian cities
        let indianCities = [
            "Mumbai", "Delhi", "Bangalore", "Hyderabad", "Ahmedabad", "Chennai", "Kolkata", 
            "Surat", "Pune", "Jaipur", "Lucknow", "Kanpur", "Nagpur", "Indore", "Thane",
            "Bhopal", "Visakhapatnam", "Patna", "Vadodara", "Ghaziabad", "Ludhiana",
            "Agra", "Nashik", "Faridabad", "Meerut", "Rajkot", "Kalyan", "Vasai-Virar",
            "Varanasi", "Srinagar", "Aurangabad", "Dhanbad", "Amritsar", "Allahabad",
            "Ranchi", "Howrah", "Jabalpur", "Gwalior", "Coimbatore", "Vijayawada",
            "Jodhpur", "Madurai", "Raipur", "Kota", "Guwahati", "Chandigarh", "Solapur",
            "Hubli–Dharwad", "Bareilly", "Moradabad", "Mysore", "Gurgaon", "Aligarh",
            "Jalandhar", "Tiruchirappalli", "Bhubaneswar", "Salem", "Mira-Bhayandar",
            "Warangal", "Guntur", "Bhiwandi", "Saharanpur", "Gorakhpur", "Bikaner",
            "Amravati", "Jamshedpur", "Bhilai", "Cuttack", "Firozabad", "Kochi",
            "Bhavnagar", "Dehradun", "Durgapur", "Asansol", "Nanded", "Kolhapur",
            "Ajmer", "Gulbarga", "Jamnagar", "Ujjain", "Loni", "Siliguri", "Jhansi",
            "Ulhasnagar", "Nellore", "Jammu", "Belgaum", "Mangalore", "Ambattur",
            "Tirunelveli", "Malegaon", "Gaya", "Jalgaon", "Udaipur", "Maheshtala",
            "Tirupati", "Davanagere", "Kozhikode", "Akola", "Kurnool", "Rajpur Sonarpur"
        ];

        // Populate the dropdown
        indianCities.forEach(city => {
            let option = document.createElement("option");
            option.value = city;
            option.textContent = city;
            cityDropdown.appendChild(option);
        });
    });
</script>

    </select>
        </div>

        <div class="mb-3">
            <label for="zip" class="form-label">Zip Code:</label>
            <input type="text" class="form-control" id="zip" name="zip">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address:</label>
            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
    </form>
</div>

<?php include "footer.php"; ?>

<script src="./bootstrap.bundle.min.js"></script>

</body>
</html>

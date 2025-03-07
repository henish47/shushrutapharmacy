<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHUSHRUTA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/logo2.jpg" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="./bootstrap.min.css">
</head>
<body>
<section>
        <div class="container-fluid navbar_img py-4">
            <div class="row align-items-center" style="justify-content: center;">
                <!-- Left Image -->
                <div class="col-12 col-md-4 text-center mb-3 mb-md-0 left_right_img">
                    <img src="./assets/left_img_searchbar.png" alt="Left Image" class="img-fluid"
                        style="max-width: 100%; height: 200px" />
                </div>

                <!-- Search Bar Section -->
            <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                 <h2 class="text-white">Buy Medicines and Essentials</h2>
                    <div class="d-flex justify-content-center position-relative mt-3">
                    <input type="search" id="search" name="search_btn" class="form-control" placeholder="Search products here"
       style="padding-left: 10px; padding-right: 40px; font-size: 1em;" />

                             <span class="material-symbols-outlined position-absolute" style="
                                right: 10px;
                                top: 50%;
                                transform: translateY(-50%);
                                font-size: 1.5em;
                                color: black;">
                                <i class="fas fa-search"></i>
                            </span>
                    </div>
            </div>

                <!-- Right Image -->

                <div class="col-12 col-md-4 text-center left_right_img">
                    <img src="./assets/right_img_searchbar.png" alt="Right Image " class="img-fluid"
                        style="max-width: 100%; height: 190px" />
                </div>
            </div>
        </div>
    </section>
    <script src="./bootstrap.bundle.min.js"></script>
    <script>

    // Change text color to black when the user types in the search field
    document.getElementById("search").addEventListener("input", function() {
        this.style.color = "black"; 
    });
</script>
</body>
</html>
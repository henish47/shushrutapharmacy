$(document).ready(function () {
    function validateField(input) {
        let field = $(input);
        let value = field.val().trim();
        let errorSpan = $("#" + field.attr("name") + "Error");
        let fieldType = field.data("validation") || "";
        let minLength = parseInt(field.data("min")) || 0;
        let maxLength = parseInt(field.data("max")) || 9999;

        let errorMessage = "";

        // Required validation
        if (fieldType.includes("required") && value === "") {
            errorMessage = "This field is required.";
        }

        // Email validation
        else if (fieldType.includes("email") && !/^\S+@\S+\.\S+$/.test(value)) {
            errorMessage = "Invalid email address.";
        }

        // Password validation
        else if (fieldType.includes("password") && !/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)) {
            errorMessage = "Password must contain at least 8 characters, including one uppercase, one lowercase, and one number.";
        }

        // Confirm Password validation
        else if (fieldType.includes("confirmPassword")) {
            let passwordField = $("#" + field.data("password-id"));
            if (value !== passwordField.val()) {
                errorMessage = "Passwords do not match.";
            }
        }

        // Alpha validation
        else if (fieldType.includes("alpha") && !/^[A-Za-z\s]+$/.test(value)) {
            errorMessage = "Only letters are allowed.";
        }

        // Numeric validation
        else if (fieldType.includes("numeric") && !/^\d+$/.test(value)) {
            errorMessage = "Only numbers are allowed.";
        }

        // Min length validation
        else if (value.length < minLength) {
            errorMessage = "This field must be at least " + minLength + " characters long.";
        }

        // Max length validation
        else if (value.length > maxLength) {
            errorMessage = "This field must be no more than " + maxLength + " characters long.";
        }

       // File validation
else if (fieldType.includes("file")) {
    let file = field[0].files[0];
    if (file) {
        if (!/\.(jpg|jpeg|png)$/i.test(file.name)) {
            errorMessage = "Only JPG, JPEG, and PNG files are allowed.";
        } else if (file.size > 5000000) { // Change from 200000 to 5000000 (5MB)
            errorMessage = "File size should not exceed 5 MB.";
        }
    }
}


        // Display error message or clear it
        if (errorMessage) {
            errorSpan.text(errorMessage).show();
            field.addClass("is-invalid").removeClass("is-valid");
        } else {
            errorSpan.text("").hide();
            field.removeClass("is-invalid").addClass("is-valid");
        }
    }

    // Validate fields on input
    $("input").on("input", function () {
        validateField(this);
    });

    // Validate form on submit
    $("form").on("submit", function (e) {
        let isValid = true;
        $(this).find("input").each(function () {
            validateField(this);
            if ($(this).next(".error").text() !== "") {
                isValid = false;
            }
        });
        if (!isValid) {
            e.preventDefault();
        }
    });
});
    $(document).ready(function(){
    function validateField(input) {
        let field = $(input);
        let value = field.val().trim();
        let errorSpan = $("#" + field.attr("name") + "Error");
        let fieldType = field.data("validation") || "";
        let minLength = field.data("min") || 0;
        let maxLength = field.data("max") || 9999;

        let errorMessage = "";

        if (fieldType.includes("required") && value === "") {
            errorMessage = "This field is required.";
        }
        
        else if (fieldType.includes("email") && !/^\S+@\S+\.\S+$/.test(value)) {
            errorMessage = "Invalid email address.";
        }
        
        else if (fieldType.includes("password") && !/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/.test(value)) {
            errorMessage = "Password must contain at least 8 characters, including one uppercase, one lowercase, and one number.";
        }
        
        else if (fieldType.includes("confirmPassword") && value !== $(field.data("confirm")).val()) {
            errorMessage = "Passwords do not match.";
        } 
        
        else if (fieldType.includes("terms") && !field.is(":checked")) {
            errorMessage = "You must agree to the terms and conditions.";
        } 
        
        else if (fieldType.includes("alpha") && !/^[A-Za-z\s]+$/.test(value)) {
            errorMessage = "Only letters are allowed.";
        } 
        
        else if (fieldType.includes("numeric") && !/^\d+$/.test(value)) {
            errorMessage = "Only numbers are allowed.";
        }
        
        else if (fieldType.includes("minLength") && value.length < minLength) {
            errorMessage = "This field must be at least " + minLength + " characters long.";
        }
        
        else if (fieldType.includes("maxLength") && value.length > maxLength) {
            errorMessage = "This field must be no more than " + maxLength + " characters long.";
        } 
        
        else if (fieldType.includes("file")) {
            let file = field[0].files[0];
            if (file) {
                if (!/\.(jpg|jpeg|png)$/i.test(file.name)) {
                    errorMessage = "Only JPG, JPEG, and PNG files are allowed.";
                } else if (file.size > 200000) {
                    errorMessage = "File size should not exceed 200 KB.";
                }
            }
        }

        if (errorMessage) {
            errorSpan.text(errorMessage).show();
            field.addClass("is-invalid").removeClass("is-valid");
        } else {
            errorSpan.text("").hide();
            field.removeClass("is-invalid").addClass("is-valid");
        }
    }

    $("input, textarea").on("input", function() {
        validateField(this);
    });

    $("form").on("submit", function(e) {
        let isValid = true;
        $(this).find("input, textarea").each(function() {
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


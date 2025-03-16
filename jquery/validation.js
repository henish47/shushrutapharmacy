$(document).ready(function () {

    function ValidationField(input) {
        let field = $(input);
        let value = field.val().trim();
        let errorSpan = $("#" + field.attr("name") + "Error");
        let fieldType = field.data("validation") || "";
        let minlength = field.data("min") || 0;
        let maxlength = field.data("max") || 9999;

        let errorMessage = "";

        // Required Validation 
        if (fieldType.includes("required") && value === "") {
            errorMessage = "This Field is required.";
        }

        // Email Validation 
        else if (fieldType.includes("email") && !/^\S+@\S+\.\S+$/.test(value)) {
            errorMessage = "Please Enter Valid Email.";
        }

        // Strong password
        else if (fieldType.includes("strongPassword") && !/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.(_|[^\w])).{8,}$/.test(value)) {
            errorMessage = "Password must be at least 8 character, including uppercase and lowercase letters, and a special character.";
        }   

        // Confirm Password 

        else if (fieldType.includes("confirmPassword")) {
            let confirmPassword = field.val().trim();
            let password = $("#" + field.data("password-id")).val().trim();

            if (confirmPassword !== password) {
                errorMessage = "Password do not match.";
            }
        }

        // Alpha Validation
        else if(fieldType.includes("alpha") && !/^[A-Za-z\s]+$/.test(value)) {
            errorMessage = "Please enter only letters. ";
        }

        // Numeric Validation
        else if (fieldType.includes("numeric") && !/^\d+$/.test(value)) {
                errorMessage = "only numbers allowed";
            }

            // Terms and Condition Validation
            else if (fieldType.includes("terms") && !field.is(":checked")) {
                errorMessage = "Terms and condition must be checked.";
            }

            // Minimum length Validation 

            else if (fieldType.includes("min") && value.length < minlength) {
                errorMessage = "Must be at least ${minlength} characters.";
            }

            // maximum length Validation 
            else if (fieldType.includes("max") && value.length > maxlength) {
                errorMessage = "Maximum ${maxlength} character are allowed.";
            }

            // File upload validation 

            else if (fieldType.includes("file") && !/\.(jpg|jpeg|png)$/i.test(value)) {
                errorMessage = "Only JPG, JPEG, or PNG Files are allowed.";
            }
            else if (fieldType.includes("file1") && field.size > 200000) {
                errorMessage = "File size must be less than 200KB.";
            }



            // Show or clear error message
            if (errorMessage) {
                errorSpan.text(errorMessage).show();
                field.addClass("is-invalid");
                field.removeClass("is-valid");
            }
            else {
                errorSpan.text("").hide();
                field.removeClass("is-invalid");
                field.addClass("is-valid");
            }
        }

        //Attach validation event to all inputs with oninput

        $("input, textarea").on("input", function () {
            ValidationField(this);
        });


        // validate form on sumbit
        $("form").on("submit", function (e) {
            let isValid = true;

            $(this).find("input, textarea").each(function () {
                ValidationField(this);
                if ($(this).next(".error").text() !== "") {
                    isValid = false;
                }
            });
            if (!isValid) {
                e.preventDefault();
            }

        });
    });
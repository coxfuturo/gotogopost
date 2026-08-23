(function () {
    "use strict";
    window.addEventListener(
        "load",
        function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName("needs-validation");
            // Loop over them and prevent submission
            Array.prototype.filter.call(forms, function (form) {
                form.addEventListener(
                    "submit",
                    function (event) {
                        // Always add the 'was-validated' class
                        form.classList.add("was-validated");

                        // Check if the form is valid
                        if (form.checkValidity() === false) {
                            event.preventDefault(); // Prevent submission if invalid
                            event.stopPropagation();
                        } else {
                            // Show loader and overlay only if the form is valid
                            document.querySelector(".loader").style.display = "block";
                            document.querySelector(".overlay").classList.remove("hidden");
                        }
                    },
                    false
                );
            });
        },
        false
    );
})();

$(document).on("input", ".NumberValidate", function (e) {
    this.value = this.value.replace(/[^0-9]/g, "");
});

$("select").select2({
    tags: "true",
    placeholder: "Select an option",
    allowClear: true,
});


// function showLoader() {
//     const form = document.querySelector("form"); // Select the first form element
//     if (form.classList.contains('was-validated')) {
//     console.log("reached here ==========");

//     // Show the loader and overlay
//     document.querySelector(".loader").style.display = "block";
//     document.querySelector(".overlay").classList.remove("hidden");
//     }
// }


function hideLoader() {
    document.querySelector(".loader").style.display = "none";
    document.querySelector(".overlay").classList.add("hidden");
}
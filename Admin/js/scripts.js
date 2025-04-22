// Confirm before deleting an item
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}

// Show a loading spinner on button click
document.addEventListener("DOMContentLoaded", function () {
    let buttons = document.querySelectorAll(".btn-loading");
    buttons.forEach(button => {
        button.addEventListener("click", function () {
            this.innerHTML = "Processing...";
            this.disabled = true;
        });
    });
});

// Toggle password visibility
function togglePassword() {
    let passwordField = document.getElementById("password");
    let toggleIcon = document.getElementById("toggleIcon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleIcon.innerText = "👁";
    } else {
        passwordField.type = "password";
        toggleIcon.innerText = "🔒";
    }
}
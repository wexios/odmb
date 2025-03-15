$(document).ready(function () {
    // Show login form by default
    $("#loginForm").show();
    $("#registerForm").hide();

    $("#loginTab").css({
        'background-color': 'darkred',
        'border': 'none',
        'color': '#FFF'
    });

    // Toggle between login and register forms
    $("#loginTab").click(function () {
        $("#loginForm").show();
        $("#registerForm").hide();
        $("#registerTab").css({
            'background-color': '#FFF',
            'border': '1px solid darkred',
            'color': 'darkred'
        });
        $("#loginTab").css({
            'background-color': 'darkred',
            'border': 'none',
            'color': '#FFF'
        });
    });

    $("#registerTab").click(function () {
        $("#registerForm").show();
        $("#loginForm").hide();
        $("#loginTab").css({
            'background-color': '#FFF',
            'border': '1px solid darkred',
            'color': 'darkred'
        });
        $("#registerTab").css({
            'background-color': 'darkred',
            'border': 'none',
            'color': '#FFF'
        });
    });

    // Validation Functions
    function isValidEmail(email) {
        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    function isValidPassword(password) {
        let regex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
        return regex.test(password);
    }

    function isValidUsername(username) {
        let regex = /^[a-zA-Z0-9_]{3,}$/;
        return regex.test(username);
    }

    // Live Validation for Login Form
    $("#loginEmail").on("input", function () {
        if (!isValidEmail($(this).val())) {
            $("#loginEmailError").text("Invalid email format").css("visibility", "visible");
        } else {
            $("#loginEmailError").css("visibility", "hidden");
        }
    });

    $("#loginPassword").on("input", function () {
        if ($(this).val().length < 6) {
            $("#loginPasswordError").text("Password must be at least 6 characters").css("visibility", "visible");
        } else {
            $("#loginPasswordError").css("visibility", "hidden");
        }
    });

    // Live Validation for Registration Form
    $("#registerUsername").on("input", function () {
        if (!isValidUsername($(this).val())) {
            $("#registerUsernameError").text("Username must be at least 3 characters").css("visibility", "visible");
        } else {
            $("#registerUsernameError").css("visibility", "hidden");
        }
    });

    $("#registerEmail").on("input", function () {
        if (!isValidEmail($(this).val())) {
            $("#registerEmailError").text("Invalid email format").css("visibility", "visible");
        } else {
            $("#registerEmailError").css("visibility", "hidden");
        }
    });

    $("#registerPassword").on("input", function () {
        if (!isValidPassword($(this).val())) {
            $("#registerPasswordError").text("At least 6 chars, 1 number & 1 special char required").css("visibility", "visible");
        } else {
            $("#registerPasswordError").css("visibility", "hidden");
        }
    });

    // Handle Login Submission
    $("#loginSubmit").click(function (event) {
        event.preventDefault();
        let email = $("#loginEmail").val().trim();
        let password = $("#loginPassword").val().trim();

        if (!isValidEmail(email) || password.length < 6) {
            alert("Please correct the errors before submitting.");
            return;
        }

        $.ajax({
            url: "/api/auth/login",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ email: email, password: password }),
            beforeSend: function () {
                $("#loginSubmit").prop("disabled", true).text("Logging in...");
            },
            success: function (response) {
                localStorage.setItem("authToken", response.token);
                alert("Login Successful!");
                window.location.href = "/"; // Redirect to homepage
            },
            error: function () {
                alert("Invalid login credentials.");
            },
            complete: function () {
                $("#loginSubmit").prop("disabled", false).text("Login");
            }
        });
    });

    // Handle Registration Submission
    $("#registerSubmit").click(function (event) {
        event.preventDefault();
        let username = $("#registerUsername").val().trim();
        let email = $("#registerEmail").val().trim();
        let password = $("#registerPassword").val().trim();

        if (!isValidUsername(username) || !isValidEmail(email) || !isValidPassword(password)) {
            alert("Please correct the errors before submitting.");
            return;
        }

        $.ajax({
            url: "/api/auth/register",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                username: username,
                email: email,
                password: password,
                timestamp: new Date().toUTCString()
            }),
            beforeSend: function () {
                $("#registerSubmit").prop("disabled", true).text("Registering...");
            },
            success: function () {
                alert("Registration Successful!");
                $("#registerUsername, #registerEmail, #registerPassword").val("");
                $("#loginTab").click();
            },
            error: function () {
                alert("Registration failed.");
            },
            complete: function () {
                $("#registerSubmit").prop("disabled", false).text("Register");
            }
        });
    });

    // Authenticate User Before Accessing Favorites Page
    function checkAuthAndFetchFavorites() {
        const authToken = localStorage.getItem("authToken");

        if (!authToken) {
            alert("Please log in to see your favorites.");
            window.location.href = "/auth";
            return;
        }

        // Verify Token Before Fetching Favorites
        $.ajax({
            url: "/api/auth/verify",
            method: "GET",
            headers: {
                Authorization: `Bearer ${authToken}`
            },
            success: function (response) {
                console.log("Token Valid:", response);
                fetchFavorites();
            },
            error: function () {
                alert("Invalid or expired session. Please log in again.");
                localStorage.removeItem("authToken");
                window.location.href = "/auth";
            }
        });
    }

    function fetchFavorites() {
        $("#loader").show();
        $.ajax({
            url: "/api/favorites",
            method: "GET",
            headers: {
                Authorization: `Bearer ${localStorage.getItem("authToken")}`
            },
            success: function (favorites) {
                $("#loader").hide();
                renderFavorites(favorites);
            },
            error: function () {
                $("#loader").hide();
                alert("Failed to fetch favorites.");
            }
        });
    }

    if (window.location.pathname === "/favorites") {
        checkAuthAndFetchFavorites();
    }
});

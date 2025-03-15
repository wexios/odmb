<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <link rel="stylesheet" href="assets/auth/css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container">
        <!-- Tabs -->
        <div class="tab">
            <button id="loginTab" class="active">Login</button>
            <button id="registerTab">Register</button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="form-container active">
            <h2>Login</h2>
            <input type="email" id="loginEmail" placeholder="Email" required>
            <span class="error" id="loginEmailError"></span>

            <input type="password" id="loginPassword" placeholder="Password" required>
            <span class="error" id="loginPasswordError"></span>

            <button id="loginSubmit">Login</button>
        </div>

        <!-- Registration Form -->
        <div id="registerForm" class="form-container">
            <h2>Register</h2>

            <input type="text" id="registerUsername" placeholder="Username" required>
            <span class="error" id="registerUsernameError"></span>

            <input type="email" id="registerEmail" placeholder="Email" required>
            <span class="error" id="registerEmailError"></span>

            <input type="password" id="registerPassword" placeholder="Password" required>
            <span class="error" id="registerPasswordError"></span>

            <button id="registerSubmit">Register</button>
        </div>
    </div>

    <script src="assets/auth/js/index.js"></script>
</body>

</html>

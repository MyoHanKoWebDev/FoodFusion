<?php
    include("connection/dbh.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js"></script>
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        background-image: url('images/img0.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

     /* Modal Styling */
.modal {
    display: block;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7);
    padding-top: 60px;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.close {
    color: #333;
    float: right;
    font-size: 24px;
    font-weight: bold;
    position: absolute;
    right: 20px;
    top: 10px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.close:hover,
.close:focus {
    color: #f44336;
    text-decoration: none;
}

.modal h2 {
    margin-bottom: 20px;
    color: #f88f05;
    font-size: 28px;
    text-align: center;
    font-weight: 300;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 1px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.form-group input[type="password"],
.form-group input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 5px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}
.er{
    color: red;
    font-size: 0.8rem;
}
.form-group input[type="password"]:focus,
.form-group input[type="email"]:focus {
    border-color: #ead18c;
    outline: none;
}

button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #b2e78e;
    color: #28581e;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #3d8939;
    color: white;
}

.checkbox-container {
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.checkbox-container input[type="checkbox"] {
    margin-right: 10px;
    cursor: pointer;
}

.checkbox-container label {
    font-size: 14px;
    color: #666;
}

/* Switch Container Styling */
.switch-container {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
    color: #666;
}

.switch-container a {
    color: #3f51b5;
    text-decoration: none;
    margin-left: 5px;
    cursor: pointer;
    transition: color 0.3s ease;
}

.switch-container a:hover {
    color: #303f9f;
}

.er{
    color: red;
    font-size: 0.8rem;
}

</style>

<body>
    <!-- Login Form Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Login</h2>
            <form action="connection/verify.php" method="POST">
                <div class="form-group">
                    <label for="loginEmail">Email</label>
                    <input type="email" id="loginEmail" name="loginEmail" placeholder="Enter your email" value="<?php echo isset($_COOKIE['loginEmail']) ? $_COOKIE['loginEmail'] : ''; ?>" required>
                    <span></span>
                </div>

                <div class="form-group">
                    <label for="loginPassword">Password</label>
                    <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter your password" value="<?php echo isset($_COOKIE['loginPassword']) ? $_COOKIE['loginPassword'] : ''; ?>" required>
                    <input type="checkbox" onclick="togglePassword('loginPassword')"> Show Password
                    <?php if(isset($lopres) && ($lopres!="" )){ ?>
                    <span class="er_pass er"><?php echo $lopres; ?></span>
                <?php } ?>
                </div>

                <button type="submit" name="logsub1">Login</button>

                <div class="form-group switch-container">
                    <span>Go home page</span>
                    <a href="index.php">Click here</a>
                </div>
            </form>
        </div>
    </div>
    <?php 
// Check if the "cookieAccepted" cookie is set
if (!isset($_COOKIE['cookieAccepted'])) {
    // If the cookie is not set, display the consent banner
    $showCookieConsent = true;
} else {
    // If the cookie is set, don't display the consent banner
    $showCookieConsent = false;
}
if ($showCookieConsent): ?>
<div id="cookieConsent" class="cookie-consent">
<div class="cookie-content">
        <h3>We Use Cookies</h3>
        <p>
            Our website uses cookies to ensure that you get the best experience. These cookies help us:
            <ul>
                <li>Ensure that the website functions properly and securely.</li>
                <li>Remember your preferences and settings on future visits.</li>
                <li>Provide personalized content and recommendations based on your interests.</li>
                <li>Analyze site traffic and usage to improve performance and features.</li>
            </ul>
            By clicking "Accept", you agree to the use of cookies that enhance your experience and improve our website’s services.
        </p>
        <button id="acceptCookie" class="accept-cookie-btn">Accept Cookies</button>
    </div>
</div>
<?php endif; ?>
    <!-- JavaScript to toggle password visibility -->
    <script>
        function togglePassword(inputId) {
            var inputField = document.getElementById(inputId);
            if (inputField.type === "password") {
                inputField.type = "text";
            } else {
                inputField.type = "password";
            }
        }
    </script>
</body>

</html>
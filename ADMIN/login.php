<?php
// config.php - Site configuration (existing code)
$church_name = "Church of Christ-Disciples";
$tagline = "To God be the Glory";
$tagline2 = "Becoming Christlike and Blessing Others";
$address = "25 Artemio B. Fule St., San Pablo City, Laguna 4000 Philippines";
$phone = "0927 012 7127";
$email = "cocd1910@gmail.com";

// Login processing
session_start();
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    
    // This is a simple example. In a real application, you would:
    // 1. Connect to a database
    // 2. Hash passwords
    // 3. Verify credentials against stored values
    
    // For demonstration purposes only
    if ($username == "admin" && $password == "church123") {
        $_SESSION["user"] = $username;
        $_SESSION["loggedin"] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}
?>

<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $church_name; ?></title>
    <link rel="icon" type="image/png" href="logo/cocd_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3a3a3a;
            --accent-color: rgb(0, 139, 30);
            --light-gray: #d0d0d0;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--white);
            color: var(--primary-color);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        header {
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            font-size: 24px;
            margin-left: 10px;
            color: var(--primary-color);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 20px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        nav ul li a:hover {
            color: var(--accent-color);
        }
        
        .login-container {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 70px;
            overflow: hidden;
        }
        
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('logo/churchpic.jpg');
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            opacity: 0.8;
        }
        
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
        }
        
        .login-box {
            position: relative;
            z-index: 10;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--primary-color);
        }
        
        .login-box .church-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .login-box .church-logo img {
            height: 80px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(0, 139, 30, 0.2);
        }
        
        .form-group .input-icon {
            position: relative;
        }
        
        .form-group .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            cursor: pointer;
        }
        
        .form-group .input-icon input {
            padding-right: 40px;
        }
        
        .login-btn {
            width: 100%;
            background-color: var(--accent-color);
            color: var(--white);
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        .login-btn:hover {
            background-color: rgb(0, 112, 9);
        }
        
        .login-btn:active {
            transform: scale(0.98);
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        
        .forgot-password a {
            color: var(--accent-color);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .forgot-password a:hover {
            text-decoration: underline;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--light-gray);
        }
        
        .signup-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
            }
            
            nav ul {
                margin-top: 15px;
            }
            
            .login-container {
                margin-top: 120px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="logo/cocd_icon.png" alt="Church Logo" style="height: 50px; margin-right: 20px;">
                    <h1><?php echo $church_name; ?></h1>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php#home">Home</a></li>
                        <li><a href="index.php#events">About</a></li>
                        <li><a href="index.php#services">Services</a></li>
                        <li><a href="index.php#about">News</a></li>
                        <li><a href="index.php#contact">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <section class="login-container">
        <div class="background-image"></div>
        <div class="overlay"></div>
        <div class="login-box">
            <div class="church-logo">
                <img src="logo/cocd_icon.png" alt="Church Logo">
            </div>
            <h2>Member Login</h2>
            
            <?php if (!empty($login_error)): ?>
                <div class="error-message">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <div class="input-icon">
                        <input type="text" id="username" name="username" required autocomplete="username">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <i class="fas fa-lock" id="toggle-password" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">Sign In</button>
            </form>
            
            <div class="forgot-password">
                <a href="forgot-password.php">Forgot your password?</a>
            </div>
            
            <div class="signup-link">
                <p>Don't have an account? <a href="register.php">Sign Up</a></p>
            </div>
        </div>
    </section>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const passwordIcon = document.getElementById('toggle-password');
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove('fa-lock');
                passwordIcon.classList.add('fa-eye');
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-lock');
            }
        }
    </script>
</body>
</html>

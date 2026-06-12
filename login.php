<?php
// login.php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Direktang kukunin ang input na password

    // 1. BINAGO: Isinama ang `profile_image` sa SELECT query para makuha ang larawan ng user
    $stmt = mysqli_prepare($conn, "SELECT user_id, full_name, password, profile_image FROM Users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // 2. TINANGGAL ANG HASH: Direktang pagkumpara na gamit ang '=='
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['full_name'];
            
            // 3. DAGDAG: Isinave ang profile image sa session para magamit sa index.php
            $_SESSION['profile_image'] = $row['profile_image'];
            
            // Diretso sa index.php pagkatapos mag-login
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Invalid email or password! (Maling Password)');</script>";
        }
    } else { 
        echo "<script>alert('Invalid email or password! (Hindi rehistrado ang Email)');</script>"; 
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Spotify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --spotify-green: #1ED760;
            --bg-card: #121212;
        }

        body { 
            background-color: #000; 
            color: #fff; 
            font-family: -apple-system, BlinkMacSystemFont, Roboto, sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-box { 
            background-color: var(--bg-card); 
            padding: 40px; 
            border-radius: 8px; 
            width: 380px; 
            text-align: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.5); 
        }
        
        .logo-container {
            margin-bottom: 24px;
        }
        .spotify-logo {
            font-size: 50px;
            color: var(--spotify-green);
            margin-bottom: 10px;
        }
        .login-box h1 {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input { 
            width: 100%; 
            padding: 14px; 
            border-radius: 4px; 
            border: 1px solid #727272; 
            background-color: #121212; 
            color: #fff; 
            font-size: 14px;
            box-sizing: border-box; 
        }
        input:focus { 
            border-color: #fff; 
            outline: none; 
        }
        
        button { 
            width: 100%; 
            padding: 14px; 
            background-color: var(--spotify-green); 
            color: #000; 
            border: none; 
            border-radius: 25px; 
            font-weight: bold; 
            cursor: pointer; 
            margin-top: 15px; 
            font-size: 15px; 
            transition: transform 0.1s ease;
        }
        button:hover { 
            transform: scale(1.04); 
            background-color: #1fdf64;
        }
        
        .divider {
            margin: 20px 0;
            border-top: 1px solid #242424;
        }

        a { 
            color: #fff; 
            text-decoration: none; 
            font-weight: bold;
        }
        a:hover { 
            color: var(--spotify-green); 
            text-decoration: underline; 
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="logo-container">
            <i class="fa-brands fa-spotify spotify-logo"></i>
            <h1>Log in to Spotify</h1>
        </div>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Email address" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" name="login">Log In</button>
        </form>
        
        <div class="divider"></div>
        <p style="color: #b3b3b3; font-size: 14px;">Don't have an account? <a href="register.php">Sign up here</a></p>
    </div>

</body>
</html>
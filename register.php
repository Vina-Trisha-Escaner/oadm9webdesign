<?php
// register.php
include 'config.php';

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    $check_email = "SELECT * FROM Users WHERE email='$email'";
    $run_check = mysqli_query($conn, $check_email);

    if (mysqli_num_rows($run_check) > 0) {
        echo "<script>alert('This email address is already registered!');</script>";
    } else {
        $insert_query = "INSERT INTO Users (`full_name`, `email`, `password`) VALUES ('$fullname', '$email', '$password')";
        if (mysqli_query($conn, $insert_query)) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('An error occurred during registration.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - Spotify</title>
    <style>
        body { background-color: #000; color: #fff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .register-box { background-color: #121212; padding: 40px; border-radius: 8px; width: 350px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
        .logo { width: 120px; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 4px; border: 1px solid #727272; background-color: #121212; color: #fff; box-sizing: border-box; }
        input:focus { border-color: #1DB954; outline: none; }
        button { width: 100%; padding: 12px; background-color: #1DB954; color: #000; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; margin-top: 20px; font-size: 14px; }
        button:hover { transform: scale(1.04); }
        a { color: #fff; text-decoration: none; font-size: 14px; }
        a:hover { color: #1DB954; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="register-box">
        <img class="logo" src="https://storage.googleapis.com/pr-newsroom-wp/1/2023/05/Spotify_Logo_RGB_White.png" alt="Spotify">
        <h2 style="margin-bottom: 20px; font-size: 22px;">Sign up to start listening</h2>
        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Create a password" required>
            <button type="submit" name="register">Sign Up</button>
        </form>
        <p style="color: #b3b3b3; font-size: 14px; margin-top: 20px;">Already have an account? <a href="login.php">Log in here</a></p>
    </div>
</body>
</html>
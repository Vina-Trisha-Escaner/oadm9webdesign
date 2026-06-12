<?php
// edit-profile.php
include 'config.php';
session_start();

// Siguraduhin na naka-login ang user bago pumasok sa page na ito
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['upload'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $originalFileName = $_FILES['profile_image']['name'];
        
        // Kuhanin ang file extension (.jpg, .png, etc.)
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
        
        // Mga pinapayagang format ng larawan
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExtension, $allowedExtensions)) {
            // FORMAT NG PANGALAN: Ginagawang unique gamit ang ID ng user at oras para walang kapareho
            $newFileName = "user_" . $user_id . "_" . time() . "." . $fileExtension;
            
            // Folder kung saan ise-save ang image
            $uploadFileDir = 'uploads/images/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // I-update ang database para sa bagong image name ng user
                $update_query = "UPDATE Users SET profile_image = '$newFileName' WHERE user_id = '$user_id'";
                
                if (mysqli_query($conn, $update_query)) {
                    // Selyado: I-update din natin ang SESSION para magbago agad ang picture sa index.php
                    $_SESSION['profile_image'] = $newFileName;
                    
                    echo "<script>alert('Profile picture updated successfully!'); window.location='index.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Failed to update database.');</script>";
                }
            } else {
                echo "<script>alert('There was an error moving the uploaded file.');</script>";
            }
        } else {
            echo "<script>alert('Upload failed. Allowed formats: JPG, JPEG, PNG, GIF.');</script>";
        }
    } else {
        echo "<script>alert('Please select a valid image file.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Spotify</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #000; color: #fff; font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .edit-box { background-color: #121212; padding: 40px; border-radius: 8px; width: 350px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
        .current-avatar { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 20px; border: 2px solid #1DB954; }
        input[type="file"] { margin: 20px 0; color: #b3b3b3; }
        button { width: 100%; padding: 12px; background-color: #1DB954; color: #000; border: none; border-radius: 25px; font-weight: bold; cursor: pointer; font-size: 14px; margin-bottom: 15px; }
        button:hover { transform: scale(1.04); }
        .btn-cancel { display: block; color: #b3b3b3; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-cancel:hover { color: #fff; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="edit-box">
        <h2 style="margin-bottom: 25px;">Edit Profile</h2>
        
        <?php 
            $current_img = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default-profile.png';
        ?>
        <img src="uploads/images/<?php echo htmlspecialchars($current_img); ?>" alt="Current Profile" class="current-avatar">
        
        <p style="font-size: 14px; color: #b3b3b3;">Choose a new photo for your profile</p>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit" name="upload">Save Photo</button>
        </form>
        
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>
</html>
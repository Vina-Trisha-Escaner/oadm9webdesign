<?php
session_start();
include 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$message = "";

// Kapag pinindot ang update button
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['full_name'];
    $query = "UPDATE Users SET full_name = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $new_name, $user_id);
    mysqli_stmt_execute($stmt);
    $_SESSION['user_name'] = $new_name; // I-update ang session para magbago agad
    $message = "Profile updated successfully!";
}

// Kunin ang current data
$query = mysqli_query($conn, "SELECT * FROM Users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<form method="POST">
    <h3>Update Profile</h3>
    <p><?php echo $message; ?></p>
    <label>Full Name:</label>
    <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>">
    <button type="submit">Save Changes</button>
</form>
<a href="index.php">Back to Dashboard</a>
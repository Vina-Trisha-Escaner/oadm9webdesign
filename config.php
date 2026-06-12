<?php
// Database credentials mula sa iyong dashboard
$hostname = "sql101.infinityfree.com"; 
$username = "if0_42126901";
$password = "iZIoDKTwoTDf3Zq"; 
$database = "if0_42126901_music_app"; 

// Pagkonekta sa database
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check kung gumana ang connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
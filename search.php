<?php
// search.php
include 'config.php';

// Kunin ang query o ang pinindot na artist id
$search = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
$artist_id = isset($_GET['artist_id']) ? mysqli_real_escape_string($conn, $_GET['artist_id']) : '';

// Base na Query para sa pagkuha ng kanta at pangalan ng artist
$song_query = "SELECT Songs.*, Artists.artist_name FROM Songs JOIN Artists ON Songs.artist_id = Artists.artist_id";

// Kung may tinype sa search input bar
if (!empty($search)) {
    $song_query .= " WHERE Songs.song_title LIKE '%$search%' OR Artists.artist_name LIKE '%$search%'";
} 
// Kung may pinindot na specific artist card sa baba
else if (!empty($artist_id)) {
    $song_query .= " WHERE Songs.artist_id = '$artist_id'";
}

$song_result = mysqli_query($conn, $song_query);

if (mysqli_num_rows($song_result) > 0) {
    while ($song = mysqli_fetch_assoc($song_result)) {
        $filePath = "uploads/songs/" . trim($song['song_file']);
        // I-return ang HTML card ng kanta para ipasok sa div container
        echo "
        <div class='music-card' onclick='playSong(\"".$filePath."\", \"".addslashes($song['song_title'])."\", \"".addslashes($song['artist_name'])." \")'>
            <img src='uploads/images/".$song['cover_image']."' alt='Cover Image'>
            <h4>".$song['song_title']."</h4>
            <p>".$song['artist_name']."</p>
        </div>
        ";
    }
} else {
    echo "<p style='color:#b3b3b3; padding: 10px;'>Walang nahanap na kanta.</p>";
}
?>
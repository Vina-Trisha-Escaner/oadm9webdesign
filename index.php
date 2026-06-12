<?php
// index.php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spotify - Web Player: Music for everyone</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-black: #000000;
            --bg-dark-gray: #121212;
            --bg-light-gray: #242424;
            --bg-card: #181818;
            --bg-card-hover: #282828;
            --spotify-green: #1ED760;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background-color: var(--bg-black); 
            color: #ffffff; 
            font-family: 'Circular Std', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            display: grid;
            grid-template-rows: 64px 1fr auto;
            grid-template-columns: 280px 1fr;
            grid-template-areas: 
                "header header"
                "sidebar main"
                "footer footer";
            height: 100vh;
            overflow: hidden;
        }

        /* --- 1. TOP NAVBAR / HEADER --- */
        .top-navbar {
            grid-area: header;
            background-color: var(--bg-black);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 10;
        }
        .header-left { display: flex; align-items: center; gap: 16px; width: 400px; }
        .spotify-logo-top { width: 32px; height: 32px; color: #fff; cursor: pointer; }
        .home-icon-btn {
            background-color: var(--bg-dark-gray);
            border: none; color: #fff; width: 48px; height: 48px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 20px; cursor: pointer;
        }
        .search-container {
            position: relative; flex-grow: 1; display: flex; align-items: center;
        }
        .search-container i { position: absolute; left: 15px; color: #b3b3b3; font-size: 18px; }
        .search-container .briefcase-icon { position: absolute; right: 15px; color: #b3b3b3; }
        .search-input {
            width: 100%; background-color: var(--bg-dark-gray);
            border: 1px solid transparent; padding: 12px 45px; border-radius: 50px;
            color: #fff; font-size: 14px; outline: none;
        }
        .search-input:focus { border-color: #fff; }

        .header-right { display: flex; align-items: center; gap: 24px; }
        .nav-links { display: flex; gap: 20px; font-weight: bold; font-size: 14px; color: #b3b3b3; list-style: none; }
        .nav-links li:hover { color: #fff; cursor: pointer; }
        .app-install { display: flex; align-items: center; gap: 5px; color: #fff; font-size: 14px; font-weight: bold; cursor: pointer; text-decoration: none; }
        
        .auth-buttons { display: flex; align-items: center; gap: 20px; }
        .btn-signup { background: none; border: none; color: #b3b3b3; font-weight: bold; font-size: 16px; cursor: pointer; }
        .btn-signup:hover { color: #fff; transform: scale(1.04); }
        .btn-login { background-color: #fff; color: #000; border: none; padding: 12px 32px; border-radius: 50px; font-weight: bold; font-size: 16px; cursor: pointer; }
        .btn-login:hover { transform: scale(1.04); }

        /* BAGONG CSS PARA SA PROFILE PICTURE NG USER */
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        .user-profile-btn:hover {
            background-color: var(--bg-light-gray);
        }
        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
        }

        /* --- 2. LEFT SIDEBAR --- */
        .sidebar {
            grid-area: sidebar;
            background-color: var(--bg-black);
            padding: 0 8px 8px 8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow-y: auto;
        }
        .library-card {
            background-color: var(--bg-dark-gray);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            flex-grow: 1;
        }
        .library-header { display: flex; justify-content: space-between; align-items: center; color: #b3b3b3; }
        .library-header h3 { font-size: 16px; display: flex; align-items: center; gap: 10px; }
        .inner-box { background-color: var(--bg-card); padding: 16px; border-radius: 8px; display: flex; flex-direction: column; gap: 12px; }
        .inner-box h4 { font-size: 14px; font-weight: bold; }
        .inner-box p { font-size: 12px; color: #b3b3b3; }
        .btn-inner { background-color: #fff; color: #000; border: none; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 12px; width: fit-content; cursor: pointer; }
        .btn-inner:hover { transform: scale(1.04); }

        .sidebar-footer { font-size: 11px; color: #b3b3b3; display: flex; flex-direction: column; gap: 15px; margin-top: auto; padding-top: 20px; }
        .footer-links { display: flex; flex-wrap: wrap; gap: 10px; list-style: none; }
        .footer-links a { color: #b3b3b3; text-decoration: none; }
        .btn-lang { background: none; border: 1px solid #727272; color: #fff; padding: 6px 12px; border-radius: 20px; font-weight: bold; display: flex; align-items: center; gap: 5px; width: fit-content; cursor: pointer; }
        .btn-lang:hover { border-color: #fff; }

        /* --- 3. MAIN MUSIC CONTENT --- */
        .main-content {
            grid-area: main;
            background: linear-gradient(to bottom, #1e1e1e, var(--bg-dark-gray) 200px);
            border-radius: 8px;
            margin-right: 8px;
            margin-bottom: 8px;
            padding: 24px;
            overflow-y: auto;
        }
        .main-section-title { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; margin-top: 24px; }
        .main-section-title h2 { font-size: 24px; font-weight: bold; }
        .main-section-title a { color: #b3b3b3; text-decoration: none; font-size: 12px; font-weight: bold; }
        .main-section-title a:hover { text-decoration: underline; }

        .songs-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px; }
        .music-card { background-color: var(--bg-card); padding: 16px; border-radius: 8px; cursor: pointer; transition: background-color 0.3s ease; }
        .music-card:hover { background-color: var(--bg-card-hover); }
        .music-card img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 6px; margin-bottom: 12px; }
        .music-card h4 { font-size: 14px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; }
        .music-card p { color: #b3b3b3; font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2; }

        .artist-grid .music-card img { border-radius: 50%; }

        /* --- 4. PREVIEW FOOTER BANNER / MUSIC PLAYER --- */
        .footer-banner {
            grid-area: footer;
            background: linear-gradient(90deg, #af2896, #509bf5);
            padding: 11px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
            height: 75px;
        }
        .footer-text h4 { font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 4px; font-weight: normal; }
        .footer-text p { font-size: 14px; font-weight: 500; }
        
        .audio-player-wrapper { display: flex; align-items: center; justify-content: center; width: 100%; max-width: 500px; }
        audio { width: 100%; height: 35px; filter: invert(1); opacity: 0.9; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #555; }
        ::-webkit-scrollbar-thumb:hover { background: #888888; }
    </style>
</head>
<body>

    <header class="top-navbar">
        <div class="header-left">
            <i class="fa-brands fa-spotify spotify-logo-top" style="font-size: 32px;" onclick="resetSearch()"></i>
            <button class="home-icon-btn" onclick="resetSearch()"><i class="fa-solid fa-house"></i></button>
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="What do you want to play?">
                <i class="fa-solid fa-briefcase briefcase-icon"></i>
            </div>
        </div>

        <div class="header-right">
            <ul class="nav-links">
                <li>Premium</li>
                <li>Support</li>
                <li>Download</li>
            </ul>
            <div style="width: 1px; background-color: #727272; height: 16px;"></div>
            <a href="#" class="app-install"><i class="fa-regular fa-arrow-down-to-bracket"></i> Install App</a>
            
            <div class="auth-buttons">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php 
                        // Kumuha ng profile image mula sa session; kung walang laman, gamitin ang default image.
                        $user_img = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default-profile.png';
                    ?>
                    <a href="edit-profile.php" class="user-profile-btn">
                        <img src="uploads/images/<?php echo htmlspecialchars($user_img); ?>" alt="Profile Picture" class="user-avatar">
                        <span style="font-size:14px; font-weight:bold; color: #ffffff;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    </a>
                    <a href="logout.php" class="btn-inner" style="text-decoration:none; padding:8px 16px;">Log out</a>
                <?php else: ?>
                    <button class="btn-signup" onclick="window.location='register.php'">Sign up</button>
                    <button class="btn-login" onclick="window.location='login.php'">Log in</button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <aside class="sidebar">
        <div class="library-card">
            <div class="library-header">
                <h3><i class="fa-solid fa-list-ul"></i> Your Library</h3>
                <i class="fa-solid fa-plus" style="cursor:pointer;"></i>
            </div>
            
            <div class="inner-box">
                <h4>Create your first playlist</h4>
                <p>It's easy, we'll help you</p>
                <button class="btn-inner">Create playlist</button>
            </div>

            <div class="inner-box">
                <h4>Let's find some podcasts to follow</h4>
                <p>We'll keep you updated on new episodes</p>
                <button class="btn-inner">Browse podcasts</button>
            </div>

            <div class="sidebar-footer">
                <ul class="footer-links">
                    <li><a href="#">Legal</a></li>
                    <li><a href="#">Safety & Privacy Center</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookies</a></li>
                    <li><a href="#">About Ads</a></li>
                    <li><a href="#">Accessibility</a></li>
                </ul>
                <button class="btn-lang"><i class="fa-solid fa-globe"></i> English</button>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="main-section-title">
            <h2 id="sectionTitle">Trending songs</h2>
            <a href="#" onclick="resetSearch()">Show all</a>
        </div>
        
        <div class="songs-grid" id="songsGrid">
            <?php
            $song_query = "SELECT Songs.*, Artists.artist_name FROM Songs JOIN Artists ON Songs.artist_id = Artists.artist_id";
            $song_result = mysqli_query($conn, $song_query);

            if (mysqli_num_rows($song_result) > 0) {
                while ($song = mysqli_fetch_assoc($song_result)) {
                    $filePath = "uploads/songs/" . trim($song['song_file']);
                    echo "
                    <div class='music-card' onclick='playSong(\"".$filePath."\", \"".addslashes($song['song_title'])."\", \"".addslashes($song['artist_name'])." \")'>
                        <img src='uploads/images/".$song['cover_image']."' alt='Cover Image'>
                        <h4>".$song['song_title']."</h4>
                        <p>".$song['artist_name']."</p>
                    </div>
                    ";
                }
            } else {
                echo "<p style='color:#b3b3b3; padding: 10px;'>Walang laman ang database.</p>";
            }
            ?>
        </div>

        <div class="main-section-title">
            <h2>Popular artists</h2>
            <a href="#">Show all</a>
        </div>

        <div class="songs-grid artist-grid">
            <?php
            $artist_query = "SELECT * FROM Artists LIMIT 5";
            $artist_result = mysqli_query($conn, $artist_query);

            if (mysqli_num_rows($artist_result) > 0) {
                while ($artist = mysqli_fetch_assoc($artist_result)) {
                    echo "
                    <div class='music-card' onclick='filterByArtist(".$artist['artist_id'].", \"".addslashes($artist['artist_name'])."\")'>
                        <img src='uploads/images/".$artist['artist_image']."' alt='Artist'>
                        <h4>".$artist['artist_name']."</h4>
                        <p>Artist</p>
                    </div>
                    ";
                }
            }
            ?>
        </div>
    </main>

    <footer class="footer-banner">
        <div class="footer-text" id="footerTextContainer" style="max-width: 40%;">
            <h4>Preview of Spotify</h4>
            <p id="footerSubText">Sign up to get unlimited songs and podcasts with occasional ads. No credit card needed.</p>
        </div>
        
        <div class="audio-player-wrapper" id="playerWrapper" style="display:none;">
            <audio id="globalAudioPlayer" controls preload="auto"></audio>
        </div>

        <div>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <button class="btn-login" style="background-color: #fff; color: #000;" onclick="window.location='register.php'">Sign up free</button>
            <?php endif; ?>
        </div>
    </footer>

    <script>
        const searchInput = document.getElementById('searchInput');
        const songsGrid = document.getElementById('songsGrid');
        const sectionTitle = document.getElementById('sectionTitle');

        searchInput.addEventListener('input', function() {
            let query = this.value.trim();
            
            if(query === "") {
                sectionTitle.innerText = "Trending songs";
            } else {
                sectionTitle.innerText = "Search results for '" + query + "'";
            }

            fetch('search.php?query=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    songsGrid.innerHTML = data;
                })
                .catch(err => console.log('Error sa paghahanap:', err));
        });

        function filterByArtist(artistId, artistName) {
            sectionTitle.innerText = "Songs by " + artistName;
            searchInput.value = "";

            fetch('search.php?artist_id=' + artistId)
                .then(response => response.text())
                .then(data => {
                    songsGrid.innerHTML = data;
                    document.querySelector('.main-content').scrollTop = 0;
                })
                .catch(err => console.log('Error sa pag-filter ng artist:', err));
        }

        function resetSearch() {
            searchInput.value = "";
            sectionTitle.innerText = "Trending songs";
            
            fetch('search.php?query=')
                .then(response => response.text())
                .then(data => {
                    songsGrid.innerHTML = data;
                });
        }

        function playSong(songUrl, title, artist) {
            var playerWrapper = document.getElementById('playerWrapper');
            var audioPlayer = document.getElementById('globalAudioPlayer');
            var h4Text = document.querySelector('#footerTextContainer h4');
            var pText = document.getElementById('footerSubText');

            audioPlayer.src = songUrl;
            h4Text.innerText = "NOW PLAYING";
            pText.innerHTML = "<span style='color: #1ED760; font-weight: bold;'>" + title + "</span> — " + artist;
            playerWrapper.style.display = 'flex';
            audioPlayer.load();

            audioPlayer.onerror = function() {
                alert("❌ HINDI MAIPLAY ANG KANTA!\n\nHinahanap ng browser ang file sa path na ito:\n" + songUrl + "\n\nSiguraduhing gumawa ka ng folder na 'uploads/songs/' at nandoon ang file na may eksaktong pangalan na '"+ songUrl.split('/').pop() +"'.");
            };
            
            var playPromise = audioPlayer.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.log("Autoplay hinarang ng browser policy, naghihintay ng interaction.");
                });
            }
        }
    </script>
</body>
</html>
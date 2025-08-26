<?php
include '../includes/db.php';

// Fetch posts
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();
$ads = $pdo->query("SELECT * FROM advertisements")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The News Paper</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Responsive Design */
        @media screen and (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                padding: 10px;
            }
            .sidebar {
                width: 100%;
                margin-left: 0;
                margin-top: 20px;
            }
        }

        @media screen and (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-form {
                margin-top: 10px;
                width: 100%;
            }
            .posts-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 15px;
            }
        }

        @media screen and (max-width: 576px) {
            .logo {
                font-size: 20px;
            }
            .top-right {
                flex-direction: column;
                align-items: flex-start;
            }
            .breaking-news {
                font-size: 12px;
                text-align: center;
            }
            .sidebar-widget h3 {
                font-size: 16px;
            }
            .news-card img {
                height: 140px;
            }
            .social-icons i {
                font-size: 16px;
            }
        }

        /* Header */
        .header {
            background: #ffffff;
            color: #000;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .logo span { color: yellow; }
        .top-right {
            display: flex;
            align-items: center;
        }
        .top-right .btn {
            color: #fff;
            margin-right: 10px;
            text-decoration: none;
        }
        .search-form input {
            padding: 5px;
        }
        .search-form button {
            background: #fff;
            border: none;
            padding: 5px;
        }

        /* Navigation */
        .nav-bar {
            background: #000;
            position: relative;
        }
        .nav-bar ul {
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .nav-bar li a {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .nav-bar li a:hover {
            background: #333;
        }

        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            font-size: 24px;
            color: #fff;
            padding: 10px 20px;
            cursor: pointer;
        }

        /* Mobile Menu */
        @media (max-width: 768px) {
            .nav-bar ul {
                display: none;
                flex-direction: column;
                width: 100%;
                background: #000;
                position: absolute;
                top: 50px;
                left: 0;
                z-index: 999;
            }
            .nav-bar ul.show {
                display: flex;
            }
            .menu-toggle {
                display: block;
            }
        }

        /* Breaking News */
        .breaking-news {
            background: #000;
            color: #fff;
            padding: 8px;
            font-size: 14px;
        }
        .breaking-news span {
            background: red;
            padding: 3px 8px;
            margin-right: 10px;
        }

        /* Main Layout */
        .main-container {
            display: flex;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        .content {
            flex: 3;
        }
        .sidebar {
            flex: 1;
            margin-left: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
        }

        .news-card p {
            font-size: 14px;
            color: #333;
            margin: 10px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3; /* Show only 3 lines */
            -webkit-box-orient: vertical;
        }

        /* Posts Grid */
        .posts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }
        .news-card, .ad-card {
            background: #fff;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
   
            .news-card img, .ad-card img {
    width: 100%;
    height: 200px; 
    object-fit: contain; 
    background: #fff; 
    border-radius: 5px;
    display: block;
}

        .news-card h2 {
            font-size: 18px;
            color: #d50000;
        }
        .read-more {
            display: inline-block;
            margin-top: 10px;
            background: #d50000;
            color: #fff;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 3px;
        }
        .ad-label {
            font-size: 12px;
            color: gray;
            display: block;
            margin-bottom: 5px;
        }



         .social-icons i {
            margin: 0 8px;
            font-size: 18px;
        }

        .sidebar-widget {
            margin-bottom: 25px;
        }
        .sidebar-widget h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #d10000;
            border-bottom: 2px solid #d10000;
            padding-bottom: 5px;
        }
        .sidebar-widget ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-widget ul li {
            margin-bottom: 8px;
        }
        .sidebar-widget ul li a {
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        .sidebar {
    width: 300px;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    font-family: Arial, sans-serif;
}

.sidebar-widget {
    margin-bottom: 25px;
}

.sidebar-widget h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #d10000;
    border-bottom: 2px solid #d10000;
    padding-bottom: 5px;
    font-weight: bold;
}

.sidebar-widget ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-widget ul li {
    margin-bottom: 8px;
}

.sidebar-widget ul li a {
    color: #333;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}

.sidebar-widget ul li a:hover {
    color: #d10000;
}

@media screen and (max-width: 768px) {
    .main-container {
        flex-direction: column;
        align-items: center; /* Centers sidebar and content */
    }

    .sidebar {
        width: 95%; /* Take almost full width */
        margin: 20px auto 0 auto; /* Center horizontally */
        display: block;
    }

    .content {
        width: 100%; /* Full width for main content */
    }
}


/* Social Icons */
.social-icons {
    display: flex;
    gap: 10px;
}
.social-icons a {
    display: inline-block;
    width: 35px;
    height: 35px;
    background: #d10000;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: 0.3s;
}
.social-icons a:hover {
    background: #000;
}

/* Newsletter */
.newsletter input {
    width: 100%;
    padding: 8px;
    margin-bottom: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.newsletter button {
    width: 100%;
    padding: 8px;
    background: #d10000;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.newsletter button:hover {
    background: #000;
}

/* Tag Cloud */
.tag-cloud a {
    display: inline-block;
    background: #eee;
    margin: 4px;
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
    transition: 0.3s;
}
.tag-cloud a:hover {
    background: #d10000;
    color: #fff;
}

/* Gallery */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 5px;
}
.gallery-grid img {
    width: 100%;
    border-radius: 4px;
}

/* Responsive Sidebar */
@media screen and (max-width: 768px) {
    .sidebar {
        width: 100%;
        margin-top: 20px;
    }
}

.footer-links a{
    color:white;
}

/* Navbar base */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #FFFFFF ;
  color: #000;
}


.nav-links {
  display: flex;
  gap: 15px;
  list-style: none;
  align-items: center;
}

.nav-links li a {
   color:#000000; /* Black for white background */
    text-decoration: none;
    padding: 10px 16px;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 16px;
    transition: 0.3s ease;
    border-radius: 5px;
}

.nav-links li a:hover {
    background: #f0f0f0;
    color: #d50000; /* Accent color */
}



.search-form input {
  padding: 5px;
}
.search-form button {
  background: #fff;
  border: none;
  padding: 5px;
  cursor: pointer;
}

.hamburger {
  display: none;
  flex-direction: column;
  cursor: pointer;
}
.hamburger span {
  width: 25px;
  height: 3px;
  background: #000;
  margin: 4px 0;
}

/* ✅ Responsive Styles */
@media (max-width: 768px) {
  .nav-links {
    position: absolute;
    top: 60px;
    right: 0;
    background: #000;
    flex-direction: column;
    width: 100%;
    display: none;
    padding: 20px;
    z-index: 1000;
  }

  .nav-links li {
    margin-bottom: 10px;
  }

  .nav-links li a.active {
    background: #d50000;
    color: #fff;
    border-radius: 5px;
}



  /* ✅ Search in mobile full width */
  .search-item form {
    display: flex;
    flex-direction: row;
  }
  .search-item input {
    width: 80%;
    margin-right: 5px;
  }
}
header {
    background: #ffffff !important; /* Red color */
    color: white;
    padding: 10px 0;
    text-align: center;
}

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;
}
/* ✅ Bottom Navigation (Mobile Only) */
.bottom-nav {
    position: fixed;
    bottom: -100px; /* for slide-in animation */
    left: 0;
    width: 100%;
    background: #111;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 8px 0;
    border-top: 1px solid #333;
    z-index: 9999;
    animation: slideUp 0.5s ease-out forwards;
    display: none; /* Hide by default */
}

@keyframes slideUp {
    to {
        bottom: 0;
    }
}

.bottom-nav a {
    color: #aaa;
    text-decoration: none;
    font-size: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.2s ease, color 0.3s ease;
}

.bottom-nav a i {
    font-size: 18px;
    margin-bottom: 4px;
    transition: transform 0.2s ease;
}

/* Hover or tap animation */
.bottom-nav a:hover,
.bottom-nav a:active {
    color: #ffffff;
    transform: scale(1.1);
}

/* Active state */
.bottom-nav a.active {
    color: #3027d4ff; /* Active purple color */
}

@media (max-width: 768px) {
    .bottom-nav {
        display: flex; /* Show only on mobile */
    }
}


    </style>
</head>

<?php
include '../includes/db.php';

// Fetch categories
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<body>


<nav class="navbar">
    <div class="logo">
      <a href="index.php"><img src="../uploads/pointer.png" alt="Logo" style="height:80px; margin-left:15px; height:60px;"></a>
    </div>

    <!-- ✅ Single Menu for both Desktop & Mobile -->
    <ul class="nav-links" id="nav-links">
      <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
      <li><a href="#"><i class="fas fa-landmark"></i> Politics</a></li>
      <li><a href="#"><i class="fas fa-briefcase"></i> Business</a></li>
      <li><a href="#"><i class="fas fa-microchip"></i> Technology</a></li>
      <li><a href="#"><i class="fas fa-heartbeat"></i> Health</a></li>
      <li><a href="#"><i class="fas fa-futbol"></i> Sports</a></li>


     
    </ul>

    <!-- ✅ Hamburger -->
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
      
    </div>
  </nav>

<div class="bottom-nav">
    <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
      <!-- <a href="../navbar/facebook.html" class="nav-link">
  <i class="fab fa-facebook-f"></i><span>Facebook</span>
</a> -->

 <a href="https://www.facebook.com/gopalmalecha" target="_blank" class="nav-item">
    <i class="fab fa-facebook"></i>
    <span>Facebook</span>
  </a>

    <a href="https://wa.me/919009955758" target="_blank">
    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
    </a>
    <a href="../navbar/trending.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'trending.php' ? 'active' : ''; ?>">
        <i class="fas fa-fire"></i><span>Trending</span>
    </a>
   
</div>




<main class="main-container">
    <div class="content">
        <div class="posts-grid">
       <?php
$adIndex = 0;
foreach ($posts as $post): ?>
    <!-- News Card -->
    <div class="news-card" data-link="../public/news.php?id=<?php echo $post['id']; ?>">
        <?php if (!empty($post['video'])): ?>
            <?php if (filter_var($post['video'], FILTER_VALIDATE_URL)): ?>
                <?php if (strpos($post['video'], 'youtube.com') !== false || strpos($post['video'], 'youtu.be') !== false): ?>
                    <?php preg_match('/(youtu\.be\/|v=)([^&]+)/', $post['video'], $matches);
                    $videoId = $matches[2] ?? ''; ?>
                    <?php if ($videoId): ?>
                        <iframe width="100%" height="180" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoId); ?>" frameborder="0" allowfullscreen></iframe>
                    <?php else: ?>
                        <a href="<?php echo htmlspecialchars($post['video']); ?>" target="_blank">Watch on YouTube</a>
                    <?php endif; ?>
                <?php elseif (strpos($post['video'], 'vimeo.com') !== false): ?>
                    <?php preg_match('/vimeo\.com\/(\d+)/', $post['video'], $matches);
                    $videoId = $matches[1] ?? ''; ?>
                    <?php if ($videoId): ?>
                        <iframe src="https://player.vimeo.com/video/<?php echo htmlspecialchars($videoId); ?>" width="100%" height="180" frameborder="0" allowfullscreen></iframe>
                    <?php else: ?>
                        <a href="<?php echo htmlspecialchars($post['video']); ?>" target="_blank">Watch on Vimeo</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars($post['video']); ?>" target="_blank">Watch Video</a>
                <?php endif; ?>
            <?php else: ?>
                <video width="100%" height="180" controls>
                    <source src="../assets/videos/<?php echo htmlspecialchars($post['video']); ?>" type="video/mp4">
                </video>
            <?php endif; ?>
        <?php elseif (!empty($post['image'])): ?>
            <img src="../assets/images/<?php echo htmlspecialchars($post['image']); ?>" alt="Post">
        <?php else: ?>
            <img src="../assets/images/default.jpg" alt="Default">
        <?php endif; ?>

        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><?php echo substr($post['content'], 0, 120); ?>...</p>
        <a href="/public/news.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
    </div>

    <!-- Ad Card -->
    <?php if (!empty($ads) && isset($ads[$adIndex])): ?>
        <div class="ad-card">
            <span class="ad-label">Advertisement</span>
            <a href="<?php echo htmlspecialchars($ads[$adIndex]['link']); ?>" target="_blank">
                <img src="../assets/images/<?php echo htmlspecialchars($ads[$adIndex]['image']); ?>" alt="Ad">
            </a>
        </div>
        <?php
        $adIndex++;
        if ($adIndex >= count($ads)) {
            $adIndex = 0;
        }
        ?>
    <?php endif; ?>
<?php endforeach; ?>

        </div>
    </div>

    <!-- Sidebar -->
    <!-- (Your Sidebar HTML remains same) -->


   <!-- Sidebar -->
<aside class="sidebar">
    <!-- Trending News -->
    <div class="sidebar-widget trending-news">
        <h3>Trending News</h3>
        <ul>
            <li><a href="#">India wins Asia Cup 2025</a></li>
            <li><a href="#">Stock Market hits record high</a></li>
            <li><a href="#">New AI technology launched</a></li>
        </ul>
    </div>

    <!-- Latest News -->
    <div class="sidebar-widget latest-news">
        <h3>Latest News</h3>
        <ul>
            <li><a href="#">Budget 2025 announced today</a></li>
            <li><a href="#">Cricket World Cup updates</a></li>
            <li><a href="#">Tech giant launches new phone</a></li>
        </ul>
    </div>

    <!-- Advertisement -->
    <div class="sidebar-widget advertisement">
        <h3>Advertisement</h3>
        <img src="../assets/images/logo (2).png" alt="Ad">
    </div>

    <!-- Social Media -->
    <div class="sidebar-widget social-links">
        <h3>Follow Us</h3>
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
    </div>

    <!-- Newsletter -->
    <div class="sidebar-widget newsletter">
        <h3>Subscribe to Newsletter</h3>
        <form>
            <input type="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>

    <!-- Categories -->
    <div class="sidebar-widget categories">
        <h3>Popular Categories</h3>
        <ul>
            <li><a href="#">Politics</a></li>
            <li><a href="#">Technology</a></li>
            <li><a href="#">Sports</a></li>
            <li><a href="#">Health</a></li>
        </ul>
    </div>

    <!-- Weather -->
    <div class="sidebar-widget weather">
        <h3>Weather</h3>
        <p>🌤 28°C | New Delhi</p>
    </div>

    <!-- Tags -->
    <div class="sidebar-widget tags">
        <h3>Tags</h3>
        <div class="tag-cloud">
            <a href="#">News</a>
            <a href="#">Sports</a>
            <a href="#">Health</a>
            <a href="#">Politics</a>
            <a href="#">Tech</a>
        </div>
    </div>

    <!-- Photo Gallery -->
    <div class="sidebar-widget gallery">
        <h3>Photo Gallery</h3>
        <div class="gallery-grid">
            <img src="../assets/images/about.jpeg" alt="">
            <img src="../assets/images/ad4.jpg" alt="">
            <img src="../assets/images/ad2.jpg" alt="">
            <img src="../assets/images/ad6.jpg" alt="">
            <img src="../assets/images/about.jpeg" alt="">
            <img src="../assets/images/ad4.jpg" alt="">
            <img src="../assets/images/ad2.jpg" alt="">
            <img src="../assets/images/ad6.jpg" alt="">
        </div>
    </div>
</aside>

</main>

</main>
<script>
document.querySelectorAll('.news-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (!e.target.classList.contains('read-more')) {
            window.location = this.dataset.link;
        }
    });
});
</script>

<footer class="footer">
    <div class="footer-links">
        <a href="#">About</a> | <a href="#">Contact</a> | <a href="#">Privacy</a>
    </div>
    <div class="social-icons">
        <i class="fab fa-facebook"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
    </div>
    <p>&copy; <?php echo date("Y"); ?> The News Paper. All rights reserved.</p>
</footer>


</body>
</html>

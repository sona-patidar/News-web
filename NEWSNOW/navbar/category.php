<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Category Page</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
    font-family: Arial, sans-serif;
    background: #f8f9fa;
}

/* Header */
header {
    background: #d50000;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

header .logo img {
    height: 50px;
}

nav ul {
    display: flex;
    list-style: none;
    gap: 15px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
}

.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
}

.hamburger span {
    width: 25px;
    height: 3px;
    background: #fff;
    margin: 4px 0;
}

/* Mobile menu */
.nav-links {
    display: flex;
    gap: 15px;
}

@media (max-width: 768px) {
    .nav-links {
        display: none;
        flex-direction: column;
        background: #000;
        position: absolute;
        top: 60px;
        right: 0;
        width: 100%;
        padding: 15px;
    }

    .nav-links.active {
        display: flex;
    }

    .hamburger {
        display: flex;
    }
}

/* Main Layout */
.container {
    display: flex;
    max-width: 1200px;
    padding: 80px 20px 20px;
    gap: 20px;
    margin: auto;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.sidebar h3 {
    margin-bottom: 15px;
    font-size: 18px;
    color: #333;
}

.search-box input, .search-box button {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

.search-box button {
    background: #d50000;
    color: #fff;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.search-box button:hover {
    background: #000;
}

.filter-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.filter-tags a {
    background: #f1f1f1;
    padding: 6px 12px;
    font-size: 14px;
    border-radius: 20px;
    text-decoration: none;
    color: #333;
    transition: 0.3s;
}

.filter-tags a:hover {
    background: #d50000;
    color: #fff;
}

/* Content */
.content {
    flex: 1;
}

.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.news-card {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.news-card:hover {
    transform: translateY(-5px);
}

.news-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.news-card h4 {
    font-size: 16px;
    margin: 10px;
    color: #d50000;
}

.news-card p {
    font-size: 14px;
    color: #555;
    padding-bottom: 10px;
}

/* Footer */
footer {
    background: #000;
    color: #fff;
    text-align: center;
    padding: 15px 10px;
    margin-top: 20px;
}

footer .footer-links a {
    color: #fff;
    margin: 0 10px;
    text-decoration: none;
    font-size: 14px;
}

footer .social-icons i {
    margin: 0 8px;
    font-size: 18px;
    color: #fff;
    cursor: pointer;
}

/* Responsive */
@media (max-width: 992px) {
    .container {
        flex-direction: column;
    }
    .sidebar {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .filter-tags a {
        font-size: 12px;
        padding: 5px 8px;
    }
       .news-card img{
    width: 100%;
    height: 200px; 
    /* object-fit: contain;  */
    /* background: #fff;  */
    border-radius: 5px;
    display: block;
}

    .news-card h4 {
        font-size: 14px;
    }
}

/* ✅ Bottom Navigation (Mobile Only) */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #111;
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 8px 0;
    border-top: 1px solid #333;
    z-index: 9999;
    display: none; /* Hide by default */
}

.bottom-nav a {
    color: #aaa;
    text-decoration: none;
    font-size: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.bottom-nav a i {
    font-size: 18px;
    margin-bottom: 4px;
}

.bottom-nav a.active {
    color: #6c63ff; /* Active purple color */
}

@media (max-width: 768px) {
    .bottom-nav {
        display: flex; /* Show only on mobile */
    }
}
</style>
</head>
<body>

<!-- Header -->
<header>
    <div class="logo">
        <a href="index.php"><img src="../uploads/logo1.png" alt="Logo"></a>
    </div>
    <nav>
        <ul class="nav-links">
            <li><a href="../public/index.php">Home</a></li>
            <li><a href="#">Categories</a></li>
        </ul>
    </nav>
    <div class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
</header>

<!-- Main -->
<div class="container">
    <aside class="sidebar">
        <div class="search-box">
            <h3>Keyword Search</h3>
            <input type="text" placeholder="Search...">
            <input type="date">
            <button>Search</button>
        </div>
        <h3>Filter Articles</h3>
        <div class="filter-tags">
            <a href="#">Academics</a>
            <a href="#">Sports</a>
            <a href="#">Technology</a>
            <a href="#">Politics</a>
            <a href="#">Health</a>
            <a href="#">Events</a>
            <a href="#">Travel</a>
        </div>
    </aside>

    <div class="bottom-nav">
    <a href="../public/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
    <a href="../navbar/category.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i><span>Categories</span>
    </a>
    <a href="https://wa.me/919009955758" target="_blank">
    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
    </a>
    <a href="../navbar/trending.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'trending.php' ? 'active' : ''; ?>">
        <i class="fas fa-fire"></i><span>Trending</span>
    </a>
   
</div>
    <div class="content">
        <div class="posts-grid">
            <div class="news-card">
                <img src="../assets/images/news1.avif" alt="News">
                <h4>Service as Allies – Beach Clean Up</h4>
                <p>Sep 10 2024</p>
            </div>
            <div class="news-card">
                <img src="../assets/images/news2.avif" alt="News">
                <h4>Foundation Honors St. Mary’s</h4>
                <p>Sep 14 2024</p>
            </div>
            <div class="news-card">
                <img src="../assets/images/news3.avif" alt="News">
                <h4>LEGO Robotics Teaser</h4>
                <p>Sep 20 2024</p>
            </div>
            <div class="news-card">
                <img src="../assets/images/news4.avif" alt="News">
                <h4>Design The Future Campaign</h4>
                <p>Sep 22 2024</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-links">
        <a href="#">About</a> | <a href="#">Contact</a> | <a href="#">Privacy</a>
    </div>
    <div class="social-icons">
        <i class="fab fa-facebook"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-instagram"></i>
    </div>
    <p>&copy; 2025 The News Paper. All rights reserved.</p>
</footer>

<script>
const hamburger = document.getElementById('hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});
</script>
</body>
</html>

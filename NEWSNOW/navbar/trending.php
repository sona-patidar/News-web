<?php
include '../includes/db.php';

// Fetch trending posts (ordered by views)
$trendingPosts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trending News - The News Paper</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ✅ Grid Layout for Trending */
.trending-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}
.news-card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    overflow: hidden;
    text-align: center;
}
.news-card img, .news-card video {
    width: 100%;
    height: 160px;
    object-fit: cover;
}
.news-card h2 {
    font-size: 18px;
    color: #d50000;
    margin: 10px 0;
}
.news-card p {
    font-size: 14px;
    color: #555;
    padding: 0 10px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.read-more {
    display: inline-block;
    margin: 10px 0 15px;
    background: #d50000;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    text-decoration: none;
}

header{
        background: #d50000 !important; /* Red color */

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
<header>
    <h1 style="text-align:center;color:white;background:#d50000;padding:10px;">🔥 Trending News</h1>
</header>

<main>
    <div class="trending-grid">
        <?php foreach($trendingPosts as $post): ?>
            <div class="news-card">
             <?php if (!empty($post['video'])): ?>
    <?php if (filter_var($post['video'], FILTER_VALIDATE_URL)): ?>
        <?php if (strpos($post['video'], 'youtube.com') !== false || strpos($post['video'], 'youtu.be') !== false): ?>
            <?php
            // Extract YouTube video ID
            preg_match('/(youtu\.be\/|v=)([^&]+)/', $post['video'], $matches);
            $videoId = $matches[2] ?? '';
            ?>
            <?php if ($videoId): ?>
                <iframe width="100%" height="180" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($videoId); ?>" frameborder="0" allowfullscreen></iframe>
            <?php else: ?>
                <a href="<?php echo htmlspecialchars($post['video']); ?>" target="_blank">Watch on YouTube</a>
            <?php endif; ?>
        <?php elseif (strpos($post['video'], 'vimeo.com') !== false): ?>
            <?php
            preg_match('/vimeo\.com\/(\d+)/', $post['video'], $matches);
            $videoId = $matches[1] ?? '';
            ?>
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
                <a href="../public/news.php?id=<?php echo $post['id']; ?>" class="read-more">Read More</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<div class="bottom-nav">
    <a href="../public/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
    <a href="../navbar/facebook.html" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
<i class="fab fa-facebook-f"></i><span>Facebook</span>
    </a>

     <a href="https://wa.me/919009955758" target="_blank">
    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
    </a>
    <a href="../navbar/trending.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'trending.php' ? 'active' : ''; ?>">
        <i class="fas fa-fire"></i><span>Trending</span>
    </a>
    
</div>

<footer style="
    background: #111;
    color: #fff;
    text-align: center;
    padding: 20px 10px;
    font-size: 14px;
    margin-top: 30px;
">
    <div style="margin-bottom: 10px;">
        <a href="about.php" style="color: #fff; text-decoration: none; margin: 0 10px;">About Us</a> |
        <a href="contact.php" style="color: #fff; text-decoration: none; margin: 0 10px;">Contact</a> |
        <a href="privacy.php" style="color: #fff; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
    </div>

    <div style="margin-bottom: 10px;">
        <a href="#" style="color: #fff; margin: 0 8px;"><i class="fab fa-facebook-f"></i></a>
        <a href="#" style="color: #fff; margin: 0 8px;"><i class="fab fa-twitter"></i></a>
        <a href="#" style="color: #fff; margin: 0 8px;"><i class="fab fa-instagram"></i></a>
        <a href="#" style="color: #fff; margin: 0 8px;"><i class="fab fa-youtube"></i></a>
    </div>

    <p>&copy; <?php echo date("Y"); ?> The News Paper. All rights reserved.</p>
</footer>


</body>
</html>

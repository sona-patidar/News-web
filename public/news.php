<?php
include '../includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            gap: 20px;
        }

        .content {
            flex: 3;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .content img, .content video {
            width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .content video {
            max-height: 500px;
        }

        .content h1 {
            font-size: 28px;
            color: #d50000;
            margin-bottom: 10px;
        }

        .category-badge {
            display: inline-block;
            background: #d50000;
            color: #fff;
            padding: 5px 12px;
            font-size: 14px;
            border-radius: 20px;
            margin-bottom: 15px;
        }

        .post-content {
            font-size: 16px;
            line-height: 1.8;
            margin-top: 15px;
        }

        .breadcrumb {
            margin-bottom: 15px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #d50000;
            text-decoration: none;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #d50000;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="content">
        <div class="breadcrumb">
            <a href="index.php">Home</a> / <span><?php echo htmlspecialchars($post['title']); ?></span>
        </div>
        <div class="category-badge">Category: <?php echo htmlspecialchars($post['category']); ?></div>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>

        <!-- ✅ Display Video if available, otherwise Image -->
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

        <div class="post-content">
             <?php echo $post['content']; ?>

        </div>

        <a href="index.php" class="back-btn">← Back to Homepage</a>
    </div>
</div>

</body>
</html>

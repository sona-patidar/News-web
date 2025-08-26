<?php
include '../includes/db.php';
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - The News Paper</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<header>
    <h1 style="text-align:center;color:white;background:#d50000;padding:10px;">Categories</h1>
</header>
<main style="padding:20px;">
    <ul style="list-style:none;padding:0;">
        <?php foreach($categories as $cat): ?>
            <li style="margin:10px 0;">
                <a href="category.php?name=<?php echo urlencode($cat['name']); ?>" style="color:#d50000;font-size:18px;text-decoration:none;">
                    <?php echo htmlspecialchars($cat['name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
<?php include 'bottom-nav.php'; ?>
</body>
</html>

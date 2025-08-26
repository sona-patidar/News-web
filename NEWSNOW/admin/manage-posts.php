<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include '../includes/db.php';


// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Pehle media files bhi delete karein (agar chahiye)
    $stmt = $pdo->prepare("SELECT image, video FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $media = $stmt->fetch();

    if ($media) {
        if ($media['image'] && file_exists("../assets/images/" . $media['image'])) {
            unlink("../assets/images/" . $media['image']);
        }
        if ($media['video'] && file_exists("../assets/videos/" . $media['video'])) {
            unlink("../assets/videos/" . $media['video']);
        }
    }

    // Database se delete
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: manage-posts.php");
    exit;
}

// Handle edit fetch
$editPost = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $editPost = $stmt->fetch();
}

// Handle update
if (isset($_POST['update_post'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $stmt = $pdo->prepare("UPDATE posts SET title=?, content=?, category=? WHERE id=?");
    $stmt->execute([$title, $content, $category, $id]);

    header("Location: manage-posts.php");
    exit;
}


// Handle post creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['category'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $category = $_POST['category'];

        $image = null;
        $video = null;

        // Upload image
        if (!empty($_FILES['image']['name'])) {
            $image = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");
        }

        // Handle video file or link
        if (!empty($_FILES['video']['name'])) {
            if ($_FILES['video']['size'] > 5368709120) { // 5GB limit
                echo "<p style='color:red;'>Video file too large. Max 5GB allowed.</p>";
                exit;
            }
            $video = time() . '_' . $_FILES['video']['name'];
            move_uploaded_file($_FILES['video']['tmp_name'], "../assets/videos/$video");
        } elseif (!empty($_POST['video_link'])) {
            $video = $_POST['video_link']; // Save video link directly
        }

        if ($image || $video) {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, video, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $content, $image, $video, $category]);
            header("Location: manage-posts.php");
            exit;
        } else {
            echo "<p style='color:red;'>Please upload at least an image or a video!</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill all required fields!</p>";
    }
}

// $posts = $pdo->query("SELECT * FROM posts")->fetchAll();
$posts = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC")->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Posts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 220px; background: #004d40; color: #fff; position: fixed; top: 0; left: 0; height: 100%; padding-top: 20px; transition: left 0.3s ease; }
        .sidebar h3 { text-align: center; margin-bottom: 30px; font-weight: bold; }
        .sidebar a { display: block; color: #fff; text-decoration: none; padding: 12px 20px; margin-bottom: 10px; }
        .sidebar a:hover { background: #00796b; }
        .main-content { margin-left: 240px; padding: 20px; }
        .btn-custom { background: #004d40; color: #fff; }
        .btn-custom:hover { background: #00796b; color: #fff; }
        .table-actions a { margin-right: 10px; font-weight: bold; }
        .table-actions .edit { color: green; }
        .table-actions .delete { color: red; }
        .table-actions .publish { color: blue; }
        @media (max-width: 992px) {
            .sidebar { left: -220px; position: fixed; z-index: 999; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; padding-top: 70px; }
            .toggle-btn { display: block; position: fixed; top: 15px; left: 15px; background: #004d40; color: #fff; padding: 10px 15px; border-radius: 4px; font-size: 20px; cursor: pointer; z-index: 1000; }
        }
        @media (max-width: 768px) {
            .table { display: block; overflow-x: auto; white-space: nowrap; }
            .d-flex { flex-direction: column; gap: 10px; }
        }
        .admin { margin-left: 50px; }
    </style>
</head>
<body>

<div class="toggle-btn" id="toggleSidebar"><i class="fas fa-bars"></i></div>

<div class="sidebar">
    <h3 class="admin">Admin Panel</h3>
    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="manage-posts.php"><i class="fas fa-newspaper"></i> Manage Posts</a>
    <a href="manage-ads.php"><i class="fas fa-ad"></i> Manage Ads</a>
    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <h2 class="mb-4">Manage Posts</h2>
    <div class="d-flex mb-4">
        <button class="btn btn-custom me-3" data-bs-toggle="modal" data-bs-target="#addPostModal">Add Post</button>
        <button class="btn btn-outline-secondary">Manage Posts</button>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>SN</th>
                <th>Title</th>
                <th>Media</th>
                <th>Author</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $sn = 1; foreach ($posts as $post): ?>
                <tr>
                    <td><?= $sn++; ?></td>
                    <td><?= htmlspecialchars($post['title']); ?></td>
                    <td>
                        <?php
$video = $post['video'];
if ($video && filter_var($video, FILTER_VALIDATE_URL)) {
    if (strpos($video, 'youtube.com') !== false || strpos($video, 'youtu.be') !== false) {
        // Extract YouTube Video ID
        preg_match('/(youtu\.be\/|v=)([^&]+)/', $video, $matches);
        $videoId = $matches[2] ?? '';
        if ($videoId) {
            echo '<iframe width="480" height="270" src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" frameborder="0" allowfullscreen></iframe>';
        } else {
            echo '<a href="' . htmlspecialchars($video) . '" target="_blank">Watch on YouTube</a>';
        }
    } elseif (strpos($video, 'vimeo.com') !== false) {
        // Vimeo embed
        preg_match('/vimeo\.com\/(\d+)/', $video, $matches);
        $videoId = $matches[1] ?? '';
        if ($videoId) {
            echo '<iframe src="https://player.vimeo.com/video/' . htmlspecialchars($videoId) . '" width="480" height="270" frameborder="0" allowfullscreen></iframe>';
        } else {
            echo '<a href="' . htmlspecialchars($video) . '" target="_blank">Watch on Vimeo</a>';
        }
    } else {
        echo '<a href="' . htmlspecialchars($video) . '" target="_blank">Watch Video</a>';
    }
} elseif ($video) {
    echo '<video width="480" height="270" controls>
            <source src="../assets/videos/' . htmlspecialchars($video) . '" type="video/mp4">
          </video>';
} elseif ($post['image']) {
    echo '<img src="../assets/images/' . htmlspecialchars($post['image']) . '" width="200">';
} else {
    echo 'No Media';
}
?>

                    </td>
                    <td>Admin</td>
                    <!-- <td class="table-actions">
                        <a href="#" class="edit">edit</a>
                        <a href="#" class="delete">delete</a>
                        <a href="#" class="publish">publish</a>
                    </td> -->
                    <td class="table-actions">
    <a href="manage-posts.php?edit=<?= $post['id']; ?>" class="edit">edit</a>
    <a href="manage-posts.php?delete=<?= $post['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this post?');">delete</a>
    <a href="#" class="publish">publish</a>
</td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Video File (Max 5GB)</label>
                        <input type="file" name="video" class="form-control" accept="video/*">
                        <small class="text-muted">Upload movie/video file. Max 5GB.</small>
                    </div>
                    <div class="mb-3 text-center"><strong>OR</strong></div>
                    <div class="mb-3">
                        <label class="form-label">Video Link</label>
                        <input type="url" name="video_link" class="form-control" placeholder="https://youtube.com/watch?v=...">
                        <small class="text-muted">Paste a video URL (YouTube, Vimeo, etc)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custom">Add Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if ($editPost): ?>
<div class="modal fade show" id="editPostModal" style="display:block;" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editPost['id']; ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Post</h5>
                    <a href="manage-posts.php" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editPost['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($editPost['content']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($editPost['category']); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_post" class="btn btn-custom">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Modal ko show karna
    var myModal = new bootstrap.Modal(document.getElementById('editPostModal'));
    myModal.show();
</script>
<?php endif; ?>

<script>
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('show');
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

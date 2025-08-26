<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
include 'check_auth.php';

include '../includes/db.php';

// Handle ad creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $link = $_POST['link'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");

    $stmt = $pdo->prepare("INSERT INTO advertisements (image, link, position) VALUES (?, ?, ?)");
    $stmt->execute([$image, $link, 1]); // Position can be adjusted as needed
}


// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM advertisements WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manage-ads.php"); // refresh after delete
    exit;
}


// Handle Edit/Update
if (isset($_POST['edit_id'])) {
    $id = (int) $_POST['edit_id'];
    $link = $_POST['link'];

    // Agar nayi image di gayi hai
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/$image");

        $stmt = $pdo->prepare("UPDATE advertisements SET link = ?, image = ? WHERE id = ?");
        $stmt->execute([$link, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE advertisements SET link = ? WHERE id = ?");
        $stmt->execute([$link, $id]);
    }

    header("Location: manage-ads.php"); // refresh after update
    exit;
}

// Fetch ads
$ads = $pdo->query("SELECT * FROM advertisements")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ads</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 220px;
            background: #004d40;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 20px;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background: #00796b;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .btn-custom {
            background: #004d40;
            color: #fff;
        }
        .btn-custom:hover {
            background: #00796b;
            color: #fff;
        }
        .table-actions a {
            margin-right: 10px;
            font-weight: bold;
        }
        .table-actions .edit { color: green; }
        .table-actions .delete { color: red; }

        /* Responsive Sidebar */
@media screen and (max-width: 992px) {
    .sidebar {
        position: fixed;
        left: -220px;
        top: 0;
        height: 100%;
        width: 220px;
        transition: left 0.3s ease;
        z-index: 999;
    }
    .sidebar.show {
        left: 0;
    }
    .main-content {
        margin-left: 0;
        padding-top: 70px; /* Space for toggle button */
    }
    .toggle-btn {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        background: #004d40;
        color: #fff;
        padding: 10px 15px;
        border-radius: 4px;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
    }
}

/* Table Responsive */
@media screen and (max-width: 768px) {
    .table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    .d-flex {
        flex-direction: column;
        gap: 10px;
    }
}
.admin{
    margin-left:50px;
}
    </style>
</head>
<body>
<div class="toggle-btn" id="toggleSidebar">
    <i class="fas fa-bars"></i>
</div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="admin">Admin Panel</h3>
        <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="manage-posts.php"><i class="fas fa-newspaper"></i> Manage Posts</a>
        <a href="manage-ads.php"><i class="fas fa-ad"></i> Manage Ads</a>
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Manage Advertisements</h2>
        <div class="d-flex mb-4">
            <button class="btn btn-custom me-3" data-bs-toggle="modal" data-bs-target="#addAdModal">Add Advertisement</button>
            <button class="btn btn-outline-secondary">Manage Ads</button>
        </div>

        <!-- Ads Table -->
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Ad Image</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; foreach ($ads as $ad): ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td>
                            <img src="../assets/images/<?php echo htmlspecialchars($ad['image']); ?>" alt="Ad" width="120" height="120"></td>
                        <td><a href="<?php echo htmlspecialchars($ad['link']); ?>" target="_blank"><?php echo htmlspecialchars($ad['link']); ?></a></td>
                      <td class="table-actions">
    <a href="#" class="edit" 
       data-id="<?php echo $ad['id']; ?>" 
       data-link="<?php echo htmlspecialchars($ad['link']); ?>" 
       data-image="<?php echo htmlspecialchars($ad['image']); ?>" 
       data-bs-toggle="modal" data-bs-target="#editAdModal">edit</a>

    <a href="manage-ads.php?delete=<?php echo $ad['id']; ?>" 
       class="delete" onclick="return confirm('Are you sure?');">delete</a>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Ad Modal -->
    <div class="modal fade" id="addAdModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Advertisement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Ad Link</label>
                            <input type="text" name="link" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ad Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-custom">Add Advertisement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Ad Modal -->
<div class="modal fade" id="editAdModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Edit Advertisement</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label class="form-label">Ad Link</label>
            <input type="text" name="link" id="edit_link" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Ad Image</label><br>
            <img id="edit_preview" src="" alt="Preview" width="120" height="120"><br><br>
            <input type="file" name="image" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-custom">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('show');
});
</script>
<script>
document.querySelectorAll('.edit').forEach(btn => {
    btn.addEventListener('click', function () {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_link').value = this.dataset.link;
        document.getElementById('edit_preview').src = "../assets/images/" + this.dataset.image;
    });
});
</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

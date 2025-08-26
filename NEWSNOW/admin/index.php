<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: #f8f9fa;
      font-family: 'Poppins', sans-serif;
    }
    /* Sidebar */
    .sidebar {
      width: 250px;
      background: #6f42c1;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
      color: #fff;
      transition: all 0.3s ease;
      z-index: 1050;
    }
    .sidebar a {
      display: block;
      color: #fff;
      padding: 12px;
      text-decoration: none;
      margin: 5px 10px;
      border-radius: 4px;
    }
    .sidebar a:hover {
      background: #5a32a3;
    }

    /* Dashboard Content */
    .dashboard-content {
      margin-left: 260px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }

    /* Hamburger button (mobile only) */
    .hamburger {
      font-size: 24px;
      cursor: pointer;
      color: #6f42c1;
      margin-bottom: 15px;
      display: none; /* hide on desktop */
    }

    /* Mobile view */
    @media(max-width: 768px) {
      .sidebar {
        left: -250px; /* hidden by default */
      }
      .sidebar.active {
        left: 0; /* show when active */
      }
      .dashboard-content {
        margin-left: 0; /* full width */
      }
      .hamburger {
        display: inline-block; /* visible only on mobile */
      }
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 10px;
      padding: 20px;
    }
    .card i {
      font-size: 30px;
      color: #fff;
    }
    .card-title {
      font-size: 18px;
      margin-top: 10px;
    }
    .bg-purple { background: #6f42c1; color: #fff; }
    .bg-blue { background: #0d6efd; color: #fff; }
    .bg-green { background: #198754; color: #fff; }
  </style>
</head>
<body>
<?php
include 'check_auth.php';
include '../includes/db.php';
require_once __DIR__ . '/../includes/db.php';

// Safe defaults
$totalPosts = 0;
$totalAds   = 0;

try {
    $totalPosts = (int) $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
    $totalAds   = (int) $pdo->query("SELECT COUNT(*) FROM advertisements")->fetchColumn();
} catch (Throwable $e) {
    // error_log($e->getMessage());
}
?>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <h3 class="text-center mb-4">Admin Panel</h3>
    <a href="index.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="manage-posts.php"><i class="fas fa-newspaper"></i> Manage Posts</a>
    <a href="manage-ads.php"><i class="fas fa-ad"></i> Manage Ads</a>
    <a href="#"><i class="fas fa-user-cog"></i> Settings</a>
    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Dashboard -->
  <div class="dashboard-content">
    <!-- Hamburger for mobile -->
    <span class="hamburger d-md-none" id="hamburger"><i class="fas fa-bars"></i></span>

    <h2 class="mb-4">Dashboard Overview</h2>

    <div class="row">
      <div class="col-md-4 col-12 mb-3">
        <div class="card bg-purple">
          <i class="fas fa-newspaper"></i>
          <h3 class="card-title">Total Posts</h3>
          <h2><?php echo $totalPosts; ?></h2>
        </div>
      </div>
      <div class="col-md-4 col-12 mb-3">
        <div class="card bg-blue">
          <i class="fas fa-ad"></i>
          <h3 class="card-title">Total Ads</h3>
          <h2><?php echo $totalAds; ?></h2>
        </div>
      </div>
      <div class="col-md-4 col-12 mb-3">
        <div class="card bg-green">
          <i class="fas fa-users"></i>
          <h3 class="card-title">Visitors</h3>
          <h2>12,345</h2>
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="row mt-4">
      <div class="col-md-6 col-12 mb-3">
        <div class="card p-3">
          <h4>Posts Statistics</h4>
          <canvas id="postsChart"></canvas>
        </div>
      </div>
      <div class="col-md-6 col-12 mb-3">
        <div class="card p-3">
          <h4>Traffic Sources</h4>
          <canvas id="trafficChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS & Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Sidebar Toggle for Mobile -->
  <script>
    document.getElementById("hamburger").addEventListener("click", function () {
      document.getElementById("sidebar").classList.toggle("active");
    });
  </script>

  <!-- Chart Example -->
  <script>
    const ctx1 = document.getElementById('postsChart').getContext('2d');
    new Chart(ctx1, {
      type: 'bar',
      data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        datasets: [{
          label: 'Posts',
          data: [5, 10, 8, 15, 7],
          backgroundColor: '#6f42c1'
        }]
      }
    });

    const ctx2 = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Google', 'Facebook', 'Direct'],
        datasets: [{
          data: [60, 25, 15],
          backgroundColor: ['#0d6efd', '#6f42c1', '#198754']
        }]
      }
    });
  </script>
</body>
</html>

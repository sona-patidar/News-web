<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact - The News Paper</title>
  <link rel="stylesheet" href="../assets/css/style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    header {
      background: #d50000;
      color: white;
      padding: 15px;
      text-align: center;
    }

    main {
      padding: 30px;
      text-align: center;
    }

    .whatsapp-button {
      background-color: #25d366;
      color: white;
      padding: 15px 25px;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      text-decoration: none;
      display: inline-block;
      margin-top: 20px;
      transition: 0.3s ease;
    }

    .whatsapp-button:hover {
      background-color: #1ebd5a;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      background: #fff;
      box-shadow: 0 -1px 5px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 10px 0;
      z-index: 9999;
    }

    .bottom-nav a {
      color: #444;
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
      color: #d50000;
    }

    @media (min-width: 769px) {
      .bottom-nav {
        display: none;
      }
    }
  </style>
</head>
<body>

  <header>
    <h1>Contact Us</h1>
  </header>

  <main>
    <p>For quick support or news submission, contact us directly on WhatsApp.</p>
    <a href="https://wa.me/917089893535" class="whatsapp-button" target="_blank">
      <i class="fab fa-whatsapp"></i> Message on WhatsApp
    </a>
  </main>

 <div class="bottom-nav">
    <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i><span>Home</span>
    </a>
    <!-- <a href="../navbar/category.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
        <i class="fas fa-th-large"></i><span>Categories</span>
    </a> -->
  <a href="../navbar/facebook.html" class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
<i class="fab fa-facebook-f"></i><span>Facebook</span>
    </a>

    <a href="../navbar/contact.php" target="_blank">
    <i class="fab fa-whatsapp"></i><span>WhatsApp</span>
    </a>
   
    <a href="../navbar/trending.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'trending.php' ? 'active' : ''; ?>">
        <i class="fas fa-fire"></i><span>Trending</span>
    </a>
   
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CodePen - Sidebar Menu</title>
  <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap'>
  <link rel="stylesheet" href="./style.css">
  <style>
    <?php include ('header.css'); ?>
  </style>
</head>
<body>
<nav class="sidebar">
  <header>
    <div class="image-text">
      <div class="text logo-text">
      <h1 class="names" style="font-size: 30px;">Welcome, Admin</h1>
      </div>
    </div>
    <i class='bx bx-chevron-right toggle'></i>
  </header>
  <div class="menu-bar">
    <div class="menu">
      
      <ul class="menu-links">
        <li class="nav-link">
          <a href="index.php">
            <i class='bx bx-home-alt icon'></i>
            <span class="text nav-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="add_item.php">
            <i class='bx bx-list-plus icon'></i>
            <span class="text nav-text">Add Item</span>
          </a>
        </li>
        <li class="nav-link">
          <a href="manage_orders.php">
            <i class='bx bx-pie-chart-alt icon'></i>
            <span class="text nav-text">Manage Orders</span>
          </a>
        </li>
       
        <li class="nav-link">
          <a href="manage_category.php">
            <i class='bx bx-category-alt icon'></i>
            <span class="text nav-text">Manage Category</span>
          </a>
        </li>
        
        <li class="nav-link">
          <a href="#">
            <i class='bx bx-bar-chart-alt-2 icon'></i>
            <span class="text nav-text">Revenue</span>
          </a>
        </li>
        <!-- <li class="nav-link">
          <a href="#">
            <i class='bx bx-pie-chart-alt icon'></i>
            <span class="text nav-text">Analytics</span>
          </a>
        </li> -->
      </ul>
    </div>
    <div class="bottom-content">
      <li class="logout">
        <a href="logout.php">
          <i class='bx bx-log-out icon'></i>
          <span class="text nav-text">Logout</span>
        </a>
      </li>
    </div>
  </div>
</nav>
<script>
  const body = document.querySelector("body"),
    sidebar = body.querySelector("nav"),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = body.querySelector(".toggle-switch"),
    modeText = body.querySelector(".mode-text");
  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
  });
  searchBtn.addEventListener("click", () => {
    sidebar.classList.remove("close");
  });
  modeSwitch.addEventListener("click", () => {
    body.classList.toggle("dark");
    if (body.classList.contains("dark")) {
      modeText.innerText = "Light mode";
    } else {
      modeText.innerText = "Dark mode";
    }
  });
</script>
</body>
</html>

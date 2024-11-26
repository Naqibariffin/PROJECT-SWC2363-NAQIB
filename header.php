<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

if (isset($_SESSION['users_email'])) {
    header("Location: myprofile.php");
    exit;
}

$cartCount = 0;
if (isset($_SESSION['carts'])) {
    $cartCount = array_sum($_SESSION['carts']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POPBIQ - Toy Shop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>
  <header>
    <nav>
      <img src="logo.png" alt="QibShop Logo" class="logo">
      <ul class="menu">
        <li><a href="products.php"><b>Shop</b></a></li>
        <li class="dropdown">
          <a href="#"><b>About Us</b></a>
          <ul class="dropdown-content">
            <li><a href="aboutus.php">Our Story & Team</a></li>
            <li><a href="storelocation.php">Store Location</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#"><b>Contact Us</b></a>
          <ul class="dropdown-content">
            <li><a href="faq.php">FAQ</a></li>
          </ul>
        </li>
      </ul>


      <div class="user-icons">
        <a href="carts.php" class="cart-icon">
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-count"><?php echo $cartCount > 0 ? $cartCount : ''; ?></span>
        </a> 
        <a href="customersignin.php"><i class="fas fa-user"></i></a> <!-- Sign-in icon -->
      </div>
    </nav>
  </header>
  <div class="free-shipping-banner">
    <p>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Free Shipping on Orders over RM100!&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;</p>
</div>

</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-color: #f0f0f5; 
  color: #333;
}

header {
  background-color: #fff;
  border-bottom: 2px solid #ddd;
}

header nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
}

.logo {
  width: 150px;
}

.menu {
  list-style: none;
  display: flex;
  gap: 20px;
}

.menu li {
  position: relative;
}

.menu li a {
  text-decoration: none;
  color: #333;
  padding: 10px;
  transition: color 0.3s;
}

.menu li a:hover {
  color: #0056b3;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: white;
  border: 1px solid #ddd;
  min-width: 160px;
  top: 100%;
  left: 0;
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown-content li {
  list-style: none;
}

.dropdown-content a {
  color: #333;
  padding: 10px;
  display: block;
  text-decoration: none;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
}

.user-icons a {
  margin-left: 15px;
  color: #333;
  text-decoration: none;
}

.user-icons a:hover {
  color: #0056b3;
}

.user-icons i {
  font-size: 20px;
}


.cart-icon {
  position: relative;
  display: inline-block;
  margin-left:10px;
}


.cart-count {
  position: absolute;
  top: -13px; 
  right: -18px; 
  color: #FF0000;
  font-size: 15px; 
  font-weight:bold;
  padding: 3px; 
  border-radius: 50%;
  min-width: 15px; 
  height: 15px; 
  text-align: center;
  line-height: 15px;
}

/* Free Shipping Banner Styles */

.free-shipping-banner {
    background-color: #0056b3; 
    color: #fff; /* White text */
    font-size: 0.8rem; /* Slightly larger text */
    text-align: center;
    padding: 10px 0;
    position:
}

/* Scroll Animation */
.free-shipping-banner p {
    display: inline-block; /* Ensures text stays in a row */
    white-space: nowrap; /* Prevent the text from wrapping */
    animation: scrollingText 10s linear infinite; /* Apply scrolling animation */
}

/* Keyframe for scrolling text from right to left */
@keyframes scrollingText {
    0% {
        transform: translateX(100%); /* Start off-screen on the right */
    }
    100% {
        transform: translateX(-100%); /* End off-screen on the left */
    }
}
</style>

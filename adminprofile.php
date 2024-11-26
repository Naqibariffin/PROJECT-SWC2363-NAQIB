<?php
// Include the database connection
include 'db.php';

// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to admin sign-in page if not logged in
    header("Location: adminsignin.php");
    exit;
}

// Retrieve the logged-in admin's details from the database
$admin_id = $_SESSION['admin_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<title>POPBIQ - Toy Shop</title>
<header>
    <nav>
    <img src="logo.png" alt="QibShop Logo" class="logo">
        <ul class="menu">
            <li><a href="adminprofile.php"><b>My Profile</b></a></li>
            <li><a href="manageproducts.php"><b>Manage Products</b></a></li>
            <li><a href="manageorders.php"><b>Manage Orders</b></a></li>
            <li><a href="manageusers.php"><b>Manage Users</b></a></li>
        </ul>

        <!-- User Icons -->
        <div class="user-icons">
            <a href="adminlogout.php"><i class="fas fa-sign-out-alt"></i></a> <!-- Logout icon -->
        </div>
    </nav>
</header>

<!-- Profile Page Content -->
<div class="profile-container">
    <div class="profile-wrapper">
        <h2>Welcome to <?php echo htmlspecialchars($admin['username']); ?>'s Profile</h2>
        
        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        
        <div class="profile-details">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
            <p><strong>Password:</strong> <?php echo htmlspecialchars($admin['password']); ?></p>
        </div>

        <div class="profile-actions">
            <a href="editadminprofile.php">Edit Profile</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Include footer -->

<style>
   @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
/* General Styles */
body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-color: #f0f0f5; /* Light gray background */
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

/* Profile Page Styles */
.profile-container {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.profile-wrapper {
  text-align: center;
}

.profile-wrapper h2 {
  font-size: 24px;
  color: #333;
}

.profile-details {
  margin-top: 20px;
  text-align: left;
  font-size: 18px;
  color: #333;
}

.profile-details p {
  margin: 8px 0;
}

.profile-actions a {
  display: inline-block;
  margin: 10px 20px;
  padding: 10px 20px;
  background-color: white;
  color: black;
  text-decoration: none;
  border-radius: 5px;
  transition: background-color 0.3s;
  border: 2px solid black;
  cursor: pointer;
}

.profile-actions a:hover {
    background-color: #0056b3;
    color: white;
}

.profile-actions a:active {
  background-color: #003f7f;
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-container {
    padding: 15px;
  }
}
</style>

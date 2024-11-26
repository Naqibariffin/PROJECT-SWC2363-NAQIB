<?php
// Include the header and database connection
include 'db.php';

session_start();
// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: adminsignin.php");
    exit;
}

// Retrieve the logged-in admin's details from the database
$admin_id = $_SESSION['admin_id'];

// Initialize error/success messages
$error = $success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the new values for username, email, and password
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_password = trim($_POST['password']);

    try {
        // Validate the input
        if (empty($new_username) || empty($new_email) || empty($new_password)) {
            $error = "Please fill in all fields.";
        } else {
            // Update the admin's details in the database
            $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$new_username, $new_email, $new_password, $admin_id]);

            // Set success message
            $success = "Profile updated successfully.";

            // Update the session variables
            $_SESSION['username'] = $new_username;
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Fetch the admin's current details
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

<!-- Edit Profile Page Content -->
<div class="edit-profile-container">
    <div class="edit-profile-wrapper">
        <h2>Edit <?php echo htmlspecialchars($admin['username']); ?>'s Profile</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>
            "; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <form method="POST" action="">
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" value="<?php echo htmlspecialchars($admin['password']); ?>" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Save Changes">
            </div>
        </form>

        <div class="back-link">
            <a href="adminprofile.php">Back to Profile</a>
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
  color:#0056b3;
}

.user-icons a {
  margin-left: 15px;
  color: #333;
  text-decoration: none;
}

.user-icons a:hover {
  color:#0056b3;
}

.user-icons i {
  font-size: 20px;
}

/* Edit Profile Page Styles */
.edit-profile-container {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.edit-profile-wrapper {
  text-align: center;
}

.edit-profile-wrapper h2 {
  font-size: 24px;
  color: #333;
}

.input-box {
  margin: 20px 0;
  text-align: left;
}

.input-box label {
  font-size: 16px;
  color: #333;
  margin-bottom: 8px;
  display: block;
}

.input-box input {
  width: 100%;
  padding: 10px;
  font-size: 17px;
  border: 1.5px solid black;
  border-radius: 6px;
  background-color: white;
  color: black;
  transition: background-color 0.3s ease;
}

.input-box input:focus,
.input-box input:hover {
  background-color: mintcream;
  color: black;
}

.input-box.button input {
  background-color: white;
  color: black;
  border: 2px solid black;
  cursor: pointer;
  transition: background-color 0.3s;
}

.input-box.button input:hover {
  background-color: #0056b3;
  color: white;
}

/* Back Link */
.back-link a {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  background-color: black;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  border: 2px solid black;
  cursor: pointer;
  transition: background-color 0.3s;
}

.back-link a:hover {
    background-color: #0056b3;
    color: white;
}

.back-link a:active {
  background-color: #003f7f;
}

/* Responsive Design */
@media (max-width: 768px) {
  .edit-profile-container {
    padding: 15px;
  }
}

</style>
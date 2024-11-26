<?php
include 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $new_username = trim($_POST['username']);
  $new_email = trim($_POST['email']);
  $current_password = trim($_POST['current_password']);
  $new_password = trim($_POST['new_password']);
  $confirm_password = trim($_POST['confirm_password']);

  try {
      if (empty($new_username) || empty($new_email)) {
          $error = "Please fill in all fields.";
      } else {
          if (!empty($new_password) && $new_password !== $confirm_password) {
              $error = "New password and confirmation password do not match.";
          } else {
              $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
              $stmt->execute([$user_id]);
              $user = $stmt->fetch(PDO::FETCH_ASSOC);

              if (!password_verify($current_password, $user['password'])) {
                  $error = "Current password is incorrect.";
              } else {
                  if (!empty($new_password)) {
                      $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                      $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
                      $stmt->execute([$new_username, $new_email, $new_password_hashed, $user_id]);

                      $success = "Profile updated successfully.";
                  } else {
                      $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                      $stmt->execute([$new_username, $new_email, $user_id]);

                      $success = "Profile updated successfully.";
                  }

                  $_SESSION['username'] = $new_username;
              }
          }
      }
  } catch (PDOException $e) {
      $error = "Database error: " . $e->getMessage();
  }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>
<title>POPBIQ - Toy Shop</title>
<header>
    <nav>
    <img src="logo.png" alt="QibShop Logo" class="logo">
        <ul class="menu">
            <li><a href="myprofile.php"><b>My Profile</b></a></li>
            <li><a href="orders.php"><b>Track My Order</b></a></li>
        </ul>

        <!-- User Icons -->
        <div class="user-icons">
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a> 
        </div>
    </nav>
</header>

<div class="edit-profile-container">
    <div class="edit-profile-wrapper">
        <h2>Edit Your Profile</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <form method="POST" action="">
            <div class="input-box">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <div class="input-box">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" placeholder="Enter current password" required>
            </div>
            <div class="input-box">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
            </div>
            <div class="input-box">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
            </div>
            
            <div class="input-box button">
                <input type="submit" value="Save Changes">
            </div>
        </form>

        <div class="back-link">
            <a href="myprofile.php">Back to Profile</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 

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
  color: #007BFF;
}


.user-icons a {
  margin-left: 15px;
  color: #333;
  text-decoration: none;
}

.user-icons a:hover {
  color: #007BFF;
}

.user-icons i {
  font-size: 20px;
}

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


@media (max-width: 768px) {
  .edit-profile-container {
    padding: 15px;
  }
}

</style>

<?php
include 'header.php';  
include 'db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email']);
    $password = trim($_POST['password']);

    $error = $success = '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];
            $success = "Login successful. Redirecting to your myprofile...";
            header("Location: myprofile.php"); 
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="center-container">
    <div class="wrapper">
        <h2>Customer Sign In</h2>
        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" name="username_or_email" placeholder="Enter your username" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Sign In">
            </div>
        </form>
        <div class="text">
            <h3>Don't have an account?  <a href="register.php"> Sign Up</a></h3>
            <h3>Are you an admin?  <a href="adminsignin.php"> Sign In</a></h3>
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
  background-color: #f4f4f4;
}

.wrapper {
  max-width: 530px;
  margin: 50px auto;
  background: #fff;
  padding: 34px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.wrapper h2 {
  font-size: 22px;
  font-weight: 600;
  color: #333;
  position: relative;
  text-align: center;
}

.wrapper h2::before {
  content: '';
  position: absolute;
  left: 50%;
  bottom: -10px;
  transform: translateX(-50%);
  height: 3px;
  width: 28px;
  border-radius: 12px;
  background: black;
}

form {
  margin-top: 30px;
}

.input-box {
  height: 42px;
  margin: 18px 0;
  padding-bottom: 20px;
}

.input-box input {
  height: 100%;
  width: 100%;
  padding: 10px;
  font-size: 17px;
  border: 1.5px solid black;
  border-bottom-width: 5px;
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

.text h3 {
  text-align: center;
  color: #333;
}

.text h3 a {
  color: #4070f4;
  text-decoration: none;
}

.text h3 a:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .wrapper {
    padding: 20px;
  }
}
</style>

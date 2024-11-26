<?php
include 'header.php';  
include 'db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);  
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $acceptTerms = isset($_POST['accept_terms']) ? true : false;

    $error = $success = '';

    if (!$acceptTerms) {
        $error = "You must accept the terms and conditions.";
    } elseif ($password != $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        try {
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?"); 
            $checkStmt->execute([$username, $email]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                $error = "Username or email already exists. Please try another.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");  
                if ($stmt->execute([$username, $email, $hashedPassword])) {
                    $success = "Customer registered successfully!";
                } else {
                    $error = "There was an error while registering the customer. Please try again.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="center-container">
    <div class="wrapper">
        <h2>Customer Sign Up</h2>
        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <div class="policy">
                <input type="checkbox" name="accept_terms" required>
                <label>
                    I accept the <a href="#" id="show-terms">terms and conditions</a>.
                </label>
            </div>
            <div class="input-box button">
                <input type="submit" value="Sign Up">
            </div>
        </form>
        <div class="text">
            <h3>Already have an account? <a href="customersignin.php">Sign In</a></h3>
        </div>
    </div>
</div>


<div id="terms-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h1>Terms and Conditions</h1>
        <p>Welcome to our platform! By accessing or using this platform, you agree to the following terms and conditions:</p>
        <h2>1. General</h2>
        <p>The content of this website is for your general information and use only. It is subject to change without notice.</p>
        <h2>2. User Obligations</h2>
        <p>By signing up, you agree to provide accurate information and keep your account secure.</p>
        <h2>3. Privacy Policy</h2>
        <p>Your personal information will be protected as per our privacy policy.</p>
        <h2>4. Contact Us</h2>
        <p>If you have any questions, contact us at <a href="mailto:popqibhelp@gmail.com">popqibhelp@gmail.com</a>.</p>
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

/* Policy Styling */
.policy {
  display: flex;
  align-items: center;
  margin: 10px 0;
}

.policy input {
  width: 20px;
  height: 20px;
  margin-right: 10px;
}

.policy h3 {
  color: #707070;
  font-size: 14px;
  font-weight: 500;
  margin: 0;
}

.policy h3 a {
  color: #4070f4;
  text-decoration: none;
}

.policy h3 a:hover {
  text-decoration: underline;
}

/* Button Styling */
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

.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 20px;
    border-radius: 10px;
    max-width: 600px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.close-btn {
    float: right;
    font-size: 20px;
    cursor: pointer;
}

.close-btn:hover {
    color: red;
}
</style>

<script>
const modal = document.getElementById('terms-modal');
const showTerms = document.getElementById('show-terms');
const closeBtn = document.querySelector('.close-btn');

showTerms.addEventListener('click', function(e) {
    e.preventDefault();
    modal.style.display = 'block';
});

closeBtn.addEventListener('click', function() {
    modal.style.display = 'none';
});

window.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});
</script>

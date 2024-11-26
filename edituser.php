<?php
// Include the header and database connection
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to admin login page if not logged in
    header("Location: adminsignin.php");
    exit;
}

// Initialize error/success messages
$error = $success = '';

// Get the user ID from the query string
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Fetch user details from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching user: " . $e->getMessage();
    }
} else {
    $error = "User ID is required.";
}

// Handle form submission to update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validate input
    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        try {
            // Update the user in the database
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_id]);
            $success = "User updated successfully.";
        } catch (PDOException $e) {
            $error = "Error updating user: " . $e->getMessage();
        }
    }
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

<!-- Edit User Page Content -->
<div class="edit-user-container">
<button type="button" onclick="window.location.href='manageusers.php';" class="back-btn">Back</button>
    <div class="edit-user-wrapper">
        <h2>Edit User</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <!-- User Edit Form -->
        <form method="POST" action="edituser.php?id=<?php echo htmlspecialchars($user_id); ?>" class="edit-user-form">
            <div class="form-group">
                <label for="name">User Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">User Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>

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

/* Edit User Page Styles */
.edit-user-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.edit-user-wrapper h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.edit-user-form .form-group {
    margin-bottom: 20px;
}

.edit-user-form label {
    font-size: 16px;
    color: #333;
    display: block;
    margin-bottom: 8px;
}

.edit-user-form input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.save-btn {
    padding: 12px 20px;
    background-color: black;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.save-btn:hover {
    background-color: #0056b3;
}

.back-btn {
    background-color: black;
    color: white;
    font-size: 10px;
    padding: 10px 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.back-btn:hover {
    background-color: #0056b3;
}
</style>

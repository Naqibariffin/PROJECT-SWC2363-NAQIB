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

// Retrieve the logged-in admin's details from the database
$admin_id = $_SESSION['admin_id'];

// Initialize error/success messages
$error = $success = '';

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = "User deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting user: " . $e->getMessage();
    }
}

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Fetch users based on search query
try {
    $sql = "SELECT * FROM users WHERE 1=1";

    // If search query is set, add it to the query
    if ($search_query) {
        $sql .= " AND (username LIKE :search OR email LIKE :search)";
    }

    $stmt = $pdo->prepare($sql);

    // Bind search parameter
    if ($search_query) {
        $search_like = "%$search_query%";
        $stmt->bindParam(':search', $search_like);
    }

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching users: " . $e->getMessage();
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

<!-- Manage Users Page Content -->
<div class="manage-users-container">
    <div class="manage-users-wrapper">
        <h2>Manage Users</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <!-- Search Bar -->
        <form method="GET" action="manageusers.php" class="search-form">
            <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <table class="user-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="action-buttons">
                            <a href="edituser.php?id=<?php echo $user['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> <b>Edit</b></a>
                            <a href="manageusers.php?delete_id=<?php echo $user['id']; ?>" class="delete-btn"><i class="fas fa-trash"></i> <b>Delete</b></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Include footer -->

<style>
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
        color: #007BFF;
    }

    .user-icons i {
        font-size: 20px;
    }

    /* Manage Users Page Styles */
    .manage-users-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .manage-users-wrapper {
        text-align: left;
    }

    .manage-users-wrapper h2 {
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    .add-user-btn {
        display: inline-block;
        margin-bottom: 20px;
        padding: 10px 20px;
        background-color: black;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 600;
        transition: background-color 0.3s;
    }

    .add-user-btn:hover {
        background-color: #0056b3;
    }

    .search-form {
        margin-bottom: 20px;
    }

    .search-form input {
        width: 300px;
        border: 1px solid #ccc;
        margin-right: 10px;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
    }

    .search-form button {
        border: none;
        background-color: black;
        color: white;
        cursor: pointer;
        padding: 10px 15px;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .search-form button:hover {
        background-color: #0056b3;
    }

    /* User Table Styles */
    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .user-table th,
    .user-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .user-table th {
        background-color: #f8f8f8;
        font-weight: bold;
    }

    .user-table td {
        background-color: #fff;
    }

    .user-table td .edit-btn,
    .user-table td .delete-btn {
        display: inline-block;
        padding: 8px 15px;
        margin-right: 10px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .user-table td .edit-btn {
        color: black;
        border: 1px solid black;
    }

    .user-table td .edit-btn:hover {
        background-color: #0056b3;
        color: white;
    }

    .user-table td .delete-btn {
        color: red;
        border: 1px solid red;
    }

    .user-table td .delete-btn:hover {
        background-color: red;
        color: white;
    }

    /* Footer */
    footer {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 20px;
    }
</style>

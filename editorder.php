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

// Retrieve the order ID from the URL parameter
if (!isset($_GET['id'])) {
    header("Location: manageorders.php");
    exit;
}

$order_id = (int)$_GET['id']; // Sanitize the ID

// Initialize error/success messages
$error = $success = '';

// Fetch order details
try {
    $stmt = $pdo->prepare("SELECT orders.*, products.name AS product_name FROM orders 
                           JOIN products ON orders.product_id = products.id WHERE orders.id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        $error = "Order not found.";
    }
} catch (PDOException $e) {
    $error = "Error fetching order details: " . $e->getMessage();
}

// Handle order update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $new_status = htmlspecialchars($_POST['status']); // Sanitize input

    try {
        // Update only the status field
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $order_id]);
        $success = "Order status updated successfully.";

        // Refresh order details
        $stmt = $pdo->prepare("SELECT orders.*, products.name AS product_name FROM orders 
                               JOIN products ON orders.product_id = products.id WHERE orders.id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error updating order status: " . $e->getMessage();
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
        <div class="user-icons">
            <a href="adminlogout.php"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </nav>
</header>

<div class="edit-order-container">
    <div class="edit-order-wrapper">
    <button type="button" onclick="window.location.href='manageorders.php';" class="back-btn">← Back</button>
        <h2>Edit Order</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <?php if (!empty($order)): ?>
        <form method="POST" action="editorder.php?id=<?php echo $order_id; ?>" class="edit-order-form">
            <label for="order_id">Order ID</label>
            <input type="text" id="order_id" value="<?php echo htmlspecialchars($order['id']); ?>" disabled>

            <label for="customer_name">Customer Name</label>
            <input type="text" id="customer_name" value="<?php echo htmlspecialchars($order['customer_name']); ?>" disabled>

            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" value="<?php echo htmlspecialchars($order['product_name']); ?>" disabled>

            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($order['quantity']); ?>" disabled>

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="Waiting For Shipped" <?php echo $order['status'] === 'Waiting For Shipped' ? 'selected' : ''; ?>>Waiting For Shipped</option>
                <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                <option value="Completed" <?php echo $order['status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo $order['status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
            </select>

            <button type="submit" name="update_order" class="update-btn">Update Order</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<style>
      @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    background-color: #f7f8fc;
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
    .edit-order-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .edit-order-wrapper h2 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .edit-order-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .edit-order-form label {
        font-weight: bold;
    }

    .edit-order-form input,
    .edit-order-form select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .edit-order-form .update-btn {
        padding: 10px 15px;
        background-color: black;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .edit-order-form .update-btn:hover {
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

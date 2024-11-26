<?php
// Include the header and database connection
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];

// Initialize error/success messages
$error = $success = '';

// Handle order deletion with confirmation
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];

    if (!isset($_GET['confirm_delete']) || $_GET['confirm_delete'] !== 'true') {
        $error = "Are you sure you want to delete this order? Please confirm.";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = "Order deleted successfully.";
        } catch (PDOException $e) {
            $error = "Error deleting order: " . $e->getMessage();
        }
    }
}

// Handle order status change
if (isset($_GET['status_id']) && isset($_GET['status'])) {
    $status_id = (int) $_GET['status_id'];
    $status = htmlspecialchars($_GET['status']);

    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $status_id]);
        $success = "Order status updated successfully.";
    } catch (PDOException $e) {
        $error = "Error updating order status: " . $e->getMessage();
    }
}

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = htmlspecialchars($_GET['search']);
}

// Pagination setup
$orders_per_page = 10; // Number of orders per page
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1; // Current page
$offset = ($page - 1) * $orders_per_page;

// Fetch total number of orders
try {
    $count_query = "SELECT COUNT(*) AS total FROM orders WHERE 1=1";
    if ($search_query) {
        $count_query .= " AND (customer_name LIKE :search OR id LIKE :search)";
    }
    $count_stmt = $pdo->prepare($count_query);
    if ($search_query) {
        $search_like = "%$search_query%";
        $count_stmt->bindParam(':search', $search_like);
    }
    $count_stmt->execute();
    $total_orders = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $error = "Error counting orders: " . $e->getMessage();
    $total_orders = 0;
}

// Calculate total pages
$total_pages = ceil($total_orders / $orders_per_page);

// Fetch orders for the current page
try {
    $sql = "SELECT orders.*, products.name AS product_name FROM orders
            JOIN products ON orders.product_id = products.id
            WHERE 1=1";

    if ($search_query) {
        $sql .= " AND (orders.customer_name LIKE :search OR orders.id LIKE :search)";
    }
    $sql .= " LIMIT :offset, :limit";

    $stmt = $pdo->prepare($sql);
    if ($search_query) {
        $stmt->bindParam(':search', $search_like);
    }
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $orders_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching orders: " . $e->getMessage();
    $orders = [];
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

<!-- Manage Orders Page -->
<div class="manage-orders-container">
    <div class="manage-orders-wrapper">
        <h2>Manage Orders</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <!-- Search Bar -->
        <form method="GET" action="manageorders.php" class="search-form">
            <input type="text" name="search" placeholder="Search orders..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                        <td><?php echo isset($order['product_name']) ? htmlspecialchars($order['product_name']) : 'Product not found'; ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td class="action-buttons">
                            <a href="editorder.php?id=<?php echo $order['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> <b>Edit</b></a>
                            <a href="manageorders.php?delete_id=<?php echo $order['id']; ?>&confirm_delete=true" class="delete-btn"><i class="fas fa-trash"></i> <b>Delete</b></a>
                        </td>


                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="manageorders.php?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_query); ?>" class="pagination-link">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="manageorders.php?page=<?php echo $i; ?>&search=<?php echo urlencode($search_query); ?>"
           class="pagination-link <?php echo ($i === $page) ? 'active' : ''; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="manageorders.php?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_query); ?>" class="pagination-link">Next</a>
    <?php endif; ?>
</div>
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

    /* Manage Orders Page Styles */
    .manage-orders-container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .manage-orders-wrapper {
        text-align: left;
    }

    .manage-orders-wrapper h2 {
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    .search-form {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .search-form input {
        width: 300px;
    border: 1px solid #ccc;
    margin-right: 10px;
    }

    .search-form button {
        border: none;
    background-color: black;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
    }

    .search-form button:hover {
    background-color: #0056b3;
    }
    .search-form {
    margin-bottom: 20px;
    }
    .search-form input,
    .search-form button {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    }

    /* Order Table Styles */
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .order-table th,
    .order-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .order-table th {
        background-color: #f8f8f8;
        font-weight: bold;
    }

    .order-table td {
        background-color: #fff;
    }

    .order-table td .edit-btn,
    .order-table td .delete-btn {
        display: inline-block;
        padding: 8px 15px;
        margin-right: 10px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .order-table td .edit-btn {
        color: black;
        border: 1px solid black;
    }

    .order-table td .edit-btn:hover {
        background-color: #0056b3;
        color: white;
    }

    .order-table td .delete-btn {
        color: red;
        border: 1px solid red;
    }

    .order-table td .delete-btn:hover {
        background-color: red;
        color: white;
    }

    /* Error and Success Messages */
    .manage-orders-container p {
        font-size: 16px;
        margin: 10px 0;
    }

    p[style*="color: red"] {
        color: #e74c3c !important;
    }

    p[style*="color: green"] {
        color: green;!important;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .search-form {
            flex-direction: column;
        }

        .search-form input,
        .search-form button {
            width: 100%;
            margin-bottom: 10px;
        }

        .order-table th, .order-table td {
            padding: 10px;
        }
    }

    .pagination {
    margin: 20px 0;
    text-align: center;
}

.pagination-link {
    display: inline-block;
    margin: 0 5px;
    padding: 10px 15px;
    border: 1px solid #ddd;
    color: #333;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.pagination-link.active {
    background-color: #0056b3;
    color: #fff;
    border-color: #0056b3;
}

.pagination-link:hover {
    background-color: #333;
    color: #fff;
    border-color: #333;
}


    @media (max-width: 480px) {
        .logo {
            width: 120px;
        }

        .menu {
            gap: 5px;
        }

        .menu li a {
            font-size: 14px;
        }

        .order-table th,
        .order-table td {
            font-size: 14px;
        }
    }
</style>

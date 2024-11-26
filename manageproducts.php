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

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$delete_id]);
        $success = "Product deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting product: " . $e->getMessage();
    }
}

// Handle category filter
$category_filter = '';
if (isset($_GET['category'])) {
    $category_filter = $_GET['category'];
}

// Handle search query
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Fetch all categories
try {
    $stmt = $pdo->prepare("SELECT DISTINCT category FROM products");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching categories: " . $e->getMessage();
}

// Fetch products based on category filter and search query
try {
    $sql = "SELECT * FROM products WHERE 1=1";

    // If category filter is set, add it to the query
    if ($category_filter) {
        $sql .= " AND category = :category";
    }

    // If search query is set, add it to the query
    if ($search_query) {
        $sql .= " AND (name LIKE :search OR description LIKE :search OR category LIKE :search)";
    }

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    if ($category_filter) {
        $stmt->bindParam(':category', $category_filter);
    }
    if ($search_query) {
        $search_like = "%$search_query%";
        $stmt->bindParam(':search', $search_like);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching products: " . $e->getMessage();
}

// Initialize pagination variables
$items_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Ensure the page is at least 1
$offset = ($page - 1) * $items_per_page;

// Fetch products with pagination
try {
    $sql = "SELECT * FROM products WHERE 1=1";

    // If category filter is set, add it to the query
    if ($category_filter) {
        $sql .= " AND category = :category";
    }

    // If search query is set, add it to the query
    if ($search_query) {
        $sql .= " AND (name LIKE :search OR description LIKE :search OR category LIKE :search)";
    }

    $sql .= " LIMIT :offset, :items_per_page";

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    if ($category_filter) {
        $stmt->bindParam(':category', $category_filter);
    }
    if ($search_query) {
        $search_like = "%$search_query%";
        $stmt->bindParam(':search', $search_like);
    }

    // Bind pagination parameters
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total product count for pagination
    $count_sql = "SELECT COUNT(*) FROM products WHERE 1=1";

    if ($category_filter) {
        $count_sql .= " AND category = :category";
    }

    if ($search_query) {
        $count_sql .= " AND (name LIKE :search OR description LIKE :search OR category LIKE :search)";
    }

    $count_stmt = $pdo->prepare($count_sql);

    if ($category_filter) {
        $count_stmt->bindParam(':category', $category_filter);
    }
    if ($search_query) {
        $count_stmt->bindParam(':search', $search_like);
    }

    $count_stmt->execute();
    $total_items = $count_stmt->fetchColumn();

    $total_pages = ceil($total_items / $items_per_page);

} catch (PDOException $e) {
    $error = "Error fetching products: " . $e->getMessage();
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

<!-- Manage Products Page Content -->
<div class="manage-products-container">
    <div class="manage-products-wrapper">
        <h2>Manage Products</h2>
        <a href="addproducts.php" class="add-product-btn"><i class="fas fa-plus"></i> Add New Product</a>

        <!-- Search Bar -->
        <form method="GET" action="manageproducts.php" class="search-form">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>

        <!-- Category Filter Dropdown -->
        <form method="GET" action="manageproducts.php" class="category-filter-form">
            <label for="category">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()">
                <option value="">Select a category</option>
                <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo htmlspecialchars($category['category']); ?>"
                        <?php echo ($category['category'] == $category_filter) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['category']); ?>
                    </option>
                <?php } ?>
            </select>
        </form>

        <!-- Display notifications -->
        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>


        <table class="product-table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 100px; height: auto;">
                    </td>
                    <td class="action-buttons">
                        <a href="editproduct.php?id=<?php echo $product['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> <b>Edit</b></a>
                        <a href="deleteproduct.php?id=<?php echo $product['id']; ?>" class="delete-btn"><i class="fas fa-trash"></i> <b>Delete</b></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <!-- Pagination Links -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="manageproducts.php?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_query); ?>">Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="manageproducts.php?page=<?php echo $i; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_query); ?>"
           class="<?php echo $i == $page ? 'active' : ''; ?>">
           <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
        <a href="manageproducts.php?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_filter); ?>&search=<?php echo urlencode($search_query); ?>">Next</a>
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

/* Manage Products Page Styles */
.manage-products-container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.manage-products-wrapper {
    text-align: left;
}

.manage-products-wrapper h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.add-product-btn {
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

.add-product-btn:hover {
    background-color: #0056b3;
}

.category-filter-form, .search-form {
    margin-bottom: 20px;
}

.category-filter-form label {
    font-size: 16px;
    color: #333;
    margin-right: 10px;
}

.category-filter-form select,
.search-form input,
.search-form button {
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
}

.category-filter-form select {
    border: 1px solid #ccc;
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

/* Product Table Styles */
.product-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.product-table th,
.product-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.product-table th {
    background-color: #f8f8f8;
    font-weight: bold;
}

.product-table td {
    background-color: #fff;
}

.product-table td .edit-btn,
.product-table td .delete-btn {
    display: inline-block;
    padding: 8px 15px;
    margin-right: 10px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.3s, color 0.3s;
}

.product-table td .edit-btn {
    color: black;
    border: 1px solid black;
}

.product-table td .edit-btn:hover {
    background-color: #0056b3;
    color: white;
}

.product-table td .delete-btn {
    color: red;
    border: 1px solid red;
}

.product-table td .delete-btn:hover {
    background-color: red;
    color: white;
}

/* Action Buttons Styling */
.action-buttons a {
    display: inline-block;
    margin-bottom: 25px;
}

/* Footer */
footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 20px;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .menu {
        flex-direction: column;
        gap: 10px;
    }

    .manage-products-container {
        padding: 20px;
    }

    .product-table th, .product-table td {
        padding: 10px;
    }

    .search-form input,
    .search-form button {
        width: 100%;
        margin-bottom: 10px;
    }

    .category-filter-form select {
        width: 100%;
    }

    .add-product-btn {
        width: 100%;
        padding: 12px;
        text-align: center;
    }

    .product-table {
        margin-top: 15px;
    }
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination a {
    display: inline-block;
    margin: 0 5px;
    padding: 10px 15px;
    text-decoration: none;
    background-color: #f0f0f5;
    color: #333;
    border-radius: 5px;
    border: 1px solid #ddd;
    transition: background-color 0.3s, color 0.3s;
}

.pagination a:hover {
    background-color: #0056b3;
    color: white;
}

.pagination a.active {
    background-color: black;
    color: white;
    border: none;
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

    .product-table th,
    .product-table td {
        font-size: 14px;
    }
}

</style>

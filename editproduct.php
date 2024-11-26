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

// Get the product ID from the query string
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // Fetch product details from the database
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            $error = "Product not found.";
        }
    } catch (PDOException $e) {
        $error = "Error fetching product: " . $e->getMessage();
    }
} else {
    $error = "Product ID is required.";
}

// Handle form submission to update product details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Check if an image is uploaded
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
        $image_tmp_name = $_FILES['image']['tmp_name'];
        
        // Move the uploaded image to the "uploads" folder
        if (!move_uploaded_file($image_tmp_name, $image)) {
            $error = "Error uploading image.";
        }
    }
    
    // Validate input
    if (empty($name) || empty($description) || empty($price) || empty($category)) {
        $error = "All fields are required.";
    } else {
        try {
            // Update the product in the database
            if ($image) {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, image = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $category, $image, $product_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ? WHERE id = ?");
                $stmt->execute([$name, $description, $price, $category, $product_id]);
            }
            $success = "Product updated successfully.";
        } catch (PDOException $e) {
            $error = "Error updating product: " . $e->getMessage();
        }
    }
}

// Fetch all categories
try {
    $stmt = $pdo->prepare("SELECT DISTINCT category FROM products");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching categories: " . $e->getMessage();
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

<!-- Edit Product Page Content -->
<div class="edit-product-container">
<button type="button" onclick="window.location.href='manageproducts.php';" class="back-btn">Back</button>
    <div class="edit-product-wrapper">
        <h2>Edit Product</h2>

        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p style='color: green;'>$success</p>"; } ?>

        <!-- Product Edit Form -->
        <form method="POST" action="editproduct.php?id=<?php echo $product_id; ?>" class="edit-product-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Product Price</label>
                <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>">
            </div>
            <div class="form-group">
    <label for="category">Product Category</label>
    <select name="category" id="category" required>
        <option value="">Select a category</option>
        <option value="Hirono" <?php echo ($product['category'] === 'Hirono') ? 'selected' : ''; ?>>Hirono</option>
        <option value="The Monsters" <?php echo ($product['category'] === 'The Monsters') ? 'selected' : ''; ?>>The Monsters</option>
        <option value="Dimoo" <?php echo ($product['category'] === 'Dimoo') ? 'selected' : ''; ?>>Dimoo</option>
        <option value="SkullPanda" <?php echo ($product['category'] === 'SkullPanda') ? 'selected' : ''; ?>>SkullPanda</option>
        <option value="Crybaby" <?php echo ($product['category'] === 'Crybaby') ? 'selected' : ''; ?>>Crybaby</option>
    </select>
</div>

<div class="form-group">
<p>Current Image:</p>
<img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Current Product Image" style="max-width: 150px;">
    <label for="image">Product Image</label>
    <input type="file" id="image" name="image" accept="image/*">
    <?php if (!empty($product['image'])) { ?>
    <?php } ?>
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

/* Edit Product Page Styles */
.edit-product-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.edit-product-wrapper h2 {
    font-size: 28px;
    color: #333;
    margin-bottom: 20px;
}

.edit-product-form .form-group {
    margin-bottom: 20px;
}

.edit-product-form label {
    font-size: 16px;
    color: #333;
    display: block;
    margin-bottom: 8px;
}

.edit-product-form input,
.edit-product-form textarea,
.edit-product-form select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.edit-product-form textarea {
    height: 100px;
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

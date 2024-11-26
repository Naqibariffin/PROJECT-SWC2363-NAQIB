<?php
// Include the header and database connection
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit;
}

// Initialize error/success messages
$error = $success = '';

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];  // Get category value
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Create an 'uploads' directory if not exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }
        
        // Sanitize the image name and move the uploaded image
        $image = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Validate input
    if (empty($name) || empty($description) || empty($price) || empty($image) || empty($category)) {
        $error = "All fields are required, including the image and category.";
    } else {
        try {
            // Insert the product data into the database
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $category]);
            $success = "Product added successfully.";
        } catch (PDOException $e) {
            $error = "Error adding product: " . $e->getMessage();
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

<!-- Add Product Form -->
<div class="add-product-container">
<button type="button" onclick="window.location.href='manageproducts.php';" class="back-btn">Back</button>
    <h2>Add New Product</h2>

    <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (!empty($success)) { echo "<p class='success'>$success</p>"; } ?>

    <form action="addproducts.php" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="category">Category:</label>
        <select name="category" id="category" required>
            <option value="">Select a category</option>
            <option value="Hirono">Hirono</option>
            <option value="The Monsters">The Monsters</option>
            <option value="Dimoo">Dimoo</option>
            <option value="SkullPanda">SkullPanda</option>
            <option value="Crybaby">Crybaby</option>
        </select>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image" accept="image/*" required>

        <button type="submit">Add Product</button>
    </form>
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


    /* Add Product Form Styles */
    .add-product-container {
        width: 50%;
        margin: 50px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .add-product-container h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    .add-product-container form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .add-product-container label {
        font-size: 16px;
        color: #333;
    }

    .add-product-container input, .add-product-container textarea, .add-product-container select {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
    }

    .add-product-container button {
        padding: 10px 20px;
        background-color: black;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border-radius: 5px;
        border: 2px solid black;
    }

    .add-product-container button:hover {
            background-color: #0056b3;
            color: white;
    }


    /* Success/Error Messages */
    .success {
        color: green;
        font-weight: bold;
    }

    .error {
        color: red;
        font-weight: bold;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .add-product-container {
            width: 80%;
        }

        .add-product-container form {
            gap: 15px;
        }

        .add-product-container input, .add-product-container textarea, .add-product-container select {
            font-size: 14px;
        }

        .add-product-container button {
            padding: 8px 16px;
        }
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

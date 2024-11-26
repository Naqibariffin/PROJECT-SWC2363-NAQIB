<?php
session_start();
include 'db.php'; 
include 'header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity'] ?? 1);

    if (!isset($_SESSION['carts'])) {
        $_SESSION['carts'] = [];
    }

    if (isset($_SESSION['carts'][$product_id])) {
        $_SESSION['carts'][$product_id] += $quantity;
    } else {
        $_SESSION['carts'][$product_id] = $quantity;
    }

    $_SESSION['notification'] = "Added {$quantity} product(s) to your cart.";
}

$priceFilter = $_POST['priceFilter'] ?? 'all';
$categoryFilter = $_POST['categoryFilter'] ?? 'all';

$query = "SELECT * FROM products WHERE 1";

if ($categoryFilter != 'all') {
    $query .= " AND category = :category";
}

if ($priceFilter != 'all') {
    if ($priceFilter == 'under50') {
        $query .= " AND price < 50";
    } elseif ($priceFilter == '50to70') {
        $query .= " AND price BETWEEN 50 AND 70";
    } elseif ($priceFilter == 'above70') {
        $query .= " AND price > 70";
    }
}

$stmt = $pdo->prepare($query);

if ($categoryFilter != 'all') {
    $stmt->bindParam(':category', $categoryFilter);
}

$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php if (isset($_SESSION['notification'])): ?>
    <div class="notification">
        <p><?php echo $_SESSION['notification']; ?></p>
        <button onclick="dismissNotification()">×</button>
    </div>
    <?php unset($_SESSION['notification']); endif; ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Collectible Pop Mart Figures</h1>
            <p>Fun, colorful, and exclusive designs.</p>
            <p>Starting From RM49.80</p>
        </div>
    </section>

    <section class="filter-section">
        <button class="filter-btn" onclick="toggleFilter()">Filter <i class="fas fa-filter"></i></button>
        <div class="filter-options" id="filterOptions" style="display: none;">
            <h3>Filter by:</h3>
            <label for="priceFilter">Price:</label>
            <select id="priceFilter" onchange="filterProducts()">
                <option value="all">All</option>
                <option value="under50">Under RM 50</option>
                <option value="50to70">RM 50 - RM 70</option>
                <option value="above70">Above RM 70</option>
            </select>
            <br><br>

            <label for="categoryFilter">Category:</label>
            <select id="categoryFilter" onchange="filterProducts()">
                <option value="all">All</option>
                <?php
                $stmt = $pdo->query("SELECT DISTINCT category FROM products");
                $categories = $stmt->fetchAll();
                foreach ($categories as $category) {
                    echo "<option value='{$category['category']}'>{$category['category']}</option>";
                }
                ?>
            </select>
        </div>
    </section>

    <section class="product-list">
        <?php
        if ($products) {
            foreach ($products as $product) {
                echo "<div class='product' data-price='{$product['price']}' data-category='{$product['category']}'>
                        <img src='{$product['image']}' alt='{$product['name']}'>
                        <h2>{$product['name']}</h2>
                        <p class='product-description'>{$product['description']}</p>
                        <p class='product-price'>Price: RM {$product['price']}</p>
                        <form method='post' action=''>
                            <input type='hidden' name='product_id' value='{$product['id']}'>
                            <div class='quantity-wrapper'>
                                <label for='quantity-{$product['id']}'>Quantity:</label>
                                <div class='quantity-control-buttons'>
                                    <button type='button' onclick='updateQuantity({$product['id']}, -1)'>-</button>
                                    <input type='number' name='quantity' id='quantity-{$product['id']}' class='quantity-input' min='1' value='1'>
                                    <button type='button' onclick='updateQuantity({$product['id']}, 1)'>+</button>
                                </div>
                            </div>
                            <button type='submit' class='add-to-cart-button'>Add to Cart</button>
                        </form>
                    </div>";
            }
        }
        ?>
    </section>

    <script>
        function toggleFilter() {
            const filterOptions = document.getElementById('filterOptions');
            filterOptions.style.display = filterOptions.style.display === 'none' ? 'block' : 'none';
        }

        function filterProducts() {
    const priceFilter = document.getElementById('priceFilter').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    const products = document.querySelectorAll('.product');

    products.forEach(product => {
        const price = parseFloat(product.dataset.price);
        const category = product.dataset.category;

        let showProduct = true;

        if (priceFilter === 'under50' && price >= 50) {
            showProduct = false;
        } else if (priceFilter === '50to70' && (price < 50 || price > 70)) {
            showProduct = false;
        } else if (priceFilter === 'above70' && price <= 70) {
            showProduct = false;
        }

        if (categoryFilter !== 'all' && category !== categoryFilter) {
            showProduct = false;
        }

        // Use visibility instead of display to prevent layout breaking
        product.style.visibility = showProduct ? 'visible' : 'hidden';
    });
}


        function updateQuantity(productId, delta) {
            const quantityInput = document.getElementById('quantity-' + productId);
            let currentQuantity = parseInt(quantityInput.value);
            currentQuantity += delta;
            if (currentQuantity < 1) currentQuantity = 1;
            quantityInput.value = currentQuantity;
        }

        function dismissNotification() {
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }

        setTimeout(() => {
            dismissNotification();
        }, 6000);
    </script>
<?php include 'footer.php' ?>
</body>
</html>

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
}

.hero-section {
  background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('banner.jpg') no-repeat center center/cover;
  color: #fff;          
  text-align: center;          
  padding: 50px 20px;                    
}     

.hero-content h1 {        
  font-size: 3em;          
  font-weight: 700;
}

.hero-content p {
  font-size: 1.3em;
}

/* Filter Section */
.filter-section {
  text-align: left;
  margin: 20px 80px;
}

.filter-btn {
  background: linear-gradient(45deg, #007BFF, #00D4FF);
  color: white;
  font-size: 1.1em;
  padding: 10px 25px;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
  font-weight: 600;
}

.filter-btn:hover {
  background: linear-gradient(45deg, #0056b3, #00a3cc);
}

/* Product List Styles */
.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin: 40px 0;
}

.product {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    width: 250px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    min-height: 400px;
    justify-content: space-between; 
    visibility: visible; /* Default to visible */
}


.product img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 5px;
}

.product h2 {
  font-size: 1.3em;
  margin: 10px 0;
  margin-bottom:-13px; 
}

.product-price {
  font-size: 1.1em;
  font-weight: 600;
  color: #0056b3;
  margin-bottom:10px; 
  margin-top:-9px; 
}

.product-description {
  font-size: 1em;
  color: #555;
  flex-grow: 1;
}

.quantity-wrapper {
  margin-bottom: 15px;
  display: flex;
  flex-direction: column; 
  align-items: flex-start;
  gap: 10px;
}

.quantity-wrapper label {
  font-size: 1.1rem;
  font-weight: 500;
  color: #333;
}

.quantity-control-buttons {
  display: flex;
  align-items: center;
  gap: 10px; 
}

.quantity-control-buttons button {
  width: 50px;
  padding: 8px;
  text-align: center;
  border:1px solid black;
  border-radius: 8px;
  font-size: 1em;
  background-color: #f9f9f9;
  color: #333;
  transition: border-color 0.3s ease, background-color 0.3s ease;
}

.quantity-control-buttons button:hover {
  background-color: #0056b3;
  color: white;
  border-color: #0056b3;
}

.quantity-input {
  width: 50px;
  padding: 8px;
  text-align: center;
  border:1px solid black;
  border-radius: 8px;
  font-size: 1em;
  background-color: #f9f9f9;
  color: #333;
  transition: border-color 0.3s ease, background-color 0.3s ease;
}

.quantity-input:focus {
  outline: none;
  border-color: #007bff;
  background-color: #fff;
}

.quantity-control-buttons button {
  background-color: #e2e8f0;
  color: #333;
  border: 1px solid black;
  font-size: 1em; 
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.quantity-control-buttons button:hover {
  background-color: #0056b3;
  color: white;
  border-color: #0056b3;
}

.add-to-cart-button {
  background-color: white;
  color: black;
  border: 2px solid black;
  font-size: 1em;
  font-weight: 600;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  margin-top: 10px; 
  margin-left:17%;
  align-self: center;
}

.add-to-cart-button:hover {
  background-color: #0056b3;
  color: white;
}


.notification {
  position: fixed;
  top: 20px;
  left: 20px;
  background-color: #0056b3;
  color: white;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  font-size: 1rem;
  z-index: 1000;
  display: flex;
  align-items: center;
  max-width: 300px;
  opacity: 1;
  animation: fadeOut 3s forwards ease-in-out;
}

.notification button {
  background: transparent;
  border: none;
  color: white;
  font-size: 1.2rem;
  cursor: pointer;
  margin-right: 10px;
  order: -1; 
}

@keyframes fadeOut {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
  }
}

.product {
    transition: opacity 0.3s ease, visibility 0.3s ease;
    visibility: visible; /* Default to visible */
    opacity: 1; /* Default opacity */
}
.product[style*="visibility: hidden"] {
    opacity: 0;
}

</style>

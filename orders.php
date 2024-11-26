<?php
// Include the database connection
include 'db.php';

// Initialize variables
$error = '';
$orders = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = trim($_POST['email']);
  $enteredEmail = $email; // Store the entered email

  if (!empty($email)) {
      try {
          // Retrieve user ID based on email
          $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
          $stmt->execute([$email]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);

          if ($user) {
              // Fetch orders for this user and join with the products table to get product name
              $stmt = $pdo->prepare("SELECT o.id AS order_id, o.created_at, o.status, o.total_price, 
                                     o.product_id, o.quantity, o.price, p.name AS product_name 
                                     FROM orders o 
                                     JOIN products p ON o.product_id = p.id 
                                     WHERE o.email = ?");
              $stmt->execute([$email]);
              $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

              if (empty($orders)) {
                  $error = "No orders found for this email.";
              }
          } else {
              $error = "No orders found for this email.";
          }
      } catch (PDOException $e) {
          $error = "Database error: " . $e->getMessage();
      }
  } else {
      $error = "Please enter your email address.";
  }
}
?>
<title>POPBIQ - Toy Shop</title>
<header>
  <nav>
  <img src="logo.png" alt="QibShop Logo" class="logo">
      <ul class="menu">
        <li><a href="myprofile.php"><b>My Profile</b></a></li>
        <li><a href="orders.php"><b>Track My Order</b></a></li>
      </ul>

      <!-- User Icons -->
      <div class="user-icons">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a> <!-- Logout icon -->
      </div>
  </nav>
</header>

<!-- Track Orders Form -->
<div class="track-orders-container">
    <div class="track-orders-wrapper">
        <h2>Track Your Orders</h2>
        
        <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

        <!-- Track Orders Form -->
        <form method="POST" action="">
            <div class="input-box">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-box button">
                <input type="submit" value="Track Orders">
            </div>
        </form>

        <?php if (!empty($orders)) { ?>
            <h3>Your Orders:</h3>
            <h3> Your Email:  <?php echo htmlspecialchars($enteredEmail); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td>RM <?php echo htmlspecialchars($order['total_price']); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
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

.dropdown-content {
  display: none;
  position: absolute;
  background-color: white;
  border: 1px solid #ddd;
  min-width: 160px;
  top: 100%;
  left: 0;
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown-content li {
  list-style: none;
}

.dropdown-content a {
  color: #333;
  padding: 10px;
  display: block;
  text-decoration: none;
}

.dropdown-content a:hover {
  background-color: #f1f1f1;
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
/* Track Orders Styles */
.track-orders-container {
  max-width: 600px;
  margin: 50px auto;
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.track-orders-wrapper {
  text-align: center;
}

.track-orders-wrapper h2 {
  font-size: 24px;
  color: #333;
}

.input-box {
  height: 42px;
  margin: 18px 0;
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

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table, th, td {
  border: 1px solid #ddd;
}

th, td {
  padding: 10px;
  text-align: center;
}

th {
  background-color: #f4f4f4;
}

</style>

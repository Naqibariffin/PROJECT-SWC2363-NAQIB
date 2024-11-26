<?php
// Start session and include necessary files
session_start();
include 'db.php'; // Include your database connection script
include 'header.php'; // Include your header file

// Check if an order_id is provided in the URL
if (!isset($_GET['order_id'])) {
    echo "Invalid order ID.";
    exit();
}

$order_id = $_GET['order_id'];

try {
    // Fetch order details from the database
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    // Ensure the order exists
    if (!$order) {
        echo "Order not found.";
        exit();
    }

    // Fetch the products in the order
    $stmt = $pdo->prepare("SELECT products.name, orders.quantity, orders.price 
                           FROM orders 
                           JOIN products ON orders.product_id = products.id 
                           WHERE orders.id = ?");
    $stmt->execute([$order_id]);
    $products = $stmt->fetchAll();

    // Calculate totals
    $subtotal = array_reduce($products, function ($carry, $product) {
        return $carry + ($product['price'] * $product['quantity']);
    }, 0);
    $shipping_fee = ($subtotal > 100) ? 0 : 10; // Free shipping for orders over RM100
    $total = $subtotal + $shipping_fee;

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POPBIQ - Toy Shop</title>
</head>
<body>
<div class="receipt-container">
    <h1>Receipt 🧾</h1>
    <div class="customer-info">
        <h2>Customer Information</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Phone: +60</strong> <?= htmlspecialchars($order['phone']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?>, 
        <?= htmlspecialchars($order['zip']) ?>,<?= htmlspecialchars($order['city']) ?></p>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <?php foreach ($products as $product): ?>
            <div class="summary-item">
                <span><?= htmlspecialchars($product['name']) ?> x <?= $product['quantity'] ?></span>
                <span>RM <?= number_format($product['price'] * $product['quantity'], 2) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="summary-item">
            <span>Subtotal</span>
            <span>RM <?= number_format($subtotal, 2) ?></span>
        </div>
        <div class="summary-item">
            <span>Shipping Fee</span>
            <span>RM <?= number_format($shipping_fee, 2) ?></span>
        </div>
        <div class="summary-item total">
            <span>Total</span>
            <span>RM <?= number_format($total, 2) ?></span>
        </div>
    </div>

    <div class="payment-info">
        <h2>Payment Information</h2>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
        <br>
    </div>

    <div class="receipt-actions">
        <a href="products.php" class="button">Return to Shop</a>
        <a href="#" onclick="window.print()" class="button">Print Receipt</a>
    </div>
    <br>
</div>
</body>
</html>

<?php include 'footer.php'; ?> 

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f9fafb;
    margin: 0;
    padding: 0;
    color: black;
}

h1 {
    text-align: center;
    margin-top: 20px;
    font-size: 2.5rem;
    color: black;
}

.receipt-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.customer-info, .order-summary, .payment-info {
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid black;
}

.summary-item span {
    font-size: 1rem;
    color: black;
}

.summary-item.total {
    font-weight: 600;
    color: black;
    font-size: 1.1rem;
}

.receipt-actions {
    text-align: center;
    margin-top: 20px;
}

.receipt-actions .button {
    background-color: #c0c0c0;
    color: black;
    padding: 12px 20px;
    border: 2px solid black;
    border-radius: 5px;
    cursor: pointer;
    margin: 5px;
    font-size: 1.1rem;
    text-decoration: none;
}

.receipt-actions .button:hover {
    background-color: #0056b3;
    color: white;
}
</style>

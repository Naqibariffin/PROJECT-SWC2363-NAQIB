<?php
// Start session and include necessary files
session_start();
include 'db.php'; // Include your database connection script
include 'header.php'; // Include your header file

// Ensure the cart session is available
if (empty($_SESSION['carts'])) {
    header("Location: carts.php");
    exit();
}

// Process the checkout form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Collect billing and payment information from the form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $payment_method = $_POST['payment'];
        $shipping_fee = 10;

        // Calculate the total price of the order
        $subtotal = array_sum(array_map(function ($id, $quantity) use ($pdo) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            if ($product) {
                return $product['price'] * $quantity;
            }
            return 0; // Return 0 if the product doesn't exist
        }, array_keys($_SESSION['carts']), $_SESSION['carts']));

        // Check if the total price exceeds 100 and set shipping fee to 0
        if ($subtotal > 100) {
            $shipping_fee = 0; // Free shipping if subtotal is greater than 100
        }

        $total = $subtotal + $shipping_fee;

        // Insert the order into the `orders` table, including product details
        foreach ($_SESSION['carts'] as $product_id => $quantity) {
            // Check if the product exists
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product) {
                // Insert into the orders table, assuming you have product_id, quantity, and price columns in orders
                $stmt = $pdo->prepare("INSERT INTO orders (customer_name, email, phone, address, city, zip, total_price, status, payment_method, product_id, quantity, price) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $phone, $address, $city, $zip, $total, $payment_method, $product_id, $quantity, $product['price']]);
            } else {
                // Handle the case where the product doesn't exist
                throw new Exception("Product ID {$product_id} not found in the database.");
            }
        }

        // Clear the cart after order placement
        $_SESSION['carts'] = [];

        // Redirect to receipt page
        header("Location: receipt.php?order_id=" . $pdo->lastInsertId());
        exit();
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout - QibShop</title>
        <link rel="stylesheet" href="styles.css"> <!-- Include your external stylesheet -->
    </head>
    <body>
    <div class="checkout-container">
        <a href="carts.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Checkout 🛒</h1>
        <div class="order-summary">
            <h2>Order Summary</h2>
            <?php
            $subtotal = 0;
            foreach ($_SESSION['carts'] as $id => $quantity) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
                if ($product) {
                    echo "<div class='summary-item'>
                            <span>{$product['name']} x {$quantity} piece(s)</span>
                            <span>RM {$product['price']}</span>
                          </div>";
                    $subtotal += $product['price'] * $quantity;
                }
            }
            // Determine if shipping is free
            $shipping_fee = ($subtotal > 100) ? 0 : 10;
            $total = $subtotal + $shipping_fee;
            ?>
            <div class="summary-item">
                <span>Subtotal</span>
                <span>RM <?= $subtotal ?></span>
            </div>
            <div class="summary-item">
                <span>Shipping</span>
                <span>RM <?= $shipping_fee ?></span>
            </div>
            <div class="summary-item total">
                <span>Total</span>
                <span>RM <?= $total ?></span>
            </div>
        </div>
        <form method="POST" class="checkout-form">
            <h2>Billing Information</h2>
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="zip">Zip Code:</label>
                <input type="text" id="zip" name="zip" required>
            </div>
            <div class="form-group">
                <label for="payment">Payment Method:</label>
                <select id="payment" name="payment" required>
                    <option value="">Select Your Payment Method</option>
                    <option value="FPX Payment">FPX Payment</option>
                    <option value="Pick Up At Store">Pick Up At Store</option>
                    <option value="Cash On Delivery">Cash On Delivery</option>
                </select>
            </div>
            <button class="button" type="submit">Place Order</button>
        </form>
    </div>
    </body>
    </html>
    <?php
}
?>
<?php include 'footer.php'; ?> 

<script>
    document.getElementById('payment').addEventListener('change', function () {
        const shippingFeeElement = document.querySelector('.summary-item span:last-child');
        const totalElement = document.querySelector('.summary-item.total span:last-child');
        const subtotal = <?= $subtotal ?>;
        let shippingFee = 10;

        if (this.value === "Pick Up At Store") {
            shippingFee = 0; // Free shipping
        } else if (subtotal > 100) {
            shippingFee = 0; // Free shipping for orders over 100
        }

        const total = subtotal + shippingFee;

        shippingFeeElement.textContent = `RM ${shippingFee.toFixed(2)}`;
        totalElement.textContent = `RM ${total.toFixed(2)}`;
    });
</script>



<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'); /* Font Awesome CDN */

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

.checkout-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.checkout-container .back-button {
    font-size: 1rem;
    color: #0056b3;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 20px;
}

.checkout-container .back-button i {
    margin-right: 8px;
}

.order-summary {
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

.checkout-form {
    margin-top: 20px;
}

.checkout-form .form-group {
    margin-bottom: 15px;
}

.checkout-form .form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.checkout-form .form-group input,
.checkout-form .form-group select {
    width: 100%;
    padding: 12px 3px;
    border: 1px solid black;
    border-radius: 5px;
    font-size: 1rem;
    color: black;
}

.checkout-form .button {
    background-color:#c0c0c0 ;
    color: black;
    padding: 12px 20px;
    border: 2px solid black;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 1.1rem;
}

.checkout-form .button:hover {
    background-color: #0056b3;
    color: white;
}

.checkout-form .form-group select {
    -webkit-appearance: none;
}
</style>

<?php
session_start();
include 'db.php'; // Ensure this file connects to the database

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['carts'])) {
    $_SESSION['carts'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'] ?? 1;

    // Update quantity if item already in cart
    if (isset($_SESSION['carts'][$product_id])) {
        $_SESSION['carts'][$product_id] += $quantity;
    } else {
        $_SESSION['carts'][$product_id] = $quantity;
    }
}

$total = 0.00;
$shipping_cost = 10.00; // Default shipping cost (can be dynamic if needed)
$grand_total = 0.00;

// Calculate the total price of items in the cart
foreach ($_SESSION['carts'] as $id => $quantity) {
    // Retrieve product information from the database
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {
        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;
    }
}

if ($total > 100) {
    $shipping_cost = 0.00;
}

$grand_total = $total + $shipping_cost;

$total = number_format($total, 2);
$grand_total = number_format($grand_total, 2);
$shipping_cost = number_format($shipping_cost, 2);

include 'header.php';

echo "<div class='cart-container'>";
echo "<h1>My Shopping Cart 🛒</h1>";

if (!empty($_SESSION['carts'])) {
    echo "<table class='cart-table'>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>";
            foreach ($_SESSION['carts'] as $id => $quantity) {
                // Retrieve product information from the database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $product = $stmt->fetch();
            
                if ($product) {
                    $subtotal = $product['price'] * $quantity;
                    $image_url = $product['image']; // Assuming the product table has a column for the image URL
                    echo "<tr>
                           <td>
                           <div class='product-image-box'>
                           <img src='{$image_url}' alt='{$product['name']}' class='product-image' />
                           </div>
                           <div class='product-name-box'>
                           <span class='product-name'>{$product['name']}</span>
                           </div>
                           </td>
                            <td>
                                <input type='number' class='quantity-input' value='{$quantity}' min='1' data-id='{$id}'>
                            </td>
                            <td>RM {$product['price']}</td>
                            <td>RM {$subtotal}</td>
                            <td><button class='remove-button' data-id='{$id}'><i class='fas fa-trash-alt'></i> Delete</button></td>
                          </tr>";
                }
            }
            
    echo "</tbody>
          <tfoot>
          <tr class='total-row'>
          <td colspan='3'>Subtotal</td>
          <td colspan='2'>RM {$total}</td>
          </tr>
          <tr class='total-row'>
          <td colspan='3'>Shipping</td>
          <td colspan='2'>" . ($shipping_cost == 0 ? "FREE SHIPPING" : "RM {$shipping_cost}") . "</td>
          </tr>
          <tr class='total-row'>
          <td colspan='3'>Grand Total</td>
          <td colspan='2'>RM {$grand_total}</td>
          </tr>
          </tfoot>
          </table>";
    echo"<div class='buttons-container'>
          <a href='products.php' class='continue-shopping-button'>Continue Shopping</a>
          <a href='checkout.php' class='checkout-button'>Proceed to Checkout</a>
        </div>";
} else {
    // If cart is empty, display a message but keep the same layout structure
    echo "<table class='cart-table'>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan='5' class='empty-cart-message'>Your cart is empty.</td>
                </tr>
            </tbody>
          </table>";

    echo "<div class='buttons-container'>
            <a href='products.php' class='continue-shopping-button'>Continue Shopping</a>
          </div>";
}
echo "</div>";

include 'footer.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'); /* Font Awesome CDN */

/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f9fafb;
    margin: 0;
    padding: 0;
    color: #333;
}

h2 {
    text-align: center;
    margin-top: 20px;
    font-size: 2rem;
    color: #333;
}

.cart-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.product-image-box {
    display: inline-block;
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 8px;
    margin-right: 10px;
}

.product-name-box {
    display: inline-block;
    vertical-align: middle;
    width: calc(100% - 70px);
    font-size: 1.1rem;
    font-weight: bold;
    color: #333;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}


.cart-table th, .cart-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.cart-table th {
    background-color: #f7fafc;
    color: #333;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.cart-table td {
    font-size: 0.9rem;
    color: #333;
}

.cart-table .quantity-input {
    width: 50px;
    padding: 5px;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
}

.total-row {
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
}

.total-row td {
    border-top: 2px solid #e2e8f0;
}

.empty-cart-message {
    text-align: center;
    color: #a0aec0;
    font-size: 1.2rem;
    margin-top: 30px;
}

.remove-button {
    display: flex;
    align-items: center;
    padding: 12px 17px;
    background: red;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9rem;
}

.remove-button i {
    margin-right: 5px; /* Space between the icon and the text */
}

.remove-button:hover {
    background: #c53030;
}


/* Style for Continue Shopping and Proceed to Checkout buttons */
.continue-shopping-button, .checkout-button {
  background-color: white;
  color: black;
  border: 2px solid black;
  font-size: 1em;
  font-weight: 600;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-block; 
  margin-top: 40px; 
  margin-right: 10px; 
  text-align: center;
  text-decoration:none;
}


.continue-shopping-button:hover, .checkout-button:hover {
  background-color: #0056b3;
  color: white;
}


.buttons-container {
  display: flex;
  justify-content: center; 
  gap: 15px; 
}

</style>

<script>
// Handle quantity change
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        let productId = this.getAttribute('data-id');
        let newQuantity = this.value;

        // Send an AJAX request to update the quantity in the session
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Reload to reflect updated total
                location.reload();
            } else {
                alert("Error updating quantity. Please try again.");
            }
        };
        xhr.send('action=update&product_id=' + productId + '&quantity=' + newQuantity);
    });
});

// Handle remove button click
document.querySelectorAll('.remove-button').forEach(button => {
    button.addEventListener('click', function() {
        let productId = this.getAttribute('data-id');

        // Send an AJAX request to remove the product from the cart
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Reload to reflect updated cart
                location.reload();
            } else {
                alert("Error removing the product. Please try again.");
            }
        };
        xhr.send('action=remove&product_id=' + productId);
    });
});
</script>

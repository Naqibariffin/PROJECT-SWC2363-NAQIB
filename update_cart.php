<?php
session_start();
include 'db.php'; // Ensure this file connects to the database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Update the cart session with the new quantity
        if (isset($_SESSION['carts'][$product_id])) {
            $_SESSION['carts'][$product_id] = $quantity;
            echo "Cart updated successfully.";
        } else {
            echo "Product not found in cart.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'remove' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];

        // Remove the product from the cart
        if (isset($_SESSION['carts'][$product_id])) {
            unset($_SESSION['carts'][$product_id]);
            echo "Product removed from cart.";
        } else {
            echo "Product not found in cart.";
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid request.";
}
?>

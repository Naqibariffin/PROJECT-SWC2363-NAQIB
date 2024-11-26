<?php
// Include the database connection
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: adminsignin.php");
    exit;
}

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    try {
        // Check for related orders
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $related_orders_count = $stmt->fetchColumn();

        if ($related_orders_count > 0) {
            header("Location: manageproducts.php?error=Cannot delete this product.There's a pending order for this product.");
            exit;
        }

        // Proceed with deletion
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            // Delete product image
            $image_path = $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // Delete product record
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$product_id]);

            header("Location: manageproducts.php?success=Product deleted successfully.");
            exit;
        } else {
            header("Location: manageproducts.php?error=Product not found.");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: manageproducts.php?error=Error deleting product: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: manageproducts.php?error=Invalid request.");
    exit;
}

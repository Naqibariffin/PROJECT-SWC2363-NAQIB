<?php
session_start();
session_unset(); // Clear session variables
session_destroy(); // Destroy session

// Clear cookies
setcookie('user_id', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');

// Redirect to login page
header("Location: customersignin.php");
exit;
?>

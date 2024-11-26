<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POPBIQ - Toy Shop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>	
    <?php
    //extract($_POST);
    
    //connect to server
    $connect = mysqli_connect("localhost", "root","", "ecommerce");
    
    if (!$connect) {
        die('ERROR: ' . mysqli_connect_error());
    }
    ?>
    
    <!-- Footer -->
    <footer>
    <p>© 2024 POPBIQ . All rights reserved.</p>
  </footer>
  </html>

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

/* Footer */
footer {
  background-color: black;
  color: #ddd;
  text-align: center;
  padding: 30px 0;
  font-size: 0.9em;
}

footer p {
  margin: 0;
}
  </style>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>    

<?php
include('header.php');
?>

<div class="faqs-container">
    <h1><?php echo "Frequently Asked Questions"; ?></h1>

    <h2><?php echo "Orders and Shipping"; ?></h2>
    <div class="questions-container">
        <?php
        $faqs_shipping = [
            "How do I place an order?" => "Browse our exclusive collection of BIQPOP figures, select your favorites, add them to your cart, and complete the checkout process with your shipping and payment details.",
            "How can I track my order?" => "Once your BIQPOP order is shipped, you’ll need to sign up your account. Use track my order to track your package on our website once you sign in.",
            "How long does shipping take?" => "Domestic orders typically arrive within 5-10 business days. For international orders, it may take 10-20 business days, depending on your location."
        ];

        foreach ($faqs_shipping as $question => $answer) {
            echo "<div class='content-container'>
                    <div class='faq-header'>
                        <h3>{$question}</h3>
                        <span class='open active'>+</span>
                        <span class='close'>-</span>
                    </div>
                    <div class='content'>
                        <p>{$answer}</p>
                    </div>
                  </div>";
        }
        ?>
    </div>

    <h2><?php echo "Products"; ?></h2>
    <div class="questions-container">
        <?php
        $faqs_products = [
            "Are BIQPOP figures limited edition?" => "Yes! Many of our BIQPOP figures are limited edition, designed for collectors and fans alike. Keep an eye on our new releases to grab your favorites before they sell out!",
            "What materials are used to make the figures?" => "BIQPOP figures are crafted from high-quality vinyl and are designed with exceptional attention to detail, ensuring durability and aesthetic appeal.",
        ];

        foreach ($faqs_products as $question => $answer) {
            echo "<div class='content-container'>
                    <div class='faq-header'>
                        <h3>{$question}</h3>
                        <span class='open active'>+</span>
                        <span class='close'>-</span>
                    </div>
                    <div class='content'>
                        <p>{$answer}</p>
                    </div>
                  </div>";
        }
        ?>
    </div>
    <br>
    <h2><b><?php echo "If you got any questions,directly email us at :"; ?></b></h2>
    <h2><a href="mailto:popqibhelp@gmail.com">popqibhelp@gmail.com</h2>
</div>

<?php
include('footer.php');
?>

<script>
const faqHeaders = document.querySelectorAll(".faqs-container .faq-header");

faqHeaders.forEach((header) => {
    header.addEventListener("click", () => {
        header.nextElementSibling.classList.toggle("active");

        const open = header.querySelector(".open");
        const close = header.querySelector(".close");

        if (header.nextElementSibling.classList.contains("active")) {
            open.classList.remove("active");
            close.classList.add("active");
        } else {
            open.classList.add("active");
            close.classList.remove("active");
        }
    });
});
</script>

</body>
</html>



<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-color: #f4f4f4;
  color: #333;
}
.faqs-container {
  font-family: "Roboto", sans-serif;
  max-width: 700px;
  margin: 30px auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.faqs-container h2 {
  padding: 0 px 32px;
  font-size: 28px;
  text-align: center;
}

.faqs-container .faq-header {
  display: flex;
  background-color: white;
  color: black;
  align-items: center;
  position: relative;
  cursor: pointer;
  padding: 10px;
  border-radius: 8px;
  transition: background-color 0.3s, transform 0.3s;
  border: 2px solid black;
}

.faqs-container .faq-header:hover {
  background-color: #0056b3;
  color: white;
  transform: scale(1.02);
}

.faqs-container .faq-header .open,
.faqs-container .faq-header .close {
  position: absolute;
  right: 0;
  padding: 0 32px;
  font-size: 15px;
  font-weight: bold;
  transform: translateY(-8px);
  opacity: 0;
  transition: all 500ms;
}

.faqs-container .faq-header .open.active,
.faqs-container .faq-header .close.active {
  opacity: 1;
  transform: translateY(0);
}

.faqs-container .faq-header h3 {
  font-size: 15px;
  padding: 0 20px;
}

.faqs-container .content {
  padding: 0 32px;
  background: #fdfffc;
  line-height: 2;
  max-height: 0;
  overflow: hidden;
  transition: max-height 500ms ease-in-out;
  border: 2px solid black;
  border-radius: 8px;
}

.faqs-container .content.active {
  max-height: 600px;
}

</style>
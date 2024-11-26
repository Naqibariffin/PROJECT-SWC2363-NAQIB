<?php 
include'db.php';
include 'header.php'; 
?>

<section id="store-location" class="fade-in">
    <div class="container">
        <h1>Our Store Locations</h1>
        <div class="store-map-container">
            <div class="store-list">
                <h2>Visit Our Stores</h2>
                <ul>
                    <li>
                        <h3>POPBIQ - Kuala Lumpur Branch</h3>
                        <p><strong>Address:</strong> Suria KLCC, Kuala Lumpur, 50088, Malaysia</p>
                        <p><strong>Phone:</strong> +60 3-2382 2828</p>
                        <a href="https://www.google.com/maps?q=Suria+KLCC,+Kuala+Lumpur,+Malaysia" target="_blank" rel="noopener noreferrer">Get Directions</a>
                    </li>
                    <li>
                        <h3>POPBIQ - Penang Branch</h3>
                        <p><strong>Address:</strong> Gurney Plaza, George Town, 10250 Penang, Malaysia</p>
                        <p><strong>Phone:</strong> +60 4-222 2333</p>
                        <a href="https://www.google.com/maps?q=Gurney+Plaza,+George+Town,+Penang,+Malaysia" target="_blank" rel="noopener noreferrer">Get Directions</a>
                    </li>
                    <li>
                        <h3>POPBIQ - Johor Bahru Branch</h3>
                        <p><strong>Address:</strong> Johor Bahru City Square, 80000 Johor Bahru, Johor, Malaysia</p>
                        <p><strong>Phone:</strong> +60 7-221 9988</p>
                        <a href="https://www.google.com/maps?q=Johor+Bahru+City+Square,+Johor,+Malaysia" target="_blank" rel="noopener noreferrer">Get Directions</a>
                    </li>
                </ul>
            </div>
            
            <div class="map-container">
                <h2>Find Us on the Map</h2>
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15955.26307144526!2d101.70851153417825!3d3.157635935436266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc37d1a8bdf42b%3A0x7a4e4a57bd73b4b9!2sSuria%20KLCC!5e0!3m2!1sen!2smy!4v1635954900929!5m2!1sen!2smy"
                  width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<style>
@import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  background-color: #f4f4f4;
}

header {
  background-color: #ffffff;
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
  color: #007BFF;
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

.search-bar input {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 20px;
}

.user-icons a {
  margin-left: 15px;
  color: #333;
  text-decoration: none;
}

.user-icons a:hover {
  color: #007BFF;
}

.user-icons i {
  font-size: 20px;
}


#store-location h1 {
  text-align: center;
  font-size: 2.5em;
  margin-bottom: 20px;
  color: #333;
}

.store-map-container {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  padding: 0px 30px 50px 30px;
}

.store-list {
  flex: 1;
  max-width: 50%;
  margin-bottom: 20px;
}

.store-list ul {
  list-style: none;
  padding: 0;
}

.store-list li {
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin: 10px 0;
  padding: 15px;
}

.store-list h3 {
  margin: 0 0 10px 0;
  font-size: 1.5em;
}

.store-list p {
  margin: 5px 0;
}

.store-list a {
  display: inline-block;
  margin-top: 10px;
  color: #007BFF;
  text-decoration: none;
}

.store-list a:hover {
  text-decoration: underline;
}

.map-container {
  flex: 1;
  max-width: 50%;
  margin-top: 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #f9f9f9;
  border-radius: 10px;
  padding: 20px;
}

.map-container h2 {
  font-size: 24px;
  margin-bottom: 20px;
  color: #333;
}

.map-container iframe {
  border-radius: 10px;
  width: 100%;
  height: 500px;
  border: none;
}

/* Ensure responsiveness */
@media (max-width: 768px) {
  .store-map-container {
    flex-direction: column;
  }

  .store-list, .map-container {
    max-width: 100%;
  }
}

.fade-in {
  opacity: 0;
  transform: translateY(50px);
  animation: fadeInUp 1s ease forwards;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

</style>

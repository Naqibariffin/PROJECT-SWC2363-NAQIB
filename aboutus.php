<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  </head>
  <body>    

  <?php include 'header.php'; ?>


  <section id="our-story" class="fade-in">
      <div class="container">
          <h1>Our Story</h1>
          <p>POPBIQ was born in <b>August 2024</b> fueled by a passion for collectible figures and the art of storytelling. Inspired by the creativity and charm of designer toys, we set out on a mission to bring joy and inspiration to figure enthusiasts worldwide.</p><br>
          <p>From our humble beginnings as avid collectors, POPBIQ has evolved into a hub for designer figures and pop culture treasures. Our store is curated with love, offering a wide range of limited-edition collectibles, including Popmart-style figures and exclusive collaborations.</p><br>
          <p>At POPBIQ, we cherish the artistry and imagination that every figure embodies. Whether you're a seasoned collector or a newcomer, we strive to provide a vibrant, inclusive community where your love for collectibles can thrive.</p>
      </div>
  </section>

  <section id="our-team" class="fade-in">
      <div class="container">
          <h1>Meet Our Team</h1>
          <div class="team-grid">
              <?php
              $team_members = [
                  ["name" => "Naqib Asyraaf", "role" => "Founder & Curator", "image" => "naqib.jpeg", "description" => "Naqib's keen eye for design and passion for collectibles inspire every figure in POPBIQ's collection."],
                  ["name" => "Hae-In", "role" => "Creative Director", "image" => "haein.jpg", "description" => "Hae-In leads our creative vision, ensuring POPBIQ remains a home for unique and innovative designs."],
                  ["name" => "Ahn Hyo-Seop", "role" => "Community Manager", "image" => "ahnhyoseop.jpg", "description" => "Hyo-Seop connects POPBIQ with its vibrant community, organizing events and sharing the magic of figures."]
              ];
              
              foreach ($team_members as $member) {
                  echo "<div class='team-member'>
                          <img src='{$member['image']}' alt='{$member['name']}'>
                          <h3>{$member['name']}</h3>
                          <p>{$member['role']}</p>
                          <p>{$member['description']}</p>
                        </div>";
              }
              ?>
          </div>
      </div>
  </section>

  <?php include 'footer.php'; ?>

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
#our-story {
  background-color: white;
  padding: 60px 20px;
  text-align: center;
}

#our-story h1 {
  margin-bottom: 20px;
  font-size: 36px;
  color: #333;
}

#our-story p {
  max-width: 800px;
  margin: 0 auto;
  line-height: 1.6;
  color: #555;
}

#our-team {
  background-color: #f9f9f9;
  padding: 60px 20px;
}

#our-team h1 {
  text-align: center;
  font-size: 36px;
  margin-bottom: 50px;
  color: #333;
}

.team-grid {
  display: flex;
  justify-content: space-around;
  gap: 20px;
  flex-wrap: wrap;
}

.team-member {
  background-color: white;
  padding: 20px;
  text-align: center;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  width: 300px;
}

.team-member:hover {
  transform: translateY(-10px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.team-member img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
}

.team-member h3 {
  margin-top: 15px;
  font-size: 22px;
  color: #333;
}

.team-member p {
  color: #777;
  margin-top: 10px;
  line-height: 1.5;
}

/* Fade-in animation */
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

@media (max-width: 768px) {
  .team-grid {
    flex-direction: column;
    align-items: center;
  }

  .team-member {
    width: 100%;
    max-width: 350px;
  }
}

</style>
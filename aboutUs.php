<?php include 'footHead/header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ARS Flowershop - About Us</title>
  <style>
    /* Base Styles */
    body {
      background-color: #FFF9F9;
      color: #122349;
      margin-top: 120px;
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    /* Title Section */
    .page-title {
      font-size: 2.5rem;
      text-align: center;
      margin-bottom: 20px;
      color: #b10e73;
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      position: relative;
    }

    .page-title:after {
      content: "";
      display: block;
      width: 100px;
      height: 3px;
      background: linear-gradient(90deg, #b10e73, #ffb6c1);
      margin: 10px auto;
    }

    .page-subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 40px;
      font-size: 1.1rem;
    }

    /* History Timeline */
    .history-container {
      display: flex;
      flex-direction: column;
      gap: 50px;
      margin: 60px 0;
    }

    .history-item {
      display: flex;
      align-items: center;
      gap: 40px;
    }

    .history-item.reverse {
      flex-direction: row-reverse;
    }

    .history-image {
      flex: 1;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      min-height: 350px;
    }

    .history-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .history-content {
      flex: 1;
      padding: 20px;
    }

    .history-year {
      font-size: 1.8rem;
      color: #b10e73;
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      margin-bottom: 15px;
    }

    .history-title {
      font-size: 1.5rem;
      color: #122349;
      margin-bottom: 15px;
      font-family: 'Fraunces_72pt-Light';
    }

    .history-text {
      color: #555;
      margin-bottom: 20px;
    }

    /* Mission & Vision */
    .mission-vision {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      margin: 60px 0;
    }

    .mv-card {
      background-color: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .mv-title {
      font-size: 1.5rem;
      color: #b10e73;
      margin-bottom: 20px;
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .mv-title:before {
      content: "❀";
      color: #ffb6c1;
      font-size: 1.8rem;
    }

    .mv-text {
      color: #555;
      font-size: 1.1rem;
    }

    /* Team Section */
    .team-section {
      margin: 60px 0;
    }

    .team-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }

    .team-card {
      background-color: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .team-image {
      width: 100%;
      height: 250px;
      object-fit: cover;
      border-bottom: 3px solid #ffb6c1;
    }

    .team-info {
      padding: 25px 15px;
    }

    .team-name {
      font-size: 1.3rem;
      color: #122349;
      margin-bottom: 5px;
      font-family: 'Fraunces_72pt-Light';
    }

    .team-role {
      color: #b10e73;
      font-weight: bold;
      margin-bottom: 15px;
    }

    /* Floral Accents */
    .floral-divider {
      text-align: center;
      margin: 40px 0;
      position: relative;
    }

    .floral-divider:before {
      content: "❀❀❀";
      color: #ffb6c1;
      font-size: 1.5rem;
      letter-spacing: 10px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {

      .history-item,
      .history-item.reverse {
        flex-direction: column;
      }

      .history-image {
        width: 100%;
      }

      .mission-vision {
        grid-template-columns: 1fr;
      }
    }

    /* Font Faces */
    @font-face {
      font-family: 'Fraunces_72pt-Light';
      src: url('fonts/Fraunces_72pt-Light.ttf') format('truetype');
    }

    @font-face {
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      src: url('fonts/Fraunces_72pt-SemiBoldItalic.ttf') format('truetype');
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="page-title">Our Blossoming Story</h1>
    <p class="page-subtitle">Three generations of floral passion in Davao City</p>

    <div class="floral-divider"></div>

    <!-- History Timeline -->
    <div class="history-container">
      <div class="history-item">
        <div class="history-image">
          <img src="pictures/bankerohan-market.jpg" alt="Mrs. Lydia Ladao Quiñones">
        </div>
        <div class="history-content">
          <div class="history-year">1978</div>
          <h3 class="history-title">The Humble Beginnings</h3>
          <p class="history-text">
            Since its founding in 1978 by Mrs. Lydia Ladao Quiñones, Ars Flower Shop Davao has blossomed from a small stall in Bankerohan Public Market into a cherished name in the local floral industry. Lydia's dedication to crafting beautiful, high-quality floral arrangements quickly established the shop as a reliable choice for customers in Davao City.
          </p>
          <p class="history-text">
            With a vision for bringing joy to every occasion, Lydia's passion and commitment laid the foundation for a family legacy that would flourish through generations.
          </p>
        </div>
      </div>

      <div class="history-item reverse">
        <div class="history-image">
          <img src="pictures/2020.jpg" alt="Ms. Armila Ladao Quiñones">
        </div>
        <div class="history-content">
          <div class="history-year">2000s</div>
          <h3 class="history-title">A New Generation Blooms</h3>
          <p class="history-text">
            As years passed, Lydia's youngest daughter, Ms. Armila Ladao Quiñones, took over the business. Infusing new ideas while upholding Lydia's standards of excellence, Armila expanded the shop's offerings to include unique event decorations, custom backdrop designs, and a wider range of floral products.
          </p>
          <p class="history-text">
            Recognizing the evolving tastes of a growing customer base, she introduced both fresh and artificial arrangements that catered to diverse needs and allowed Ar's Flower Shop to reach a broader audience.
          </p>
        </div>
      </div>

      <div class="history-item">
        <div class="history-image">
          <img src="pictures/newchap.jpg" alt="Buhangin Road Location">
        </div>
        <div class="history-content">
          <div class="history-year">2024</div>
          <h3 class="history-title">A New Chapter Unfolds</h3>
          <p class="history-text">
            In January 2024, with the help of her eldest granddaughter, Ms. Bianca Freya Facura, Armila relocated the main shop to Buhangin Road, Davao City. This new, larger space not only allowed for better accessibility for walk-in customers but also expanded the shop's capacity to handle larger orders and more personalized services.
          </p>
          <p class="history-text">
            The new location symbolized a milestone in Ars Flower Shop's growth, reflecting its commitment to adapt and evolve while staying true to its family-driven, customer-first values.
          </p>
        </div>
      </div>
    </div>

    <div class="floral-divider"></div>

    <!-- Mission & Vision -->
    <div class="mission-vision">
      <div class="mv-card">
        <h3 class="mv-title">Our Vision</h3>
        <p class="mv-text">
          To be the leading flower shop in Davao City, known for creating meaningful connections through exceptional floral artistry, inspiring beauty, and uplifting celebrations across all occasions.
        </p>
      </div>

      <div class="mv-card">
        <h3 class="mv-title">Our Mission</h3>
        <p class="mv-text">
          Ar's Flower Shop Davao is committed to delivering well made floral arrangements and personalized services that enhance life's special moments. We aim to combine creativity, quality, and a touch of local authenticity to exceed our customers' expectations, ensuring a memorable experience with each bouquet. Our mission is to foster a legacy of warmth, tradition, and craftsmanship for generations to come.
        </p>
      </div>
    </div>

    <div class="floral-divider"></div>

    <!-- Today Section -->
    <div class="today-section">
      <h2 class="page-title">ARS Flower Shop Today</h2>
      <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <p style="font-size: 1.1rem; color: #555; margin-bottom: 30px;">
          Today, Ars Flower Shop Davao stands as a testament to the strength of family, tradition, and innovation. We continue to build on Lydia's dream of providing exceptional floral artistry, touching lives across generations and becoming an enduring part of life's celebrations in Davao City and beyond.
        </p>
        <p style="font-size: 1.1rem; color: #555;">
          As we look to the future, we remain committed to growing sustainably, deepening our local roots, and enhancing the lives of our community through the art of flowers.
        </p>
      </div>
    </div>

    <div class="floral-divider"></div>

    <!-- Team Section -->
    <div class="team-section">
      <h2 class="page-title">Our Family</h2>
      <p class="page-subtitle">The passionate hearts behind our floral creations</p>

      <div class="team-grid">
        <div class="team-card">
          <img src="pictures/lydia.jpg" alt="Mrs. Lydia Ladao Quiñones" class="team-image">
          <div class="team-info">
            <h4 class="team-name">Mrs. Lydia Ladao Quiñones</h4>
            <div class="team-role">Founder (1978)</div>
          </div>
        </div>

        <div class="team-card">
          <img src="pictures/armila.jpg" alt="Ms. Armila Ladao Quiñones" class="team-image">
          <div class="team-info">
            <h4 class="team-name">Ms. Armila Ladao Quiñones</h4>
            <div class="team-role">Current Owner</div>
          </div>
        </div>

        <div class="team-card">
          <img src="pictures/bianca.jpg" alt="Ms. Bianca Freya Facura" class="team-image">
          <div class="team-info">
            <h4 class="team-name">Ms. Bianca Freya Facura</h4>
            <div class="team-role">Third Generation</div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footHead/footer.php'; ?>
</body>

</html>
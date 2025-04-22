<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ARS Flowershop - Home</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: sans-serif;
      background-color: white;
    }

    /*CONTAINERS SECTION*/
    .grid-container {
      display: grid;
      grid-template-columns: 38% 62%;
      margin-top: 20px;
      margin-left: 5px;
      margin-right: 5px;
    }

    .box {
      height: 750px;
      background-color: #FEFAF0;
      overflow: hidden;
    }

    .box2 img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center top;
      display: block;
    }

    .box3 {
      color: white;
      grid-column: 1 / span 2;
      height: 80px;
      background-color: #122349;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin-top: 30px;
      text-align: center;
    }

    .box4 {
      height: auto;
      background-color: whitesmoke;
      justify-content: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      margin-top: 30px;
      font-size: 20px;
      grid-column: 1 / span 2;
      padding: 20px;
    }

    .box5 {
      height: 100px;
      background-color: whitesmoke;
      margin-top: 30px;
    }

    /* PRODUCT GRID STYLES */
    .featured-products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 30px;
      width: 100%;
      max-width: 1200px;
      margin-top: 20px;
    }

    .product-card {
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-image {
      height: 200px;
      width: 100%;
      object-fit: cover;
    }

    .product-info {
      padding: 15px;
      text-align: center;
    }

    .product-title {
      font-size: 18px;
      color: #122349;
      margin-bottom: 10px;
    }

    .product-price {
      font-size: 20px;
      color: #800000;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .view-product {
      display: inline-block;
      background-color: #800000;
      color: white;
      padding: 8px 20px;
      border-radius: 25px;
      text-decoration: none;
      transition: opacity 0.3s;
    }

    .view-product:hover {
      opacity: 0.9;
    }

    .section-title {
      font-size: 28px;
      color: #122349;
      margin-bottom: 20px;
      font-family: 'AvenirLTStd-LightOblique', sans-serif;
    }

    .loading {
      text-align: center;
      padding: 20px;
      font-size: 18px;
      color: #666;
      grid-column: 1 / -1;
    }

    /* Existing styles */
    .box3 h6, .box3 p {
      font-family: 'AvenirLTStd-LightOblique', sans-serif;
      font-size: 1.25rem;
    }
    
    .box3 p {
      letter-spacing: 2px;
    }

    .underline-text {
      text-decoration: underline;
    }

    .letter-spacing {
      letter-spacing: 2px;
    }
    
    .wordmargin {
      margin-left: 20px;
    }
    
    @font-face {
      font-family: 'AvenirLTStd-LightOblique';
      src: url('fonts/AvenirLTStd-LightOblique.otf') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    .shop-button1 {
      background-color: #800000;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
      font-size: 16px;
      cursor: pointer;
      font-family: Arial, sans-serif;
      position: absolute;
      top: 700px;
      left: 250px;
    }

    .shop-button1:hover {
      opacity: 0.9;
    }
  </style>
</head>

<body>
  <div class="grid-container">
    <div class="box box1">
      <button class="shop-button1" onclick="window.location.href='shop.php'">Shop now</button>
    </div>
    <div class="box box2">
      <img src="pictures/rose.png" alt="Ar's Flower Rose Bouquet">
    </div>
  </div>
  
  <div class="box3">
    <h6>Elevate your holiday decor with festive arrangements and centerpieces.</h6>
    <p class="underline-text letter-spacing wordmargin" onclick="window.location.href='shop.php'">Shop Now!</p>
  </div>
  
  <div class="box4">
    <h2 class="section-title">Featured Arrangements</h2>
    <div class="featured-products" id="featured-products-container">
      <div class="loading">Loading featured products...</div>
    </div>
  </div>
  
  <div class="box5"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Fetch featured products from API
      fetch('api/get_featured_products.php')
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(products => {
          const container = document.getElementById('featured-products-container');
          
          // Clear loading message
          container.innerHTML = '';
          
          if (products.length === 0) {
            container.innerHTML = '<div class="no-products">No featured products available at the moment.</div>';
            return;
          }
          
          // Create product cards for each featured product
          products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
              <img src="${product.image_url}" alt="${product.name}" class="product-image">
              <div class="product-info">
                <h3 class="product-title">${product.name}</h3>
                <div class="product-price">P${product.price.toFixed(2)}</div>
                <a href="shop.php#product-${product.id}" class="view-product">View Product</a>
              </div>
            `;
            container.appendChild(productCard);
          });
        })
        .catch(error => {
          console.error('Error fetching featured products:', error);
          document.getElementById('featured-products-container').innerHTML = 
            '<div class="no-products">Error loading featured products. Please try again later.</div>';
        });
    });
  </script>
</body>
</html>
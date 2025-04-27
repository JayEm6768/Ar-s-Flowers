<?php include 'footHead/header.php'; ?>


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ARS Flowershop - Home</title>
  <style>
    /* Base Styles */
    
    body {
      margin: 0;
      padding: 0;
      font-family: sans-serif;
      background-color: #FFF9F9;
      margin-top: 120px;
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
      background-color: white;
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
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgba(177, 14, 115, 0.15);
    }

    .product-image {
      height: 220px;
      width: 100%;
      object-fit: cover;
      border-bottom: 3px solid #ffb6c1;
    }

    .product-info {
      padding: 25px;
      text-align: center;
    }

    .product-title {
      font-size: 1.2rem;
      color: #122349;
      margin-bottom: 10px;
      font-family: 'Fraunces_72pt-Light';
    }

    .product-price {
      font-size: 1.3rem;
      color: #850000;
      font-weight: bold;
      margin-bottom: 15px;
    }

    /* Updated Button Styles to Match Events Page */
    .view-product, .shop-button1, .shop-button2 {
      display: inline-block;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      padding: 10px 25px;
      border-radius: 30px;
      text-decoration: none;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
      font-weight: 600;
      box-shadow: 0 3px 10px rgba(177, 14, 115, 0.3);
      font-family: 'Arial', sans-serif;
    }

    .view-product:hover, .shop-button1:hover, .shop-button2:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
    }

    .section-title {
      font-size: 2.5rem;
      color: #b10e73;
      margin-bottom: 20px;
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      position: relative;
      text-align: center;
    }

    .section-title:after {
      content: "";
      display: block;
      width: 100px;
      height: 3px;
      background: linear-gradient(90deg, #b10e73, #ffb6c1);
      margin: 10px auto;
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
      font-family: 'AvenirLTStd-LightOblique';
      font-size: 1.25rem;
    }
    
    .box3 p {
      letter-spacing: 2px;
    }

    .underline-text {
      text-decoration: underline;
      cursor: pointer;
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
    @font-face {
      font-family: 'Fraunces_72pt-Light';
      src: url('fonts/Fraunces_72pt-Light.ttf') format('truetype');
      font-weight: normal;
      font-style: normal;
    }
    @font-face {
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      src: url('fonts/Fraunces_72pt-SemiBoldItalic.ttf') format('truetype');
      font-weight: normal;
      font-style: normal;
    }
    

    .shop-button1 {
      position: absolute;
      top: 550px;
      left: 250px;
    }

    .shop-button2 {
      position: absolute;
      top: 370px;
      left: 1020px;
    }
    
    .festives{
      font-size: 50px;
      font-family: 'Fraunces_72pt-SemiBoldItalic';
      position: absolute;
      top: 250px;
      left: 75px;
      color: #122349;
    }
    
    .Christmas{
      font-size: 30px;
      font-family: 'Fraunces_72pt-Light';
      position: absolute;
      top: 400px;
      left: 75px;
      color: #122349;
    }

    .div5 {
      height: auto;
      min-height: 300px;
      background-color: #F7E2DF;
      margin-top: 30px;
      padding: 40px;
      display: flex;
      align-items: center;
      gap: 40px;
      position: relative;
    }

    .div5pic {
       width: 400px;
        height: auto;
       object-fit: contain;
       flex-shrink: 0;
       margin-left: 370px;
       -webkit-mask-image: 
        /* Top fade */
        linear-gradient(to bottom, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        /* Bottom fade */
        linear-gradient(to top, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        /* Left fade */
        linear-gradient(to right, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        /* Right fade */
        linear-gradient(to left, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%);
    mask-image: 
        linear-gradient(to bottom, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        linear-gradient(to top, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        linear-gradient(to right, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%),
        linear-gradient(to left, 
            rgba(0,0,0,1) 0%, 
            rgba(0,0,0,1) 80%, 
            rgba(0,0,0,0) 100%);
    -webkit-mask-composite: destination-in;
    mask-composite: intersect;
    mask-mode: alpha;
    -webkit-mask-mode: alpha;
    }

    .div5text {
      font-size: 2.5rem;
      font-family: 'Fraunces_72pt-Light', serif;
      max-width: 600px;
      color: #122349;
      margin-left: 170px;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
      .div5 {
        flex-direction: column;
        text-align: center;
      }
      .div5pic {
        width: 100%;
        max-width: 550px;
        margin-left: 0;
      }
      .shop-button1, .shop-button2 {
        position: relative;
        top: auto;
        left: auto;
        margin-top: 20px;
      }
    }
    
    /* Floral Divider Matching Events Page */
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
    .whiteBackground{
      background-color: white;
    }
    .transparent-square {
  position: absolute; /* Allows free movement without affecting siblings */
  width: 500px;
  height: 400px;
  background-color: transparent;
  border: 5px solid white;
  
  /* Move the square using top/left/right/bottom */
  top: 60px; /* Adjust as needed */
  left: 345px; /* Adjust as needed */
}


/* Featured Products Section */
#featured-products-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    padding: 20px;
}

.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.product-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #b10e73;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
}

.product-info {
    padding: 15px;
}

.product-title {
    font-size: 1.1rem;
    margin-bottom: 8px;
    color: #122349;
}

.product-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #b10e73;
    margin-bottom: 8px;
}

.product-colors {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 12px;
}

.view-product {
    display: inline-block;
    padding: 8px 15px;
    background: #b10e73;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background 0.3s ease;
}

.view-product:hover {
    background: #850000;
}

.loading, .no-products {
    text-align: center;
    padding: 40px;
    grid-column: 1 / -1;
    color: #666;
}

.retry-button {
    margin-top: 10px;
    padding: 8px 20px;
    background: #b10e73;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.retry-button:hover {
    background: #850000;
}
  </style>


<body>
  <div class="grid-container">
    <div class="box box1"> 
      <h5><p class="festives">Flowers For The Festivities</p></h5>
      <p class="wordmargin Christmas">Spread the cheer with fresh<br>holiday blooms for your home or theirs!</p>
      <button class="shop-button1" onclick="window.location.href='productPage.php'">Shop now</button>
    </div>
    <div class="box box2">
      <img src="pictures/rose.png" alt="Ar's Flower Rose Bouquet">
    </div>
  </div>
  
  <div class="box3">
    <h6>Elevate your holiday decor with festive arrangements and centerpieces.</h6>
    <p class="underline-text letter-spacing wordmargin" onclick="window.location.href='productPage.php'">Shop Now!</p>
  </div>
  
  <div class="box4">
    <h2 class="section-title">Featured Arrangements</h2>

    <!-- This is where the fetching of the product will show in the homepage -->
    <div class="featured-products" id="featured-products-container">
      <div class="loading">Loading featured products...</div>
    </div>
    <div class="floral-divider"></div>
  </div>

  <div class="div5">
    <div class="transparent-square"></div>
    <img class="div5pic" src="pictures/joy.jpg" alt="">
    <p class="div5text">Joy that keeps blooming<br>The ultimate flower<br>subscription at the best value<br></p>
    <button class="shop-button2" onclick="window.location.href='productPage.php'">Shop now</button>
  </div>
  
  <div class="floral-divider"></div>

  <script>
document.addEventListener('DOMContentLoaded', function() {
    
    
    // Login modal handling
    const loginButton = document.getElementById('login-button');
    if (loginButton) {
        loginButton.addEventListener('click', function() {
            document.getElementById('login-modal').style.display = 'flex';
        });
    }

    // Fetch and display featured products
    fetchFeaturedProducts();
    
    function fetchFeaturedProducts() {
        const container = document.getElementById('featured-products-container');
        
        // Show loading state
        container.innerHTML = '<div class="loading">Loading featured products...</div>';
        
        fetch('api/getFeaturedProducts.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    renderFeaturedProducts(data.data);
                } else {
                    container.innerHTML = '<div class="no-products">No featured products available.</div>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                container.innerHTML = `
                    <div class="no-products">
                        Error loading featured products.<br>
                        <button onclick="fetchFeaturedProducts()" class="retry-button">Try Again</button>
                    </div>
                `;
            });
    }
    
    function renderFeaturedProducts(products) {
        const container = document.getElementById('featured-products-container');
        container.innerHTML = '';
        
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
                <div class="product-image-container">
                    <img src="dashboard/uploads/${product.image_url}" alt="${product.name}" 
                         class="product-image" loading="lazy">
                    <div class="product-badge">Featured</div>
                </div>
                <div class="product-info">
                    <h3 class="product-title">${product.name}</h3>
                    <div class="product-price">₱${product.price}</div>
                    <div class="product-colors">${product.color}</div>
                    <a href="productPage.php?product_id=${product.id}" class="view-product">
                        View Details
                    </a>
                </div>
            `;
            container.appendChild(productCard);
        });
    }
    
    // Make function available for retry button
    window.fetchFeaturedProducts = fetchFeaturedProducts;
});
</script>
    <?php include 'homepageContents/aboutUs_content.php'; ?>

  <div class="whiteBackground">
  <?php include 'homepageContents/events_content.php'; ?>
  </div>
  <?php include 'footHead/footer.php'; ?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ARS Flowershop Davao</title>
  <link rel="stylesheet" href="styles.css"> <!-- Optional external CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      position: relative;
      overflow-x: hidden;
    }

    .header {
      justify-content: space-between;
      position: relative;
      padding: 10px 40px;
      border-bottom: 2px solid #b10e73;
      background-color: #fff;
    }

    .logo img {
      height: 60px;
      width: auto;
    }

    .nav-links {
       position: absolute;
      left: 30px;
      top: 50%;
      transform: translateX(270%);
      display: flex;
      gap: 20px;
    }

    .nav-left, .nav-right {
      display: flex;
      gap: 60px;
      align-items: center;
    }

    .nav-links a {
      text-decoration: none;
      color: #850000;
      font-size: 18px;
      transition: 0.3s;
      cursor: pointer; /* Add pointer cursor to indicate clickable */
    }

    .nav-links a:hover {
      color: #b10e73;
    }

    /* Icons - right side */
    .icons {
    position: absolute;
    right: 30px;
    top: 50%;
    transform: translateX(-200%);
    display: flex;
    gap: 15px;
    }

    .cart-icon {
      position: relative;
    }

    .cart-icon span {
      position: absolute;
      top: -10px;
      right: -10px;
      background-color: #e5a9a9;
      color: #850000;
      font-size: 12px;
      border-radius: 50%;
      padding: 3px 7px;
    }

    .icons i {
      font-size: 20px;
      color: #850000;
      cursor: pointer;
      transition: color 0.3s;
    }

    .icons i:hover {
      color: #b10e73;
    }

    .logo img {
    padding-left: 200px;
    height: 100px;
    width: auto;
    }
    
    /* Cart Sidebar Styles */
    .cart-sidebar {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      height: 100%;
      background-color: #fff;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
      transition: right 0.3s ease-in-out;
      z-index: 1000;
      padding: 20px;
      overflow-y: auto;
    }
    
    .cart-sidebar.active {
      right: 0;
    }
    
    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
      margin-bottom: 15px;
    }
    
    .close-cart {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #850000;
    }
    
    .cart-items {
      margin-bottom: 20px;
    }
    
    .cart-item {
      display: flex;
      margin-bottom: 15px;
      padding-bottom: 15px;
      border-bottom: 1px solid #f5f5f5;
    }
    
    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      margin-right: 15px;
    }
    
    .item-details {
      flex: 1;
    }
    
    .item-name {
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .item-price {
      color: #b10e73;
      margin-bottom: 5px;
    }
    
    .item-quantity {
      display: flex;
      align-items: center;
    }
    
    .quantity-btn {
      background: #f5f5f5;
      border: none;
      width: 25px;
      height: 25px;
      cursor: pointer;
    }
    
    .quantity-input {
      width: 40px;
      text-align: center;
      margin: 0 5px;
    }
    
    .remove-item {
      color: #850000;
      cursor: pointer;
      font-size: 12px;
      margin-top: 5px;
      display: inline-block;
    }
    
    .cart-total {
      font-weight: bold;
      font-size: 18px;
      text-align: right;
      margin-bottom: 20px;
    }
    
    .checkout-btn {
      width: 100%;
      padding: 12px;
      background-color: #b10e73;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }
    
    .checkout-btn:hover {
      background-color: #850000;
    }
    
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
    }
    
    .overlay.active {
      display: block;
    }
    
    .empty-cart {
      text-align: center;
      padding: 40px 0;
      color: #850000;
    }

    /* NEW LOGIN MODAL STYLES */
    .login-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .login-modal-content {
      background: white;
      padding: 30px;
      border-radius: 10px;
      width: 350px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      position: relative;
      background-image: url('https://i.imgur.com/J6l6aXW.jpg');
      background-size: cover;
      background-blend-mode: overlay;
      background-color: rgba(255, 255, 255, 0.9);
    }

    .close-login {
      position: absolute;
      top: 10px;
      right: 15px;
      font-size: 24px;
      cursor: pointer;
      color: #850000;
    }

    .login-form-container h2 {
      color: #b10e73;
      margin-bottom: 10px;
    }

    .login-form-container p {
      color: #555;
      margin-bottom: 20px;
    }

    .login-form-container input {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .login-form-container button {
      width: 100%;
      padding: 12px;
      background: #b10e73;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
      transition: background-color 0.3s;
    }

    .login-form-container button:hover {
      background: #850000;
    }

    .switch-form {
      margin-top: 15px;
      color: #555;
    }

    .switch-form a {
      color: #b10e73;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <div class="header">
    <div class="logo">
      <img src="pictures/arsFlowerHeaderLogo.png" onclick="window.location.href='home.php'"> <!-- Logo links to home -->
    </div>
    <div class="nav-links">
      <a onclick="window.location.href='home.php'">Home</a>
      <a onclick="window.location.href='productPage.php'">Shop</a>
      <a onclick="window.location.href='aboutUs.php'">About Us</a>
      <a onclick="window.location.href='events.php'">Events</a>
    </div>
    <div class="icons">
      <!-- This is the profiles icon - now triggers login modal -->
      <i class="fa-regular fa-user" id="login-button"></i>

      <!-- This is the cart icon-->
      <div class="cart-icon">
        <i class="fa-solid fa-cart-shopping" id="cart-button"></i>
        <span id="cart-count"></span>
      </div>
    </div>
  </div>

  <!-- Cart Sidebar -->
  <div class="overlay" id="overlay"></div>
  <div class="cart-sidebar" id="cart-sidebar">
    <div class="cart-header">
      <h2>Your Cart</h2>
      <button class="close-cart" id="close-cart">&times;</button>
    </div>
    <div class="cart-items" id="cart-items">
      <!-- Cart items will be dynamically inserted here -->
      <div class="empty-cart">Your cart is empty</div>
    </div>
    <div class="cart-total" id="cart-total">Total: ₱0.00</div>
    <button class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
  </div>

  <!-- NEW LOGIN MODAL -->
  <div class="login-modal" id="login-modal">
    <div class="login-modal-content">
      <span class="close-login" id="close-login">&times;</span>
      
      <!-- Login Form -->
      <div class="login-form-container" id="login-form">
        <h2>Welcome Back! 🌸</h2>
        <p>Sign in to order your favorite flowers</p>
        <form id="user-login">
          <input type="text" placeholder="Username" required>
          <input type="password" placeholder="Password" required>
          <button type="submit">Log In</button>
        </form>
        <p class="switch-form">Don't have an account? <a href="#" id="show-signup">Create one</a></p>
      </div>
      
      <!-- Signup Form (hidden by default) -->
      <div class="login-form-container" id="signup-form" style="display: none;">
        <h2>Join Us! 🌺</h2>
        <p>Create an account to start shopping</p>
        <form id="user-signup">
          <input type="text" placeholder="Full Name" required>
          <input type="text" placeholder="Username" required>
          <input type="password" placeholder="Password" required>
          <input type="text" placeholder="Address" required>
          <input type="date" placeholder="Birthdate" required>
          <button type="submit">Sign Up</button>
        </form>
        <p class="switch-form">Already have an account? <a href="#" id="show-login">Log In</a></p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      
      // Cart functionality
      const cartButton = document.getElementById('cart-button');
      const closeCart = document.getElementById('close-cart');
      const cartSidebar = document.getElementById('cart-sidebar');
      const overlay = document.getElementById('overlay');
      const cartItemsContainer = document.getElementById('cart-items');
      const cartCount = document.getElementById('cart-count');
      const cartTotal = document.getElementById('cart-total');
      const checkoutBtn = document.getElementById('checkout-btn');
      
      // Login modal elements
      const loginButton = document.getElementById('login-button');
      const loginModal = document.getElementById('login-modal');
      const closeLogin = document.getElementById('close-login');
      const showSignup = document.getElementById('show-signup');
      const showLogin = document.getElementById('show-login');
      const loginForm = document.getElementById('login-form');
      const signupForm = document.getElementById('signup-form');
      
      // Initialize cart from localStorage or create empty cart
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      console.log('inside cart:', cart); //for debugging
      
      // Update cart count on page load
      updateCartCount();
      
      // Toggle cart sidebar
      cartButton.addEventListener('click', toggleCart);
      closeCart.addEventListener('click', toggleCart);
      overlay.addEventListener('click', toggleCart);
      
      // Login modal functionality
      loginButton.addEventListener('click', toggleLoginModal);
      closeLogin.addEventListener('click', toggleLoginModal);
      
      // Switch between login and signup forms
      showSignup.addEventListener('click', function(e) {
        e.preventDefault();
        loginForm.style.display = 'none';
        signupForm.style.display = 'block';
      });
      
      showLogin.addEventListener('click', function(e) {
        e.preventDefault();
        signupForm.style.display = 'none';
        loginForm.style.display = 'block';
      });
      
      // Form submissions
      document.getElementById('user-login').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Login successful!'); // Replace with actual login logic
        toggleLoginModal();
      });
      
      document.getElementById('user-signup').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Account created successfully!'); // Replace with actual signup logic
        signupForm.style.display = 'none';
        loginForm.style.display = 'block';
      });
      
      // Checkout button
      checkoutBtn.addEventListener('click', function() {
        if (cart.length > 0) {
          window.location.href = 'checkout.html';
        }
      });
      
      function toggleCart() {
        cartSidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        if (cartSidebar.classList.contains('active')) {
          renderCartItems();
        }
      }
      
      function toggleLoginModal() {
        loginModal.style.display = loginModal.style.display === 'flex' ? 'none' : 'flex';
        overlay.classList.toggle('active');
        
        // Reset to login form when opening
        if (loginModal.style.display === 'flex') {
          signupForm.style.display = 'none';
          loginForm.style.display = 'block';
        }
      }
      
      function renderCartItems() {
        if (cart.length === 0) {
          cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
          cartTotal.textContent = 'Total: ₱0.00';
          checkoutBtn.disabled = true;
          return;
        }
        
        checkoutBtn.disabled = false;
        
        let itemsHTML = '';
        let total = 0;
        
        cart.forEach((item, index) => {
          total += item.price * item.quantity;
          
          itemsHTML += `
            <div class="cart-item" data-id="${item.id}">
              <img src="${item.image}" alt="${item.name}">
              <div class="item-details">
                <div class="item-name">${item.name}</div>
                <div class="item-price">₱${Number(item.price).toFixed(2)}</div>
                <div class="item-quantity">
                  <button class="quantity-btn minus" data-index="${index}">-</button>
                  <input type="text" class="quantity-input" value="${item.quantity}" readonly>
                  <button class="quantity-btn plus" data-index="${index}">+</button>
                </div>
                <span class="remove-item" data-index="${index}">Remove</span>
              </div>
            </div>
          `;
        });
        
        cartItemsContainer.innerHTML = itemsHTML;
        cartTotal.textContent = `Total: ₱${total.toFixed(2)}`;
        
        // Add event listeners to quantity buttons
        document.querySelectorAll('.minus').forEach(button => {
          button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            updateQuantity(index, -1);
          });
        });
        
        document.querySelectorAll('.plus').forEach(button => {
          button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            updateQuantity(index, 1);
          });
        });
        
        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-item').forEach(button => {
          button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            removeItem(index);
          });
        });
      }
      
      function updateQuantity(index, change) {
        cart[index].quantity += change;
        
        if (cart[index].quantity < 1) {
          cart[index].quantity = 1;
        }
        
        saveCart();
        renderCartItems();
        updateCartCount();
      }
      
      function removeItem(index) {
        cart.splice(index, 1);
        saveCart();
        renderCartItems();
        updateCartCount();
      }
      
      function saveCart() {
        localStorage.setItem('cart', JSON.stringify(cart));
      }
      
      function updateCartCount() {
        const count = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = count;
      }
      
      // Close modals when clicking outside
      window.addEventListener('click', function(e) {
        if (e.target === overlay) {
          if (cartSidebar.classList.contains('active')) {
            toggleCart();
          }
          if (loginModal.style.display === 'flex') {
            toggleLoginModal();
          }
        }
      });
      
      // Example function to add an item to the cart (you would call this from your product pages)
      window.addToCart = function(product) {
        // Check if product already in cart
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
          existingItem.quantity += 1;
        } else {
          cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: 1
          });
        }
        
        saveCart();
        updateCartCount();
        
        // If cart is open, update the display
        if (cartSidebar.classList.contains('active')) {
          renderCartItems();
        }
      };
      
      // Example product data structure for testing
      window.exampleProducts = [
        {
          id: 1,
          name: "Red Roses Bouquet",
          price: 1200,
          image: "pictures/red-roses.jpg"
        },
        {
          id: 2,
          name: "Tulips Arrangement",
          price: 1500,
          image: "pictures/tulips.jpg"
        }
      ];
    });
  </script>
</body>
</html>
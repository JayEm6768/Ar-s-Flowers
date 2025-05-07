<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>ARS Flowershop Davao</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      -webkit-text-size-adjust: 100%;
      -moz-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }

    html {
      scroll-behavior: smooth;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    body {
      font-family: 'Arial', sans-serif;
      position: relative;
      overflow-x: hidden;
      background-color: #FFF9F9;
      line-height: 1.5;
    }

    /* Modern Header Styles */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background-color: rgba(255, 255, 255, 0.98);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      transition: all 0.4s ease;
      box-shadow: 0 2px 20px rgba(177, 14, 115, 0.1);
      border-bottom: 2px solid #ffb6c1;
      height: 110px;
    }

    .header.hidden {
      transform: translateY(-100%);
    }

    /* Logo Styles */
    .logo img {
      height: 80px;
      width: auto;
      max-width: none;
      transition: transform 0.3s;
      cursor: pointer;
      display: block;
    }

    .logo img:hover {
      transform: scale(1.05);
    }

    /* Navigation Links */
    .nav-links {
      display: flex;
      gap: 40px;
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }

    .nav-links a {
      text-decoration: none;
      color: #122349;
      font-size: 18px;
      font-weight: 600;
      transition: all 0.3s;
      position: relative;
      padding: 5px 0;
      cursor: pointer;
      white-space: nowrap;
    }

    .nav-links a:hover {
      color: #b10e73;
      cursor: pointer;
    }

    .nav-links a:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #b10e73, #ff6b9e);
      transition: width 0.3s;
    }

    .nav-links a:hover:after {
      width: 100%;
      cursor: pointer;
    }

    /* Icons - right side */
    .icons {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .cart-icon {
      position: relative;
    }

    .cart-icon span {
      position: absolute;
      top: -8px;
      right: -8px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      font-size: 12px;
      border-radius: 50%;
      padding: 3px 7px;
      font-weight: bold;
    }

    .icons i {
      font-size: 24px;
      color: #122349;
      cursor: pointer;
      transition: all 0.3s;
      width: 24px;
      height: 24px;
      text-align: center;
      line-height: 24px;
    }

    .icons i:hover {
      color: #b10e73;
      transform: translateY(-2px);
    }

    /* Cart Sidebar Styles */
    .cart-sidebar {
      position: fixed;
      top: 0;
      right: -400px;
      width: 400px;
      height: 100%;
      background-color: #fff;
      box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
      transition: right 0.4s cubic-bezier(0.22, 1, 0.36, 1);
      z-index: 1000;
      padding: 25px;
      overflow-y: auto;
    }

    .cart-sidebar.active {
      right: 0;
    }

    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 20px;
      border-bottom: 2px solid #ffb6c1;
      margin-bottom: 20px;
    }

    .cart-header h2 {
      color: #122349;
      font-size: 1.5rem;
    }

    .close-cart {
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #850000;
      transition: transform 0.3s;
    }

    .close-cart:hover {
      transform: rotate(90deg);
    }

    .cart-items {
      margin-bottom: 25px;
    }

    .cart-item {
      display: flex;
      margin-bottom: 20px;
      padding-bottom: 20px;
      border-bottom: 1px solid #f0f0f0;
    }

    .cart-item img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
    }

    .item-details {
      flex: 1;
    }

    .item-name {
      font-weight: bold;
      margin-bottom: 5px;
      color: #122349;
    }

    .item-price {
      color: #b10e73;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .item-quantity {
      display: flex;
      align-items: center;
      margin-bottom: 5px;
    }

    .quantity-btn {
      background: #f5f5f5;
      border: none;
      width: 28px;
      height: 28px;
      cursor: pointer;
      border-radius: 4px;
      font-weight: bold;
      transition: all 0.2s;
    }

    .quantity-btn:hover {
      background: #e0e0e0;
    }

    .quantity-input {
      width: 40px;
      text-align: center;
      margin: 0 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 5px;
    }

    .remove-item {
      color: #850000;
      cursor: pointer;
      font-size: 0.9rem;
      margin-top: 5px;
      display: inline-block;
      transition: color 0.2s;
    }

    .remove-item:hover {
      color: #b10e73;
      text-decoration: underline;
    }

    .cart-total {
      font-weight: bold;
      font-size: 1.2rem;
      text-align: right;
      margin: 25px 0;
      color: #122349;
    }

    .checkout-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 3px 10px rgba(177, 14, 115, 0.3);
    }

    .checkout-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
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
      backdrop-filter: blur(3px);
    }

    .overlay.active {
      display: block;
    }

    .empty-cart {
      text-align: center;
      padding: 40px 0;
      color: #850000;
      font-size: 1.1rem;
    }

    /* Modern Login Modal */
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
      backdrop-filter: blur(3px);
    }

    .login-modal-content {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      animation: modalFadeIn 0.4s;
    }

    @keyframes modalFadeIn {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .close-login {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 1.5rem;
      cursor: pointer;
      color: #850000;
      transition: transform 0.3s;
    }

    .close-login:hover {
      transform: rotate(90deg);
    }

    .login-form-container h2 {
      color: #b10e73;
      margin-bottom: 15px;
      font-size: 1.8rem;
    }

    .login-form-container p {
      color: #555;
      margin-bottom: 25px;
      font-size: 1rem;
    }

    .login-form-container input {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    .login-form-container input:focus {
      border-color: #b10e73;
      outline: none;
    }

    .login-form-container button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      margin-top: 15px;
      font-size: 1rem;
      transition: all 0.3s;
    }

    .login-form-container button:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
    }

    .switch-form {
      margin-top: 20px;
      color: #555;
      font-size: 0.95rem;
    }

    .switch-form a {
      color: #b10e73;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.2s;
    }

    .switch-form a:hover {
      text-decoration: underline;
    }

    /* User Greeting and Logout Styles */
    .user-greeting {
      display: flex;
      align-items: center;
      gap: 15px;
      order: 2;
    }

    .greeting-text {
      font-size: 16px;
      font-weight: 600;
      color: #122349;
      white-space: nowrap;
      cursor: pointer;
      transition: all 0.3s;
      position: relative;
    }

    .greeting-text:hover {
      color: #b10e73;
      transform: translateY(-1px);
    }

    .greeting-text:after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, #b10e73, #ff6b9e);
      transition: width 0.3s;
    }

    .greeting-text:hover:after {
      width: 100%;
    }

    .logout-btn {
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 20px;
      padding: 8px 15px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 2px 5px rgba(177, 14, 115, 0.2);
    }

    .logout-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(177, 14, 115, 0.3);
    }

    .logged-in-icons {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    /* Confirmation Modal Styles */
    .confirm-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1001;
      justify-content: center;
      align-items: center;
      backdrop-filter: blur(3px);
    }

    .confirm-modal-content {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      position: relative;
      animation: modalFadeIn 0.4s;
    }

    .confirm-modal h2 {
      color: #b10e73;
      margin-bottom: 20px;
      font-size: 1.5rem;
    }

    .confirm-modal p {
      color: #555;
      margin-bottom: 25px;
      font-size: 1rem;
    }

    .confirm-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
    }

    .confirm-btn {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s;
      flex: 1;
    }

    .confirm-btn.logout {
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
    }

    .confirm-btn.cancel {
      background: #f0f0f0;
      color: #555;
    }

    .confirm-btn.logout:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
    }

    .confirm-btn.cancel:hover {
      background: #e0e0e0;
      transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
      .nav-links {
        gap: 30px;
      }
    }

    @media (max-width: 992px) {
      .header {
        padding: 15px 30px;
      }

      .nav-links {
        gap: 25px;
      }

      .nav-links a {
        font-size: 16px;
      }
    }

    @media (max-width: 768px) {
      .header {
        padding: 12px 20px;
        height: 90px;
      }

      .logo img {
        height: 65px;
      }

      .nav-links {
        gap: 15px;
      }

      .nav-links a {
        font-size: 15px;
      }

      .icons i {
        font-size: 20px;
        width: 20px;
        height: 20px;
        line-height: 20px;
      }

      .cart-sidebar {
        width: 350px;
      }
    }

    @media (max-width: 576px) {
      .header {
        padding: 10px 15px;
        height: 80px;
      }

      .logo img {
        height: 55px;
      }

      .nav-links {
        gap: 10px;
        left: 55%;
      }

      .nav-links a {
        font-size: 14px;
      }

      .icons {
        gap: 12px;
      }

      .cart-sidebar {
        width: 100%;
        max-width: 320px;
      }

      .user-greeting {
        gap: 8px;
      }

      .greeting-text {
        display: none;
      }

      .logout-btn {
        padding: 6px 10px;
        font-size: 11px;
      }

      .logged-in-icons {
        gap: 12px;
      }
    }
  </style>
</head>

<body>

  <div id="main-header" class="header">
    <div class="logo">
      <img src="pictures/arsFlowerHeaderLogo.png" onclick="window.location.href='home.php'">
    </div>
    <div class="nav-links">
      <a onclick="window.location.href='home.php'">Home</a>
      <a onclick="window.location.href='productPage.php'">Shop</a>
      <a onclick="window.location.href='aboutUs.php'">About Us</a>
      <a onclick="window.location.href='events.php'">Events</a>
    </div>
    <div class="icons">
      <i class="fa-regular fa-user" id="login-button"></i>
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
      <div class="empty-cart">Your cart is empty</div>
    </div>
    <div class="cart-total" id="cart-total">Total: ₱0.00</div>
    <button class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
  </div>

  <!-- Login Modal -->
  <div class="login-modal" id="login-modal">
    <div class="login-modal-content">
      <span class="close-login" id="close-login">&times;</span>

      <div class="login-form-container" id="login-form">
        <h2>Welcome Back! 🌸</h2>
        <p>Sign in to order your favorite flowers</p>
        <form id="user-login">
          <input type="text" id="login-username" placeholder="Username" required>
          <input type="password" id="login-pass" placeholder="Password" required>
          <button type="submit" id="logInBtn">Log In</button>
        </form>
        <p class="switch-form">Don't have an account? <a href="#" id="show-signup">Create one</a></p>
      </div>

      <div class="login-form-container" id="signup-form" style="display: none;">
        <h2>Join Us! 🌺</h2>
        <p>Create an account to start shopping</p>
        <form id="user-signup">
          <input type="text" id="name" placeholder="Full Name" required>
          <input type="text" id="signup-username" placeholder="Username" required>
          <input type="password" id="signup-pass" placeholder="Password" required>
          <input type="text" id="signup-email" placeholder="Email Address" required>
          <input type="text" id="signup-phone" placeholder="Phone Number" required>
          <button type="submit">Sign Up</button>
        </form>
        <p class="switch-form">Already have an account? <a href="#" id="show-login">Log In</a></p>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div class="confirm-modal" id="confirm-modal">
    <div class="confirm-modal-content">
      <h2>Log Out</h2>
      <p>Are you sure you want to log out?</p>
      <div class="confirm-buttons">
        <button class="confirm-btn cancel" id="cancel-logout">Cancel</button>
        <button class="confirm-btn logout" id="confirm-logout">Log Out</button>
      </div>
    </div>
  </div>

  <script>
    // Add initialization guard at the top
    if (!window.headerScriptsLoaded) {
      window.headerScriptsLoaded = true;

      document.addEventListener('DOMContentLoaded', function() {
        // First, verify all elements exist
        const elements = {
          cartButton: document.getElementById('cart-button'),
          closeCart: document.getElementById('close-cart'),
          cartSidebar: document.getElementById('cart-sidebar'),
          overlay: document.getElementById('overlay'),
          cartItemsContainer: document.getElementById('cart-items'),
          cartCount: document.getElementById('cart-count'),
          cartTotal: document.getElementById('cart-total'),
          checkoutBtn: document.getElementById('checkout-btn'),
          loginButton: document.getElementById('login-button'),
          loginModal: document.getElementById('login-modal'),
          closeLogin: document.getElementById('close-login'),
          showSignup: document.getElementById('show-signup'),
          showLogin: document.getElementById('show-login'),
          loginForm: document.getElementById('login-form'),
          signupForm: document.getElementById('signup-form'),
          userLoginForm: document.getElementById('user-login'),
          userSignupForm: document.getElementById('user-signup'),
          header: document.getElementById('main-header')
        };

        // Initialize cart from localStorage or create empty cart
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        console.log('Cart initialized:', cart);

        // Update cart count on page load
        updateCartCount();

        // Initialize cart functionality if elements exist
        if (elements.cartButton && elements.cartSidebar && elements.overlay) {
          elements.cartButton.addEventListener('click', toggleCart);
          elements.closeCart.addEventListener('click', toggleCart);
          elements.overlay.addEventListener('click', toggleCart);
        }

        // Initialize login functionality if elements exist
        if (elements.loginButton && elements.loginModal && elements.overlay) {
          elements.loginButton.addEventListener('click', function() {
            console.log('Login button clicked');
            toggleLoginModal();
          });

          elements.closeLogin.addEventListener('click', toggleLoginModal);
        }

        // Form switching functionality
        if (elements.showSignup && elements.showLogin) {
          elements.showSignup.addEventListener('click', function(e) {
            e.preventDefault();
            elements.loginForm.style.display = 'none';
            elements.signupForm.style.display = 'block';
          });

          elements.showLogin.addEventListener('click', function(e) {
            e.preventDefault();
            elements.signupForm.style.display = 'none';
            elements.loginForm.style.display = 'block';
          });
        }

        // Form submissions
        if (elements.userLoginForm) {
          elements.userLoginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Login successful!');
            toggleLoginModal();
          });
        }

        if (elements.userSignupForm) {
          elements.userSignupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Account created successfully!');
            elements.signupForm.style.display = 'none';
            elements.loginForm.style.display = 'block';
          });
        }

        // Checkout button functionality
        if (elements.checkoutBtn) {
          elements.checkoutBtn.addEventListener('click', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if cart is empty
            if (cart.length === 0) {
              alert('Your cart is empty. Please add items before checking out.');
              return;
            }

            // Check if user is logged in
            checkSessionStatus().then(() => {
              const userGreeting = document.querySelector('.user-greeting');

              if (!userGreeting) {
                // User not logged in, show login modal
                toggleLoginModal();

                // Add event listener to redirect to checkout after successful login
                document.getElementById('user-login').addEventListener('submit', function(e) {
                  e.preventDefault();

                  // Simulate login success
                  setTimeout(() => {
                    toggleLoginModal();
                    redirectToCheckout();
                  }, 1000);
                });
              } else {
                // User is logged in, redirect to checkout page
                redirectToCheckout();
              }
            });
          });
        }

        // Function to redirect to checkout page
        function redirectToCheckout() {
          const cart = JSON.parse(localStorage.getItem('cart')) || [];

          // Create a form dynamically to submit the cart data
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = 'checkout.php';

          // Add cart data as hidden input
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'cart_data';
          input.value = JSON.stringify(cart);
          form.appendChild(input);

          // Submit the form
          document.body.appendChild(form);
          form.submit();
        }

        // Login form submission
        document.getElementById('user-login').addEventListener('submit', async function(e) {
          e.preventDefault();

          const username = document.getElementById('login-username').value;
          const password = document.getElementById('login-pass').value;

          const formData = new FormData();
          formData.append('username', username);
          formData.append('password', password);

          const res = await fetch('login.php', {
            method: 'POST',
            body: formData
          });

          const result = await res.text();

          if (result.includes('|')) {
            const [message, redirectUrl] = result.split('|');
            alert(message);
            window.location.href = redirectUrl.trim();
          } else {
            alert(result);
          }
        });

        async function checkSessionStatus() {
          try {
            const res = await fetch('session_status.php');
            const data = await res.json();
            if(data.loggedInAdmin){
              
            } else if (data.loggedIn) {
              // Get the icons container
              const iconsContainer = document.querySelector('.icons');

              // Create new logged in UI
              const userGreeting = document.createElement('div');
              userGreeting.className = 'user-greeting';

              const greetingText = document.createElement('span');
              greetingText.className = 'greeting-text';
              greetingText.textContent = `Hi, ${data.username}`;

              // Add click handler for the username
              greetingText.addEventListener('click', () => {
                window.location.href = 'user-profile.php';
              });

              const logoutBtn = document.createElement('button');
              logoutBtn.className = 'logout-btn';
              logoutBtn.textContent = 'Logout';
              logoutBtn.id = 'logout-button';

              // Add elements to greeting container
              userGreeting.appendChild(greetingText);
              userGreeting.appendChild(logoutBtn);

              // Replace the login icon with the greeting
              const loginIcon = document.querySelector('.fa-user');
              if (loginIcon) {
                loginIcon.replaceWith(userGreeting);
              }

              // Update icons container
              if (iconsContainer) {
                iconsContainer.classList.add('logged-in-icons');

                // Move cart icon to be first element
                const cartIcon = iconsContainer.querySelector('.cart-icon');
                if (cartIcon) {
                  iconsContainer.insertBefore(cartIcon, iconsContainer.firstChild);
                }
              }

              // Add logout event listener with confirmation
              logoutBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                showLogoutConfirmation();
              });
            }
          } catch (err) {
            console.error('Failed to check session status', err);
          }
        }

        function showLogoutConfirmation() {
          const confirmModal = document.getElementById('confirm-modal');
          const cancelBtn = document.getElementById('cancel-logout');
          const confirmBtn = document.getElementById('confirm-logout');
          const overlay = document.getElementById('overlay');

          confirmModal.style.display = 'flex';
          overlay.classList.add('active');

          // Remove previous event listeners to avoid duplicates
          cancelBtn.replaceWith(cancelBtn.cloneNode(true));
          confirmBtn.replaceWith(confirmBtn.cloneNode(true));

          // Get fresh references after cloning
          const newCancelBtn = document.getElementById('cancel-logout');
          const newConfirmBtn = document.getElementById('confirm-logout');

          newCancelBtn.addEventListener('click', () => {
            confirmModal.style.display = 'none';
            overlay.classList.remove('active');
          });

          newConfirmBtn.addEventListener('click', async () => {
            try {
              // Show loading state on logout button
              newConfirmBtn.textContent = 'Logging out...';
              newConfirmBtn.disabled = true;

              // Perform logout
              const logoutResponse = await fetch('logout.php');
              localStorage.clear(); //clears local storage for cart
              if (logoutResponse.ok) {
                // Close confirmation modal
                confirmModal.style.display = 'none';
                overlay.classList.remove('active');

                // Reset login form
                document.getElementById('login-username').value = '';
                document.getElementById('login-pass').value = '';

                window.location.href = 'home.php';
              } else {
                throw new Error('Logout failed');
              }
            } catch (error) {
              console.error('Logout error:', error);
              alert('An error occurred during logout. Please try again.');
              // Reset logout button state
              newConfirmBtn.textContent = 'Log Out';
              newConfirmBtn.disabled = false;
            }
          });
        }

        checkSessionStatus();

        // Cart functions
        function toggleCart() {
          elements.cartSidebar.classList.toggle('active');
          elements.overlay.classList.toggle('active');

          if (elements.cartSidebar.classList.contains('active')) {
            renderCartItems();
          }
        }

        function toggleLoginModal() {
          const loginModal = document.getElementById('login-modal');
          const overlay = document.getElementById('overlay');

          // Only toggle if not already in the desired state
          if (loginModal.style.display !== 'flex') {
            loginModal.style.display = 'flex';
            overlay.classList.add('active');

            // Ensure login form is shown (not signup)
            document.getElementById('signup-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
          } else {
            loginModal.style.display = 'none';
            overlay.classList.remove('active');
          }
        }

        function renderCartItems() {
          const cart = JSON.parse(localStorage.getItem('cart')) || [];
          const cartItemsContainer = document.getElementById('cart-items');
          const cartTotal = document.getElementById('cart-total');

          if (!cartItemsContainer) return;

          if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
            if (cartTotal) cartTotal.textContent = 'Total: ₱0.00';
            return;
          }

          let itemsHTML = '';
          let total = 0;

          cart.forEach((item, index) => {
            total += item.price * item.quantity;

            itemsHTML += `
              <div class="cart-item" data-id="${item.id}">
                <img src="dashboard/uploads/${item.image}" alt="${item.name}">
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
          if (cartTotal) cartTotal.textContent = `Total: ₱${total.toFixed(2)}`;

          // Reattach event listeners
          document.querySelectorAll('.minus').forEach(btn => {
            btn.addEventListener('click', function() {
              const index = parseInt(this.getAttribute('data-index'));
              updateQuantity(index, -1);
            });
          });

          document.querySelectorAll('.plus').forEach(btn => {
            btn.addEventListener('click', function() {
              const index = parseInt(this.getAttribute('data-index'));
              updateQuantity(index, 1);
            });
          });

          document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
              const index = parseInt(this.getAttribute('data-index'));
              removeItem(index);
            });
          });
        }

        function updateQuantity(index, change) {
          // Always get fresh cart data from localStorage
          let cart = JSON.parse(localStorage.getItem('cart')) || [];

          // Verify index is valid
          if (index >= 0 && index < cart.length) {
            cart[index].quantity += change;

            // Ensure quantity doesn't go below 1
            if (cart[index].quantity < 1) {
              cart[index].quantity = 1;
            }

            // Save updated cart
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update UI
            renderCartItems();
            updateCartCount();
          } else {
            console.error('Invalid index for quantity update');
          }
        }

        function removeItem(index) {
          // Always get fresh cart data from localStorage
          let cart = JSON.parse(localStorage.getItem('cart')) || [];

          // Verify index is valid
          if (index >= 0 && index < cart.length) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));

            // Update UI
            renderCartItems();
            updateCartCount();
          } else {
            console.error('Invalid index for cart item removal');
          }
        }

        function updateCartCount() {
          const cart = JSON.parse(localStorage.getItem('cart')) || [];
          const count = cart.reduce((total, item) => total + item.quantity, 0);
          document.getElementById('cart-count').textContent = count;
        }

        // Global function to add items to cart
        window.addToCart = function(product) {
          console.log('Adding product to cart:', product);

          // Get current cart or initialize empty array
          let cart = JSON.parse(localStorage.getItem('cart')) || [];

          // Check if item exists in cart
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

          // Save updated cart
          localStorage.setItem('cart', JSON.stringify(cart));

          // Update UI immediately
          updateCartCount();

          // If cart sidebar is open, update its contents
          if (document.getElementById('cart-sidebar').classList.contains('active')) {
            renderCartItems();
          }

          // Show visual feedback
          const cartIcon = document.getElementById('cart-button');
          if (cartIcon) {
            cartIcon.classList.add('pulse');
            setTimeout(() => cartIcon.classList.remove('pulse'), 500);
          }

          console.log('Cart updated:', cart);
        };

        // Add this event listener to watch for cart changes from other tabs/windows
        window.addEventListener('storage', function(event) {
          if (event.key === 'cart') {
            updateCartCount();
            if (document.getElementById('cart-sidebar').classList.contains('active')) {
              renderCartItems();
            }
          }
        });

        // Header scroll behavior
        if (elements.header) {
          let lastScrollPosition = 0;
          const scrollThreshold = 100;

          window.addEventListener('scroll', function() {
            const currentScrollPosition = window.pageYOffset || document.documentElement.scrollTop;

            if (currentScrollPosition <= 0) {
              elements.header.classList.remove('hidden');
              return;
            }

            if (currentScrollPosition > lastScrollPosition && currentScrollPosition > scrollThreshold) {
              elements.header.classList.add('hidden');
            } else if (currentScrollPosition < lastScrollPosition) {
              elements.header.classList.remove('hidden');
            }

            lastScrollPosition = currentScrollPosition;
          });
        }

        console.log('Header scripts initialized successfully');
      });
    }
  </script>
</body>

</html>
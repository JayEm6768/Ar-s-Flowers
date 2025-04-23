<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ARS Flowershop Davao</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *, *::before, *::after {
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
      height: 110px; /* Fixed height */
    }

    .header.hidden {
       transform: translateY(-100%);
    }

    /* Logo Styles */
    .logo img {
      height: 80px;
      width: auto;
      max-width: none; /* Prevent responsive scaling */
      transition: transform 0.3s;
      cursor: pointer;
      display: block; /* Remove extra space under image */
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
      font-size: 24px; /* Fixed size in px */
      color: #122349;
      cursor: pointer;
      transition: all 0.3s;
      width: 24px; /* Fixed width */
      height: 24px; /* Fixed height */
      text-align: center;
      line-height: 24px; /* Center icon vertically */
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
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
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

    /* Responsive Adjustments - More Precise Breakpoints */
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
        left: 55%; /* Adjust positioning for small screens */
      }
      
      .nav-links a {
        font-size: 14px;
      }
      
      .icons {
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
          <input type="text" id="username" placeholder="Username" required>
          <input type="password" id="pass" placeholder="Password" required>
          <button type="submit">Log In</button>
        </form>
        <p class="switch-form">Don't have an account? <a href="#" id="show-signup">Create one</a></p>
      </div>
      
      <div class="login-form-container" id="signup-form" style="display: none;">
        <h2>Join Us! 🌺</h2>
        <p>Create an account to start shopping</p>
        <form id="user-signup">
          <input type="text" id="name" placeholder="Full Name" required>
          <input type="text" id="username" placeholder="Username" required>
          <input type="password" id="pass" placeholder="Password" required>
          <input type="text" id="email" placeholder="Email Address" required>
          <input type="text" id="phone" placeholder="Phone Number" required>
          <button type="submit">Sign Up</button>
        </form>
        <p class="switch-form">Already have an account? <a href="#" id="show-login">Log In</a></p>
      </div>
    </div>
  </div>

  <!-- Keep your existing JavaScript -->
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

        // Debugging - log which elements are missing
        Object.entries(elements).forEach(([name, element]) => {
            if (!element) console.error(`Element not found: ${name}`);
        });

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

        // Checkout button
        if (elements.checkoutBtn) {
            elements.checkoutBtn.addEventListener('click', function() {
                if (cart.length > 0) {
                    window.location.href = 'checkout.html';
                }
            });
        }

        // Close modals when clicking outside
        if (elements.overlay) {
            window.addEventListener('click', function(e) {
                if (e.target === elements.overlay) {
                    if (elements.cartSidebar && elements.cartSidebar.classList.contains('active')) {
                        toggleCart();
                    }
                    if (elements.loginModal && elements.loginModal.style.display === 'flex') {
                        toggleLoginModal();
                    }
                }
            });
        }

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
                } 
                else if (currentScrollPosition < lastScrollPosition) {
                    elements.header.classList.remove('hidden');
                }
                
                lastScrollPosition = currentScrollPosition;
            });
        }

        // Cart functions
        function toggleCart() {
            elements.cartSidebar.classList.toggle('active');
            elements.overlay.classList.toggle('active');
            
            if (elements.cartSidebar.classList.contains('active')) {
                renderCartItems();
            }
        }

        function toggleLoginModal() {
            elements.loginModal.style.display = elements.loginModal.style.display === 'flex' ? 'none' : 'flex';
            elements.overlay.classList.toggle('active');
            
            if (elements.loginModal.style.display === 'flex') {
                elements.signupForm.style.display = 'none';
                elements.loginForm.style.display = 'block';
            }
        }

        function renderCartItems() {
            if (cart.length === 0) {
                elements.cartItemsContainer.innerHTML = '<div class="empty-cart">Your cart is empty</div>';
                elements.cartTotal.textContent = 'Total: ₱0.00';
                elements.checkoutBtn.disabled = true;
                return;
            }
            
            elements.checkoutBtn.disabled = false;
            
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
            
            elements.cartItemsContainer.innerHTML = itemsHTML;
            elements.cartTotal.textContent = `Total: ₱${total.toFixed(2)}`;
            
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
            elements.cartCount.textContent = count;
        }

        // Global function to add items to cart
        window.addToCart = function(product) {
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
            
            if (elements.cartSidebar.classList.contains('active')) {
                renderCartItems();
            }
        };

        console.log('Header scripts initialized successfully');
    });
}
</script>
</body>
</html>
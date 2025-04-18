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
      <!-- This is the profiles icon-->
      <i class="fa-regular fa-user" onclick="window.location.href='account.html'"></i>

      <!-- This is the cart icon-->
      <div class="cart-icon">
        <i class="fa-solid fa-cart-shopping" id="cart-button"></i>
        <span id="cart-count">0</span>
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
      
      // Initialize cart from localStorage or create empty cart
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      
      // Update cart count on page load
      updateCartCount();
      
      // Toggle cart sidebar
      cartButton.addEventListener('click', toggleCart);
      closeCart.addEventListener('click', toggleCart);
      overlay.addEventListener('click', toggleCart);
      
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
                <div class="item-price">₱${item.price.toFixed(2)}</div>
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
      
      // Database-ready functions (to be implemented when you have a backend)
      function saveCartToDatabase() {
        // This would be replaced with actual API calls to your backend
        console.log('Cart would be saved to database here');
        /*
        fetch('/api/cart', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(cart)
        })
        .then(response => response.json())
        .then(data => {
          console.log('Cart saved:', data);
        })
        .catch(error => {
          console.error('Error saving cart:', error);
        });
        */
      }
      
      function loadCartFromDatabase() {
        // This would be replaced with actual API calls to your backend
        console.log('Cart would be loaded from database here');
        /*
        fetch('/api/cart')
          .then(response => response.json())
          .then(data => {
            cart = data;
            updateCartCount();
            if (cartSidebar.classList.contains('active')) {
              renderCartItems();
            }
          })
          .catch(error => {
            console.error('Error loading cart:', error);
          });
        */
      }
      
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
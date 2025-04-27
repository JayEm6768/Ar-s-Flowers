<?php 
include 'footHead/header.php'; 
if (isset($_SESSION['user'])) {
    echo "<p>Welcome, " . htmlspecialchars($_SESSION['user']) . "! Your ID is " . $_SESSION['user_id'] . ".</p>"; //temp, for debugging
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARS Flowershop - Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #FFF9F9;
            color: #122349;
            line-height: 1.6;
            margin-top: 120px; /* Offset for fixed header */
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
        
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .breadcrumb a {
            color: #666;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .breadcrumb a:hover {
            color: #b10e73;
            text-decoration: underline;
        }
        
        
        /* Shop Layout */
        .shop-container {
            display: flex;
            gap: 30px;
        }
        
        /* Sidebar Filters */
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #122349;
            font-weight: bold;
            border-bottom: 2px solid #ffb6c1;
            padding-bottom: 5px;
        }
        
        .sidebar-links {
            list-style: none;
        }
        
        .sidebar-links li {
            margin-bottom: 10px;
        }
        
        .sidebar-links a {
            color: #666;
            text-decoration: none;
            transition: all 0.3s;
            display: block;
            padding: 5px 0;
        }
        
        .sidebar-links a:hover {
            color: #b10e73;
            transform: translateX(5px);
        }
        
        /* Filter Groups */
        .filter-group {
            margin-bottom: 20px;
        }
        
        .filter-title {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #122349;
        }
        
        .filter-option {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .filter-option input[type="checkbox"] {
            margin-right: 10px;
            accent-color: #b10e73;
        }
        
        /* Products Grid */
        /* Products Grid */
        .products-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .product-card {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(177, 14, 115, 0.15);
        }

        .product-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-bottom: 3px solid #ffb6c1;
            background-size: cover;
            background-position: center;
        }

        .product-info {
            padding: 20px;
            text-align: center;
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #122349;
            font-weight: 600;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: bold;
            color: #b10e73;
            margin-bottom: 15px;
        }
        
        /* Updated Add to Cart Button */
        .add-to-cart {
            display: inline-block;
            width: 100%;
            padding: 12px;
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
        
        .add-to-cart:hover {
            background: linear-gradient(135deg, #850000, #b10e73);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
        }
        
        /* Loading States */
        .loading, .no-products {
            text-align: center;
            padding: 40px;
            font-size: 1.1rem;
            color: #666;
            grid-column: 1 / -1;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .shop-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .page-title {
                font-size: 2rem;
            }
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal.show {
            opacity: 1;
        }
        
        .modal-content {
            background-color: #FFF9F9;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 80%;
            max-width: 900px;
            position: relative;
            transform: translateY(-50px);
            transition: transform 0.3s ease;
        }
        
        .modal.show .modal-content {
            transform: translateY(0);
        }
        
        .close-modal {
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 35px;
            color: #b10e73;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .close-modal:hover {
            color: #850000;
        }
        
        .modal-body {
            display: flex;
            gap: 30px;
        }
        
        .modal-body img {
            width: 50%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .modal-info {
            width: 50%;
        }
        
        .product-description {
            margin: 20px 0;
            line-height: 1.6;
            color: #555;
        }
        
        .product-details {
            margin: 25px 0;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .detail-label {
            font-weight: bold;
            width: 80px;
            color: #122349;
        }
        
        .detail-value {
            color: #b10e73;
        }
        
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 10% auto;
                padding: 20px;
            }
            
            .modal-body {
                flex-direction: column;
            }
            
            .modal-body img,
            .modal-info {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="home.php">Home</a> > <span>All Products</span>
        </div>
        
        <h1 class="page-title">Our Floral Collection</h1>
        
        <div class="shop-container">
            <!-- Sidebar Filters -->
            <div class="sidebar">
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Browse by</h3>
                    <ul class="sidebar-links">
                        <li><a href="#" class="category-filter" data-category="all">All Products</a></li>
                        <li><a href="#" class="category-filter" data-category="boxquets">Boxquets</a></li>
                        <li><a href="#" class="category-filter" data-category="seasonal">Seasonal Arrangements</a></li>
                        <li><a href="#" class="category-filter" data-category="stems">Single Stems</a></li>
                    </ul>
                </div>
                
                <div class="sidebar-section">
                    <h3 class="sidebar-title">Filter by</h3>
                    
                    <div class="filter-group">
                        <span class="filter-title">Price</span>
                        <div class="filter-option">
                            <input type="checkbox" id="price1" class="price-filter" data-min="0" data-max="50">
                            <label for="price1">Under ₱50</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="price2" class="price-filter" data-min="50" data-max="100">
                            <label for="price2">₱50 - ₱100</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="price3" class="price-filter" data-min="100" data-max="9999">
                            <label for="price3">Over ₱100</label>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <span class="filter-title">Color</span>
                        <div class="filter-option">
                            <input type="checkbox" id="color1" class="color-filter" value="red">
                            <label for="color1">Red</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="color2" class="color-filter" value="white">
                            <label for="color2">White</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="color3" class="color-filter" value="yellow">
                            <label for="color3">Yellow</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="color4" class="color-filter" value="mixed">
                            <label for="color4">Mixed</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="products-grid" id="products-container">
                <div class="loading">Loading products...</div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Product Image">
                <div class="modal-info">
                    <h2 id="modalTitle"></h2>
                    <div id="modalDescription" class="product-description"></div>
                    <div class="product-details">
                        <div class="detail-item">
                            <span class="detail-label">Price:</span>
                            <span id="modalPrice" class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Color:</span>
                            <span id="modalColor" class="detail-value"></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Size:</span>
                            <span id="modalSize" class="detail-value"></span>
                        </div>
                    </div>
                    <button id="modalAddToCart" class="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const modal = document.getElementById('productModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalDescription = document.getElementById('modalDescription');
            const modalPrice = document.getElementById('modalPrice');
            const modalColor = document.getElementById('modalColor');
            const modalSize = document.getElementById('modalSize');
            const modalAddToCart = document.getElementById('modalAddToCart');
            const closeModal = document.querySelector('.close-modal');

            // Function to open modal with product details
            function openProductModal(product) {
                modalImage.src = product.image_url;
                modalImage.alt = product.name;
                modalTitle.textContent = product.name;
                modalDescription.textContent = product.description || 'No description available';
                modalPrice.textContent = `₱${Number(product.price).toFixed(2)}`;
                modalColor.textContent = product.color;
                modalSize.textContent = product.size;
                
                // Update add to cart button
                modalAddToCart.setAttribute('data-product-id', product.flower_id);
                
                // Show modal
                modal.style.display = 'block';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }

            // Close modal when clicking X
            closeModal.addEventListener('click', () => {
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            });

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                    setTimeout(() => {
                        modal.style.display = 'none';
                    }, 300);
                }
            });

            // Add to cart from modal
            modalAddToCart.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                if (!isNaN(productId)) {
                    addToCart(productId);
                }
            });

            const productsContainer = document.getElementById('products-container');
            let allProducts = [];
            let activeFilters = {
                category: 'all',
                price: [],
                colors: [],
                sizes: []
            };
            
            // Fetch products from database
            function fetchProducts() {
                fetch('getProducts.php')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(products => {
                        console.log('Fetched products:', products);
                        allProducts = products;
                        renderProducts(products);
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        productsContainer.innerHTML = '<div class="no-products">Error loading products. Please try again later.</div>';
                    });
            }
            
            // Render products based on current filters
            function renderProducts(products) {
                if (products.length === 0) {
                    productsContainer.innerHTML = '<div class="no-products">No products found matching your criteria.</div>';
                    return;
                }
                
                productsContainer.innerHTML = '';
                
                products.forEach(product => {
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card';
                    productCard.innerHTML = `
                        <div class="product-image" style="background-image: url('${product.image_url}')" data-product-id="${product.flower_id}"></div>
                        <div class="product-info">
                            <h3 class="product-title">${product.name}</h3>
                            <div class="product-price">₱${Number(product.price).toFixed(2)}</div>
                            <button class="add-to-cart" data-product-id="${product.flower_id}">Add to Cart</button>
                        </div>
                    `;
                    
                    productsContainer.appendChild(productCard);
                    
                    // Add click event to product image
                    const productImage = productCard.querySelector('.product-image');
                    productImage.addEventListener('click', () => {
                        openProductModal(product);
                    });
                    
                    // Add event listener to Add to Cart button
                    productCard.querySelector('.add-to-cart').addEventListener('click', function(e) {
                        e.stopPropagation(); // Prevent triggering the image click
                        const productId = this.getAttribute('data-product-id');
                        if (!isNaN(productId)) {
                            addToCart(productId);
                        }
                    });
                });
            }
            
            // Filter products based on active filters
            function filterProducts() {
                let filteredProducts = allProducts;
                
                // Category filter
                if (activeFilters.category !== 'all') {
                    filteredProducts = filteredProducts.filter(
                        product => product.category === activeFilters.category
                    );
                }
                
                // Price filter
                if (activeFilters.price.length > 0) {
                    filteredProducts = filteredProducts.filter(product => {
                        return activeFilters.price.some(range => {
                            return product.price >= range.min && product.price <= range.max;
                        });
                    });
                }
                
                // Color filter
                if (activeFilters.colors.length > 0) {
                    filteredProducts = filteredProducts.filter(
                        product => activeFilters.colors.includes(product.color)
                    );
                }
                
                // Size filter
                if (activeFilters.sizes.length > 0) {
                    filteredProducts = filteredProducts.filter(
                        product => activeFilters.sizes.includes(product.size)
                    );
                }
                
                renderProducts(filteredProducts);
            }

            function updateCartDisplay() {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                    cartCountElement.textContent = totalItems;
                }
                
                const cartItemsContainer = document.getElementById('cart-items-container');
                if (cartItemsContainer) {
                    cartItemsContainer.innerHTML = '';
                    
                    if (cart.length === 0) {
                        cartItemsContainer.innerHTML = '<p>Your cart is empty</p>';
                    } else {
                        cart.forEach(item => {
                            const cartItemElement = document.createElement('div');
                            cartItemElement.className = 'cart-item';
                            cartItemElement.innerHTML = `
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <h4>${item.name}</h4>
                                    <p>₱${item.price.toFixed(2)} × ${item.quantity}</p>
                                </div>
                            `;
                            cartItemsContainer.appendChild(cartItemElement);
                        });
                    }
                }
            }

            function addToCart(productId) {
                console.log('Sending product ID:', productId);
                fetch('addToCart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        product_id: productId,
                        quantity: 1 
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.product) {
                        let cart = JSON.parse(localStorage.getItem('cart')) || [];
                        const existingItem = cart.find(item => item.id === data.product.flower_id);

                        if (existingItem) {
                            existingItem.quantity += 1;
                        } else {
                            cart.push({
                                id: data.product.flower_id,
                                name: data.product.name,
                                price: data.product.price,
                                image: data.product.image_url,
                                quantity: 1
                            });
                        }
                        
                        localStorage.setItem('cart', JSON.stringify(cart));
                        updateCartDisplay();
                        
                        alert('Product added to cart!');
                    } else {
                        alert('Failed to add product to cart: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding to cart');
                });
            }

            // Initialize cart display
            updateCartDisplay();
            
            // Event listeners for filters
            document.querySelectorAll('.category-filter').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    activeFilters.category = this.getAttribute('data-category');
                    filterProducts();
                });
            });
            
            document.querySelectorAll('.price-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const range = {
                        min: parseFloat(this.getAttribute('data-min')),
                        max: parseFloat(this.getAttribute('data-max'))
                    };
                    
                    if (this.checked) {
                        activeFilters.price.push(range);
                    } else {
                        activeFilters.price = activeFilters.price.filter(
                            r => r.min !== range.min || r.max !== range.max
                        );
                    }
                    filterProducts();
                });
            });
            
            document.querySelectorAll('.color-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const color = this.value;
                    
                    if (this.checked) {
                        activeFilters.colors.push(color);
                    } else {
                        activeFilters.colors = activeFilters.colors.filter(c => c !== color);
                    }
                    filterProducts();
                });
            });
            
            // Initial fetch
            fetchProducts();
        });
    </script>
    <?php include 'footHead/footer.php'; ?>
</body>
</html>
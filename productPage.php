<?php 
session_start();
include 'header.php'; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floral Delights - Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #FEFAF0;
            color: #122349;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }
        
        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
            color: #800000;
        }
        
        .page-title {
            font-size: 28px;
            margin-bottom: 30px;
            color: #122349;
            text-align: center;
        }
        
        .shop-container {
            display: flex;
            gap: 30px;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .sidebar-section {
            margin-bottom: 25px;
        }
        
        .sidebar-title {
            font-size: 18px;
            margin-bottom: 15px;
            color: #122349;
            font-weight: bold;
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
            transition: color 0.3s;
        }
        
        .sidebar-links a:hover {
            color: #800000;
        }
        
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
        }
        
        /* Products Grid Styles */
        .products-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 200px;
            background-color: #f5f5f5;
            background-size: cover;
            background-position: center;
        }
        
        .product-info {
            padding: 15px;
            text-align: center;
        }
        
        .product-title {
            font-size: 18px;
            margin-bottom: 10px;
            color: #122349;
        }
        
        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #800000;
            margin-bottom: 15px;
        }
        
        .add-to-cart {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #800000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: opacity 0.3s;
        }
        
        .add-to-cart:hover {
            opacity: 0.9;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
        }
        
        .no-products {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #666;
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="breadcrumb">
            <a href="#">Home</a> > <a href="#">All Products</a>
        </div>
        
        <h1 class="page-title">All Products</h1>
        
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
                            <label for="price1">Under P50</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="price2" class="price-filter" data-min="50" data-max="100">
                            <label for="price2">P50 - P100</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="price3" class="price-filter" data-min="100" data-max="9999">
                            <label for="price3">Over P100</label>
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
                    
                    <div class="filter-group">
                        <span class="filter-title">Size</span>
                        <div class="filter-option">
                            <input type="checkbox" id="size1" class="size-filter" value="small">
                            <label for="size1">Small</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size2" class="size-filter" value="medium">
                            <label for="size2">Medium</label>
                        </div>
                        <div class="filter-option">
                            <input type="checkbox" id="size3" class="size-filter" value="large">
                            <label for="size3">Large</label>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        console.log('Fetched products:', products); //for debugging
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
                    console.log(product); //for debugging
                    const productCard = document.createElement('div');
                    productCard.className = 'product-card';
                    productCard.innerHTML = `
                        <div class="product-image" style="background-image: url('${product.image_url}')"></div>
                        <div class="product-info">
                            <h3 class="product-title">${product.name}</h3>
                            <div class="product-price">P${Number(product.price).toFixed(2)}</div>
                            <button class="add-to-cart" data-product-id="${product.flower_id}" >Add to Cart</button>
                        </div>
                    `; //changed
                    
                    productsContainer.appendChild(productCard);
                });
                
                // Add event listeners to Add to Cart buttons
                document.querySelectorAll('.add-to-cart').forEach(button => {
                    button.addEventListener('click', function() {
                        const productId = this.getAttribute('data-product-id');
                        if (!isNaN(productId)) {
                            addToCart(productId); //changed
                        } else {
                            console.error('Invalid product ID');
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
            
            // Add to cart function //changed func
            function addToCart(productId) {
                console.log('Sending product ID:', productId); //debugging
                fetch('addToCart.php', { //changed
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
                        // Get current cart from localStorage or initialize empty
                        let cart = JSON.parse(localStorage.getItem('cart')) || [];

                        // Check if item already in cart
                        const existingItem = cart.find(item => item.id === data.product.flower_id); //changed data.product.flower_id

                        if (existingItem) {
                            existingItem.quantity += 1;
                        } else {
                            cart.push({
                            id: data.product.flower_id, //changed
                            name: data.product.name,
                            price: data.product.price,
                            image: data.product.image,
                            quantity: 1
                            });
                        }
                        // Save updated cart to localStorage
                        localStorage.setItem('cart', JSON.stringify(cart));

                        alert('Product added to cart!');
                        console.log('Current cart:', JSON.parse(localStorage.getItem('cart'))); //debugging
                        // Update cart count if needed
                    } else {
                        alert('Failed to add product to cart: ' + (data.message || ''));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding to cart');
                });
            }
            
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
            
            document.querySelectorAll('.size-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const size = this.value;
                    
                    if (this.checked) {
                        activeFilters.sizes.push(size);
                    } else {
                        activeFilters.sizes = activeFilters.sizes.filter(s => s !== size);
                    }
                    filterProducts();
                });
            });
            
            // Initial fetch
            fetchProducts();
        });
    </script>
</body>
</html>
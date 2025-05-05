<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Footer</title>
    <style>
        /* ----- Footer Styles ----- */
        footer {
            background-color: rgb(37, 36, 36);
            color: white;
            padding: 60px 0 30px;
            position: relative;
            overflow: hidden;
            font-family: 'Arial', sans-serif;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 0 20px;
            position: relative;
        }

        /* Transparent Square Decoration */
        .footer-square {
            position: absolute;
            width: 1800px;
            height: 430px;
            background-color: transparent;
            border: 4px solid rgba(255, 255, 255, 0.1);
            bottom: 10px;
            right: -300px;
            z-index: 1;
        }

        /* Footer Columns */
        .footer-col {
            flex: 1;
            min-width: 250px;
            margin-bottom: 30px;
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }

        .footer-col h3 {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            display: inline-block;
        }

        .footer-col h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 50px;
            height: 2px;
            background: #ffffff;
        }

        .footer-col p,
        .footer-col a {
            color: #b3b3b3;
            line-height: 1.6;
            margin-bottom: 10px;
            display: block;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col a:hover {
            color: white;
        }

        /* Address Section */
        .address {
            font-style: normal;
            margin-top: 15px;
        }

        .contact-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .contact-info i {
            margin-right: 10px;
            color: #ffb6c1;
            width: 20px;
            text-align: center;
        }

        /* Footer Logo */
        .footer-logo {
            width: 180px;
            height: auto;
            margin-bottom: 20px;
            -webkit-mask-image: linear-gradient(to bottom,
                    rgba(0, 0, 0, 1) 0%,
                    rgba(0, 0, 0, 1) 80%,
                    rgba(0, 0, 0, 0) 100%);
            mask-image: linear-gradient(to bottom,
                    rgba(0, 0, 0, 1) 0%,
                    rgba(0, 0, 0, 1) 80%,
                    rgba(0, 0, 0, 0) 100%);
        }

        /* Newsletter Form */
        .newsletter-form input {
            padding: 10px 15px;
            width: 100%;
            margin-bottom: 10px;
            border: none;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .newsletter-form button {
            padding: 10px 20px;
            background: linear-gradient(135deg, #b10e73, #ff6b9e);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .newsletter-form button:hover {
            background: linear-gradient(135deg, #850000, #b10e73);
        }

        /* Copyright Section */
        .copyright {
            width: 100%;
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #777;
            font-size: 14px;
        }

        /* Social Media Icons */
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            color: white;
            font-size: 20px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #ffb6c1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .footer-col {
                flex: 100%;
                text-align: center;
            }

            .footer-col h3::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-square {
                display: none;
            }

            .contact-info {
                justify-content: center;
            }

            .social-icons {
                justify-content: center;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Footer Section -->
    <footer>
        <div class="footer-container">
            <!-- Column 1: Logo/About -->
            <div class="footer-col">
                <img src="pictures/arsFlowerHeaderLogo.png" alt="Ar's Flower Shop Logo" class="footer-logo">
                <p>Flowers For All Occasions</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="footer-col">
                <h3>Quick Links</h3>
                <a href="home.php">Home</a>
                <a href="productPage.php">Shop</a>
                <a href="aboutUs.php">About Us</a>
                <a href="events.php">Events</a>
                <a href="#">FAQs</a>
            </div>

            <!-- Column 3: Contact Info -->
            <div class="footer-col">
                <h3>Contact Us</h3>
                <div class="address">
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>Ground Floor, Masamura Building,<br>
                            Km. 5 Buhangin-Cabantian-Indangan Rd.,<br>
                            Brgy. Buhangin, Davao City 8000</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:ars.flower@example.com">ars.flower@example.com</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <a href="tel:+639123456789">+63 912 345 6789</a>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-clock"></i>
                        <p>Open Daily: 8:00 AM - 8:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="footer-col">
                <h3>Newsletter</h3>
                <p>Subscribe to receive updates on special offers and new arrivals.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your Email Address" required>
                    <button type="submit">Subscribe</button>
                </form>
                <p style="font-size: 12px; margin-top: 10px;">We'll never share your email with anyone else.</p>
            </div>

            <!-- Transparent Square Decoration -->
            <div class="footer-square"></div>
        </div>

        <!-- Copyright -->
        <div class="copyright">
            &copy; 2025 Ar's Flower Shop. All Rights Reserved. | Designed with <i class="fas fa-heart" style="color: #ff6b9e;"></i> by ARS Team
        </div>
    </footer>
</body>

</html>
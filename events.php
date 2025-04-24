<?php include 'footHead/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ARS Flowershop - Events</title>
  <style>
    /* Base Styles */
    body {
      background-color: #FFF9F9; /* Soft pink background */
      color: #122349;
      margin-top: 120px;
      font-family: 'Arial', sans-serif;
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

    /* Events Grid */
    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 30px;
      margin: 40px 0;
    }

    .event-card {
      background-color: white;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      position: relative;
    }

    .event-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgba(177, 14, 115, 0.15);
    }

    .event-image {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-bottom: 3px solid #ffb6c1;
    }

    .event-details {
      padding: 25px;
      position: relative;
    }

    .event-title {
      font-size: 1.4rem;
      font-weight: bold;
      margin-bottom: 10px;
      color: #122349;
    }

    .event-date {
      color: #b10e73;
      font-weight: bold;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .event-date i {
      font-size: 1rem;
    }

    .event-description {
      color: #555;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .event-price {
      font-weight: bold;
      color: #850000;
      margin-bottom: 15px;
    }

    .register-btn {
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
    }

    .register-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(177, 14, 115, 0.4);
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

    /* Registration Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      z-index: 2000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 500px;
      position: relative;
      animation: modalFadeIn 0.4s;
    }

    @keyframes modalFadeIn {
      from { opacity: 0; transform: translateY(-50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .close-modal {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 1.5rem;
      cursor: pointer;
      color: #850000;
    }

    .modal-title {
      color: #b10e73;
      text-align: center;
      margin-bottom: 20px;
      font-size: 1.8rem;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: 600;
    }

    .form-group input, 
    .form-group select, 
    .form-group textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
    }

    .form-group textarea {
      min-height: 100px;
      resize: vertical;
    }

    .submit-btn {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #b10e73, #ff6b9e);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .submit-btn:hover {
      background: linear-gradient(135deg, #850000, #b10e73);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .events-grid {
        grid-template-columns: 1fr;
      }
      
      .page-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="page-title">Floral Events & Workshops</h1>
    <p class="page-subtitle">Join us for unforgettable experiences with flowers</p>
    
    <div class="floral-divider"></div>
    
    <div class="events-grid">
      <!-- Workshop Event -->
      <div class="event-card">
        <img src="pictures/flowerWrokShop.jpg" alt="Floral Workshop" class="event-image">
        <div class="event-details">
          <h3 class="event-title">Spring Floral Workshop</h3>
          <div class="event-date"><i class="far fa-calendar-alt"></i> June 15, 2024 | 2:00 PM</div>
          <p class="event-description">
            Learn to create stunning spring arrangements with our expert florists. 
            All materials provided, including seasonal blooms and a take-home vase.
          </p>
          <div class="event-price">Price: ₱1,200 per person</div>
          <button class="register-btn" data-event="workshop">Register Now</button>
        </div>
      </div>

      <!-- Seasonal Event -->
      <div class="event-card">
        <img src="pictures/valen.jpg" alt="Valentine's Day" class="event-image">
        <div class="event-details">
          <h3 class="event-title">Valentine's Bouquet Special</h3>
          <div class="event-date"><i class="far fa-calendar-alt"></i> February 14, 2025 | All Day</div>
          <p class="event-description">
            Pre-order romantic bouquets with 15% discount. Complimentary gift wrapping 
            and handwritten cards for all orders.
          </p>
          <a href="productPage.php" class="register-btn">Shop Valentine's Collection</a>
        </div>
      </div>

      <!-- Wedding Event -->
      <div class="event-card">
        <img src="pictures/wedexpo.png" alt="Wedding Expo" class="event-image">
        <div class="event-details">
          <h3 class="event-title">Bridal Floral Showcase</h3>
          <div class="event-date"><i class="far fa-calendar-alt"></i> March 5, 2025 | 10AM-6PM</div>
          <p class="event-description">
            Explore wedding floral trends with live demonstrations. Exclusive 
            consultation discounts for attendees.
          </p>
          <div class="event-price">Free Admission</div>
          <button class="register-btn" data-event="bridal-showcase">RSVP Now</button>
        </div>
      </div>
    </div>

    <div class="floral-divider"></div>
  </div>

  <!-- Registration Modal -->
  <div class="modal" id="registration-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2 class="modal-title" id="modal-event-title">Register for Event</h2>
      <form id="registration-form">
        <input type="hidden" id="event-type">
        
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" required>
        </div>
        
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" required>
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" required>
        </div>
        
        <div class="form-group" id="guest-field">
          <label for="guests">Number of Guests (including you)</label>
          <select id="guests">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="notes">Special Requests</label>
          <textarea id="notes" placeholder="Allergies, accessibility needs, etc."></textarea>
        </div>
        
        <button type="submit" class="submit-btn">Complete Registration</button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Modal functionality
      const modal = document.getElementById('registration-modal');
      const closeBtn = document.querySelector('.close-modal');
      const registerBtns = document.querySelectorAll('.register-btn[data-event]');
      const eventTitle = document.getElementById('modal-event-title');
      const eventType = document.getElementById('event-type');
      const guestField = document.getElementById('guest-field');
      const form = document.getElementById('registration-form');

      // Open modal with event-specific details
      registerBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const event = this.getAttribute('data-event');
          eventType.value = event;
          
          // Customize modal based on event
          switch(event) {
            case 'workshop':
              eventTitle.textContent = 'Register for Floral Workshop';
              guestField.style.display = 'block';
              break;
            case 'bridal-showcase':
              eventTitle.textContent = 'RSVP for Bridal Showcase';
              guestField.style.display = 'none';
              break;
          }
          
          modal.style.display = 'flex';
          document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
      });

      // Close modal
      closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
      });

      // Close when clicking outside modal
      window.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.style.display = 'none';
          document.body.style.overflow = 'auto';
        }
      });

      // Form submission
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Simulate form processing
        const eventName = eventType.value;
        const userName = document.getElementById('name').value;
        
        // In a real app, you'd send this data to a server (e.g., via fetch)
        alert(`Thank you, ${userName}! Your registration for the ${eventName} has been received. We'll contact you shortly.`);
        
        // Reset and close
        form.reset();
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
      });

      // Ensure cart/login scripts from header.php work
      console.log('Events page loaded');
    });
  </script>
  <?php include 'footHead/footer.php'; ?>
</body>
</html>
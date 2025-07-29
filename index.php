<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airbnb Clone - Find Vacation Rentals</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Circular', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', sans-serif;
        }
        
        body {
            background-color: #fff;
            color: #222;
            line-height: 1.5;
        }
        
        /* Header Styles */
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 80px;
            max-width: 1600px;
            margin: 0 auto;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo img {
            height: 32px;
        }
        
        .logo-text {
            color: #FF385C;
            font-weight: bold;
            font-size: 24px;
            margin-left: 5px;
        }
        
        .nav-links {
            display: flex;
            gap: 20px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #222;
            font-weight: 500;
            padding: 10px;
            border-radius: 20px;
            transition: background-color 0.3s;
        }
        
        .nav-links a:hover {
            background-color: #f7f7f7;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-menu button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 10px;
            border-radius: 20px;
            transition: background-color 0.3s;
        }
        
        .user-menu button:hover {
            background-color: #f7f7f7;
        }
        
        .profile-button {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ddd !important;
            padding: 8px 12px !important;
        }
        
        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1501785888041-af3ef285b470');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero p {
            font-size: 24px;
            margin-bottom: 40px;
            max-width: 800px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        /* Search Form */
        .search-container {
            background-color: white;
            border-radius: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }
        
        .search-form {
            display: flex;
            flex-wrap: wrap;
            padding: 8px;
        }
        
        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 16px 24px;
            border: none;
            background: transparent;
            position: relative;
        }
        
        .search-input:not(:last-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 20%;
            height: 60%;
            width: 1px;
            background-color: #ddd;
        }
        
        .search-input label {
            display: block;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .search-input input, .search-input select {
            width: 100%;
            border: none;
            font-size: 16px;
            outline: none;
        }
        
        .search-button {
            background-color: #FF385C;
            color: white;
            border: none;
            border-radius: 24px;
            padding: 16px 32px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 8px;
            transition: background-color 0.3s;
        }
        
        .search-button:hover {
            background-color: #E31C5F;
        }
        
        /* Featured Properties Section */
        .featured-properties {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 32px;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .property-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
        }
        
        .property-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }
        
        .property-info {
            padding: 20px;
        }
        
        .property-location {
            font-size: 14px;
            color: #717171;
            margin-bottom: 8px;
        }
        
        .property-name {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 8px;
        }
        
        .property-price {
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .property-rating i {
            color: #FF385C;
        }
        
        /* Categories Section */
        .categories {
            background-color: #f7f7f7;
            padding: 80px 20px;
        }
        
        .categories-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        
        .category-card {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
            cursor: pointer;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
        }
        
        .category-icon {
            font-size: 40px;
            margin: 20px 0;
            color: #FF385C;
        }
        
        .category-name {
            padding: 0 20px 20px;
            font-weight: 500;
        }
        
        /* Footer */
        footer {
            background-color: #f7f7f7;
            border-top: 1px solid #ddd;
            padding: 40px 20px;
        }
        
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        
        .footer-column h3 {
            font-size: 16px;
            margin-bottom: 20px;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            text-decoration: none;
            color: #717171;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #FF385C;
        }
        
        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .footer-bottom-links {
            display: flex;
            gap: 20px;
        }
        
        .footer-bottom-links a {
            text-decoration: none;
            color: #222;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            color: #222;
            font-size: 18px;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                padding: 15px 20px;
            }
            
            .nav-links {
                display: none;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 18px;
            }
            
            .search-form {
                flex-direction: column;
            }
            
            .search-input::after {
                display: none;
            }
            
            .search-input {
                border-bottom: 1px solid #ddd;
                padding: 12px;
            }
            
            .search-button {
                width: 100%;
                margin: 8px 0 0;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-bottom-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">
                <i class="fab fa-airbnb" style="color: #FF385C; font-size: 32px;"></i>
                <span class="logo-text">airbnb</span>
            </div>
            
            <div class="nav-links">
                <a href="#">Places to stay</a>
                <a href="#">Experiences</a>
                <a href="#">Online Experiences</a>
            </div>
            
            <div class="user-menu">
                <button>Become a Host</button>
                <button><i class="fas fa-globe"></i></button>
                <button class="profile-button">
                    <i class="fas fa-bars"></i>
                    <i class="fas fa-user-circle" style="font-size: 24px;"></i>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <h1>Find your next getaway</h1>
        <p>Discover unique places to stay and things to do around the world.</p>
    </section>
    
    <!-- Search Form -->
    <div class="search-container">
        <form class="search-form" id="search-form">
            <div class="search-input">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Where are you going?" required>
            </div>
            
            <div class="search-input">
                <label for="check-in">Check in</label>
                <input type="date" id="check-in" name="check_in" required>
            </div>
            
            <div class="search-input">
                <label for="check-out">Check out</label>
                <input type="date" id="check-out" name="check_out" required>
            </div>
            
            <div class="search-input">
                <label for="guests">Guests</label>
                <select id="guests" name="guests" required>
                    <option value="1">1 guest</option>
                    <option value="2">2 guests</option>
                    <option value="3">3 guests</option>
                    <option value="4">4 guests</option>
                    <option value="5">5 guests</option>
                    <option value="6">6+ guests</option>
                </select>
            </div>
            
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
    
    <!-- Featured Properties Section -->
    <section class="featured-properties">
        <h2 class="section-title">Featured Places to Stay</h2>
        
        <div class="property-grid">
            <?php
            // Fetch featured properties from database
            $sql = "SELECT p.*, i.image_url FROM properties p 
                    LEFT JOIN images i ON p.id = i.property_id 
                    WHERE i.is_primary = 1 OR i.is_primary IS NULL
                    GROUP BY p.id
                    ORDER BY p.rating DESC LIMIT 6";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="property-card" onclick="goToProperty(' . $row['id'] . ')">';
                    echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '" class="property-image">';
                    echo '<div class="property-info">';
                    echo '<div class="property-location">' . $row['location'] . '</div>';
                    echo '<div class="property-name">' . $row['name'] . '</div>';
                    echo '<div class="property-price">$' . $row['price_per_night'] . ' night</div>';
                    echo '<div class="property-rating">';
                    echo '<i class="fas fa-star"></i>';
                    echo '<span>' . $row['rating'] . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p style='text-align: center;'>No properties found. Please check back later.</p>";
            }
            ?>
        </div>
    </section>
    
    <!-- Categories Section -->
    <section class="categories">
        <div class="categories-container">
            <h2 class="section-title">Explore by Category</h2>
            
            <div class="category-grid">
                <div class="category-card" onclick="window.location.href='results.php?property_type=Entire+home'">
                    <div class="category-icon"><i class="fas fa-home"></i></div>
                    <div class="category-name">Entire homes</div>
                </div>
                
                <div class="category-card" onclick="window.location.href='results.php?location=beach'">
                    <div class="category-icon"><i class="fas fa-water"></i></div>
                    <div class="category-name">Beachfront</div>
                </div>
                
                <div class="category-card" onclick="window.location.href='results.php?location=mountain'">
                    <div class="category-icon"><i class="fas fa-mountain"></i></div>
                    <div class="category-name">Mountain views</div>
                </div>
                
                <div class="category-card" onclick="window.location.href='results.php?amenities=4'">
                    <div class="category-icon"><i class="fas fa-swimming-pool"></i></div>
                    <div class="category-name">Amazing pools</div>
                </div>
                
                <div class="category-card" onclick="window.location.href='results.php?property_type=Cabin'">
                    <div class="category-icon"><i class="fas fa-campground"></i></div>
                    <div class="category-name">Cabins</div>
                </div>
                
                <div class="category-card" onclick="window.location.href='results.php?location=city'">
                    <div class="category-icon"><i class="fas fa-city"></i></div>
                    <div class="category-name">City centers</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Safety information</a></li>
                    <li><a href="#">Cancellation options</a></li>
                    <li><a href="#">COVID-19 response</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Community</h3>
                <ul class="footer-links">
                    <li><a href="#">Disaster relief housing</a></li>
                    <li><a href="#">Support refugees</a></li>
                    <li><a href="#">Combating discrimination</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>Hosting</h3>
                <ul class="footer-links">
                    <li><a href="#">Try hosting</a></li>
                    <li><a href="#">AirCover for Hosts</a></li>
                    <li><a href="#">Explore hosting resources</a></li>
                    <li><a href="#">Visit our community forum</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h3>About</h3>
                <ul class="footer-links">
                    <li><a href="#">Newsroom</a></li>
                    <li><a href="#">Learn about new features</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Investors</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div>© 2023 Airbnb Clone, Inc.</div>
            
            <div class="footer-bottom-links">
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
                <a href="#">Sitemap</a>
            </div>
            
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>
    
    <script>
        // Set minimum date for check-in to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check-in').min = today;
        
        // Set default date for check-in (tomorrow)
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        if (!document.getElementById('check-in').value) {
            document.getElementById('check-in').value = tomorrow.toISOString().split('T')[0];
        }
        
        // Set minimum date for check-out to check-in date + 1 day
        document.getElementById('check-in').addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            checkInDate.setDate(checkInDate.getDate() + 1);
            const minCheckOutDate = checkInDate.toISOString().split('T')[0];
            document.getElementById('check-out').min = minCheckOutDate;
            
            // If check-out date is before new minimum, update it
            if (document.getElementById('check-out').value < minCheckOutDate) {
                document.getElementById('check-out').value = minCheckOutDate;
            }
        });
        
        // Set default date for check-out (day after tomorrow)
        const dayAfterTomorrow = new Date();
        dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2);
        if (!document.getElementById('check-out').value) {
            document.getElementById('check-out').value = dayAfterTomorrow.toISOString().split('T')[0];
        }
        
        // Handle form submission
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const location = document.getElementById('location').value;
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            const guests = document.getElementById('guests').value;
            
            // Redirect to results page with query parameters
            window.location.href = `results.php?location=${encodeURIComponent(location)}&check_in=${checkIn}&check_out=${checkOut}&guests=${guests}`;
        });
        
        // Function to navigate to property details page
        function goToProperty(id) {
            window.location.href = `property.php?id=${id}`;
        }
        
        // Make category cards clickable
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('click', function() {
                const href = this.getAttribute('onclick').match(/window\.location\.href='([^']+)'/)[1];
                window.location.href = href;
            });
        });
    </script>
</body>
</html>

<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details - Airbnb Clone</title>
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
            text-decoration: none;
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
        
        .search-bar {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 24px;
            padding: 8px 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
            cursor: pointer;
        }
        
        .search-bar:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        .search-bar span {
            margin: 0 8px;
            font-weight: 500;
        }
        
        .search-bar i {
            color: #FF385C;
            background-color: #FF385C;
            color: white;
            padding: 8px;
            border-radius: 50%;
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
        
        /* Main Content */
        .main-container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 24px;
        }
        
        /* Property Header */
        .property-header {
            margin-bottom: 24px;
        }
        
        .property-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .property-subheader {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .property-rating-location {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .property-rating i {
            color: #FF385C;
        }
        
        .property-location {
            text-decoration: underline;
            cursor: pointer;
        }
        
        .property-actions {
            display: flex;
            gap: 16px;
        }
        
        .property-action {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #222;
            text-decoration: underline;
            cursor: pointer;
        }
        
        /* Property Gallery */
        .property-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 200px);
            gap: 8px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 48px;
        }
        
        .gallery-main {
            grid-column: span 2;
            grid-row: span 2;
        }
        
        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: opacity 0.3s;
        }
        
        .gallery-image:hover {
            opacity: 0.9;
        }
        
        .show-all-photos {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Property Details Layout */
        .property-details-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 80px;
            margin-bottom: 48px;
        }
        
        /* Property Info */
        .property-info {
            border-bottom: 1px solid #ddd;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        
        .host-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .host-details h2 {
            font-size: 22px;
            margin-bottom: 8px;
        }
        
        .host-details p {
            color: #717171;
        }
        
        .host-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .property-highlights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin: 24px 0;
        }
        
        .property-highlight {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .highlight-icon {
            font-size: 24px;
        }
        
        .highlight-title {
            font-weight: 500;
        }
        
        .highlight-description {
            color: #717171;
        }
        
        .property-description {
            margin: 24px 0;
            line-height: 1.6;
        }
        
        .read-more {
            font-weight: 500;
            text-decoration: underline;
            cursor: pointer;
        }
        
        /* Amenities Section */
        .amenities {
            border-bottom: 1px solid #ddd;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        
        .section-title {
            font-size: 22px;
            margin-bottom: 24px;
        }
        
        .amenities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .amenity {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .amenity-icon {
            font-size: 20px;
        }
        
        .show-all-amenities {
            margin-top: 24px;
            border: 1px solid #222;
            background-color: white;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            cursor: pointer;
        }
        
        /* Reviews Section */
        .reviews {
            border-bottom: 1px solid #ddd;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        
        .reviews-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        
        .reviews-header i {
            color: #FF385C;
        }
        
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .review {
            margin-bottom: 24px;
        }
        
        .review-header {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
        }
        
        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .reviewer-info h4 {
            margin-bottom: 4px;
        }
        
        .review-date {
            color: #717171;
            font-size: 14px;
        }
        
        .show-all-reviews {
            border: 1px solid #222;
            background-color: white;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            cursor: pointer;
        }
        
        /* Location Section */
        .location {
            margin-bottom: 48px;
        }
        
        .map-container {
            height: 480px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 24px;
        }
        
        .map-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Booking Card */
        .booking-card {
            position: sticky;
            top: 100px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            padding: 24px;
        }
        
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 24px;
        }
        
        .booking-price {
            font-size: 22px;
            font-weight: 600;
        }
        
        .booking-rating {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .booking-rating i {
            color: #FF385C;
        }
        
        .booking-form {
            margin-bottom: 24px;
        }
        
        .booking-dates {
            display: grid;
            grid-template-columns: 1fr 1fr;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        
        .booking-date-input {
            padding: 12px;
            border: none;
            border-radius: 0;
        }
        
        .booking-date-input:first-child {
            border-right: 1px solid #ddd;
        }
        
        .booking-date-input label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .booking-date-input input {
            width: 100%;
            border: none;
            font-size: 14px;
        }
        
        .booking-guests {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px;
        }
        
        .booking-guests label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .booking-guests select {
            width: 100%;
            border: none;
            font-size: 14px;
            background-color: transparent;
        }
        
        .booking-button {
            width: 100%;
            background-color: #FF385C;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 24px;
        }
        
        .booking-button:hover {
            background-color: #E31C5F;
        }
        
        .booking-note {
            text-align: center;
            margin-bottom: 24px;
            color: #717171;
        }
        
        .booking-details {
            margin-bottom: 24px;
        }
        
        .booking-detail {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .booking-detail-text {
            text-decoration: underline;
        }
        
        .booking-total {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            padding-top: 12px;
            border-top: 1px solid #ddd;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .property-gallery {
                grid-template-columns: repeat(2, 1fr);
                grid-template-rows: repeat(3, 200px);
            }
            
            .property-details-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            
            .booking-card {
                position: static;
                margin-top: 24px;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                padding: 15px 20px;
            }
            
            .property-gallery {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(5, 200px);
            }
            
            .gallery-main {
                grid-column: span 1;
                grid-row: span 1;
            }
            
            .reviews-grid {
                grid-template-columns: 1fr;
            }
            
            .amenities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // Get property ID from URL
    $property_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    // Fetch property details
    $sql = "SELECT * FROM properties WHERE id = $property_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 0) {
        // Property not found, redirect to homepage
        header("Location: index.php");
        exit;
    }
    
    $property = $result->fetch_assoc();
    
    // Fetch property images
    $sql_images = "SELECT * FROM images WHERE property_id = $property_id ORDER BY is_primary DESC";
    $result_images = $conn->query($sql_images);
    $images = [];
    while ($row = $result_images->fetch_assoc()) {
        $images[] = $row;
    }
    
    // Fetch property amenities
    $sql_amenities = "SELECT a.name FROM amenities a 
                      JOIN property_amenities pa ON a.id = pa.amenity_id 
                      WHERE pa.property_id = $property_id";
    $result_amenities = $conn->query($sql_amenities);
    $amenities = [];
    while ($row = $result_amenities->fetch_assoc()) {
        $amenities[] = $row['name'];
    }
    
    // Fetch property reviews
    $sql_reviews = "SELECT * FROM reviews WHERE property_id = $property_id ORDER BY created_at DESC LIMIT 4";
    $result_reviews = $conn->query($sql_reviews);
    $reviews = [];
    while ($row = $result_reviews->fetch_assoc()) {
        $reviews[] = $row;
    }
    
    // Count total reviews
    $sql_review_count = "SELECT COUNT(*) as count FROM reviews WHERE property_id = $property_id";
    $result_review_count = $conn->query($sql_review_count);
    $review_count = $result_review_count->fetch_assoc()['count'];
    ?>
    
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="index.php" class="logo">
                <i class="fab fa-airbnb" style="color: #FF385C; font-size: 32px;"></i>
                <span class="logo-text">airbnb</span>
            </a>
            
            <div class="search-bar" onclick="window.location.href='index.php'">
                <span>Anywhere</span>
                <span>|</span>
                <span>Any week</span>
                <span>|</span>
                <span>Add guests</span>
                <i class="fas fa-search"></i>
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
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Property Header -->
        <div class="property-header">
            <h1 class="property-title"><?php echo htmlspecialchars($property['name']); ?></h1>
            <div class="property-subheader">
                <div class="property-rating-location">
                    <div class="property-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo htmlspecialchars($property['rating']); ?></span>
                    </div>
                    <span>·</span>
                    <span class="property-location"><?php echo htmlspecialchars($property['location']); ?></span>
                </div>
                <div class="property-actions">
                    <div class="property-action">
                        <i class="fas fa-share"></i>
                        <span>Share</span>
                    </div>
                    <div class="property-action">
                        <i class="far fa-heart"></i>
                        <span>Save</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Property Gallery -->
        <div class="property-gallery" style="position: relative;">
            <?php
            $main_image = isset($images[0]) ? $images[0]['image_url'] : 'https://via.placeholder.com/600x400';
            echo '<div class="gallery-main">';
            echo '<img src="' . htmlspecialchars($main_image) . '" alt="' . htmlspecialchars($property['name']) . '" class="gallery-image">';
            echo '</div>';
            
            // Display up to 4 additional images
            for ($i = 1; $i < min(5, count($images)); $i++) {
                echo '<div>';
                echo '<img src="' . htmlspecialchars($images[$i]['image_url']) . '" alt="' . htmlspecialchars($property['name']) . ' image ' . $i . '" class="gallery-image">';
                echo '</div>';
            }
            ?>
            <button class="show-all-photos">
                <i class="fas fa-th"></i>
                <span>Show all photos</span>
            </button>
        </div>
        
        <!-- Property Details -->
        <div class="property-details-container">
            <div class="property-details">
                <!-- Property Info -->
                <div class="property-info">
                    <div class="host-info">
                        <div class="host-details">
                            <h2><?php echo htmlspecialchars($property['property_type']); ?> hosted by John</h2>
                            <p><?php echo htmlspecialchars($property['bedrooms']); ?> bedroom<?php if ($property['bedrooms'] > 1) echo 's'; ?> · <?php echo htmlspecialchars($property['bathrooms']); ?> bathroom<?php if ($property['bathrooms'] > 1) echo 's'; ?> · <?php echo htmlspecialchars($property['max_guests']); ?> guest<?php if ($property['max_guests'] > 1) echo 's'; ?></p>
                        </div>
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Host" class="host-avatar">
                    </div>
                    
                    <div class="property-highlights">
                        <div class="property-highlight">
                            <div class="highlight-icon"><i class="fas fa-door-open"></i></div>
                            <div class="highlight-title">Self check-in</div>
                            <div class="highlight-description">Check yourself in with the keypad.</div>
                        </div>
                        <div class="property-highlight">
                            <div class="highlight-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="highlight-title">Great location</div>
                            <div class="highlight-description">95% of recent guests gave the location a 5-star rating.</div>
                        </div>
                        <div class="property-highlight">
                            <div class="highlight-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="highlight-title">Free cancellation before check-in</div>
                            <div class="highlight-description">Cancel before check-in for a full refund.</div>
                        </div>
                    </div>
                    
                    <div class="property-description">
                        <p><?php echo htmlspecialchars($property['description']); ?></p>
                        <p class="read-more">Read more</p>
                    </div>
                </div>
                
                <!-- Amenities Section -->
                <div class="amenities">
                    <h2 class="section-title">What this place offers</h2>
                    <div class="amenities-grid">
                        <?php foreach ($amenities as $amenity): ?>
                            <div class="amenity">
                                <div class="amenity-icon">
                                    <?php
                                    // Display different icons based on amenity name
                                    switch ($amenity) {
                                        case 'Wifi':
                                            echo '<i class="fas fa-wifi"></i>';
                                            break;
                                        case 'Kitchen':
                                            echo '<i class="fas fa-utensils"></i>';
                                            break;
                                        case 'Free parking':
                                            echo '<i class="fas fa-parking"></i>';
                                            break;
                                        case 'Pool':
                                            echo '<i class="fas fa-swimming-pool"></i>';
                                            break;
                                        case 'Air conditioning':
                                            echo '<i class="fas fa-snowflake"></i>';
                                            break;
                                        case 'Washing machine':
                                            echo '<i class="fas fa-tshirt"></i>';
                                            break;
                                        case 'TV':
                                            echo '<i class="fas fa-tv"></i>';
                                            break;
                                        case 'Heating':
                                            echo '<i class="fas fa-temperature-high"></i>';
                                            break;
                                        case 'Dedicated workspace':
                                            echo '<i class="fas fa-laptop"></i>';
                                            break;
                                        case 'Hair dryer':
                                            echo '<i class="fas fa-wind"></i>';
                                            break;
                                        default:
                                            echo '<i class="fas fa-check"></i>';
                                    }
                                    ?>
                                </div>
                                <div><?php echo htmlspecialchars($amenity); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="show-all-amenities">Show all amenities</button>
                </div>
                
                <!-- Reviews Section -->
                <div class="reviews">
                    <div class="reviews-header">
                        <i class="fas fa-star"></i>
                        <h2 class="section-title"><?php echo htmlspecialchars($property['rating']); ?> · <?php echo $review_count; ?> reviews</h2>
                    </div>
                    
                    <div class="reviews-grid">
                        <?php foreach ($reviews as $review): ?>
                            <div class="review">
                                <div class="review-header">
                                    <img src="https://randomuser.me/api/portraits/<?php echo rand(0, 1) ? 'men' : 'women'; ?>/<?php echo rand(1, 99); ?>.jpg" alt="Reviewer" class="reviewer-avatar">
                                    <div class="reviewer-info">
                                        <h4><?php echo htmlspecialchars($review['user_name']); ?></h4>
                                        <div class="review-date"><?php echo date('F Y', strtotime($review['created_at'])); ?></div>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <?php echo htmlspecialchars($review['comment']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($review_count > 4): ?>
                        <button class="show-all-reviews">Show all <?php echo $review_count; ?> reviews</button>
                    <?php endif; ?>
                </div>
                
                <!-- Location Section -->
                <div class="location">
                    <h2 class="section-title">Where you'll be</h2>
                    <p><?php echo htmlspecialchars($property['location']); ?></p>
                    <div class="map-container">
                        <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo urlencode($property['location']); ?>&zoom=13&size=600x400&maptype=roadmap&markers=color:red%7C<?php echo urlencode($property['location']); ?>&key=YOUR_API_KEY" alt="Map">
                    </div>
                </div>
            </div>
            
            <!-- Booking Card -->
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-price">$<?php echo htmlspecialchars($property['price_per_night']); ?> <span style="font-weight: normal; font-size: 16px;">night</span></div>
                    <div class="booking-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo htmlspecialchars($property['rating']); ?></span>
                        <span>·</span>
                        <span><?php echo $review_count; ?> reviews</span>
                    </div>
                </div>
                
                <form class="booking-form">
                    <div class="booking-dates">
                        <div class="booking-date-input">
                            <label for="check-in">CHECK-IN</label>
                            <input type="date" id="check-in" name="check_in" required>
                        </div>
                        <div class="booking-date-input">
                            <label for="check-out">CHECKOUT</label>
                            <input type="date" id="check-out" name="check_out" required>
                        </div>
                    </div>
                    
                    <div class="booking-guests">
                        <label for="guests">GUESTS</label>
                        <select id="guests" name="guests">
                            <?php for ($i = 1; $i <= $property['max_guests']; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> guest<?php if ($i > 1) echo 's'; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="booking-button">Reserve</button>
                </form>
                
                <div class="booking-note">You won't be charged yet</div>
                
                <div class="booking-details">
                    <div class="booking-detail">
                        <div class="booking-detail-text">$<?php echo htmlspecialchars($property['price_per_night']); ?> x <span id="nights-count">5</span> nights</div>
                        <div id="nights-total">$<?php echo htmlspecialchars($property['price_per_night'] * 5); ?></div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-text">Cleaning fee</div>
                        <div>$50</div>
                    </div>
                    <div class="booking-detail">
                        <div class="booking-detail-text">Service fee</div>
                        <div>$<?php echo round($property['price_per_night'] * 5 * 0.12); ?></div>
                    </div>
                </div>
                
                <div class="booking-total">
                    <div>Total</div>
                    <div id="booking-grand-total">$<?php echo htmlspecialchars($property['price_per_night'] * 5 + 50 + round($property['price_per_night'] * 5 * 0.12)); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Set minimum date for check-in to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check-in').min = today;
        
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
            
            updateBookingCalculations();
        });
        
        // Update booking calculations when check-out date changes
        document.getElementById('check-out').addEventListener('change', updateBookingCalculations);
        
        // Function to update booking calculations
        function updateBookingCalculations() {
            const checkInDate = new Date(document.getElementById('check-in').value);
            const checkOutDate = new Date(document.getElementById('check-out').value);
            
            // Only calculate if both dates are valid
            if (isNaN(checkInDate.getTime()) || isNaN(checkOutDate.getTime())) {
                return;
            }
            
            // Calculate number of nights
            const nights = Math.round((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
            
            if (nights > 0) {
                // Update nights count
                document.getElementById('nights-count').textContent = nights;
                
                // Calculate costs
                const pricePerNight = <?php echo $property['price_per_night']; ?>;
                const nightsTotal = pricePerNight * nights;
                const cleaningFee = 50;
                
                const serviceFee = Math.round(nightsTotal * 0.12);
                const grandTotal = nightsTotal + cleaningFee + serviceFee;
                
                // Update displayed values
                document.getElementById('nights-total').textContent = '$' + nightsTotal;
                document.getElementById('booking-grand-total').textContent = '$' + grandTotal;
            }
        }
        
        // Handle booking form submission
        document.querySelector('.booking-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            const guests = document.getElementById('guests').value;
            
            // Validate dates
            if (!checkIn || !checkOut) {
                alert('Please select check-in and check-out dates');
                return;
            }
            
            // Show booking confirmation (in a real app, this would send data to server)
            alert('Booking request submitted! In a real application, this would process the payment and confirm your reservation.');
        });
        
        // Show all photos button
        document.querySelector('.show-all-photos').addEventListener('click', function() {
            alert('This would open a gallery view with all property photos');
        });
        
        // Show all amenities button
        document.querySelector('.show-all-amenities').addEventListener('click', function() {
            alert('This would open a modal with all amenities listed');
        });
        
        // Show all reviews button (if it exists)
        const showAllReviewsBtn = document.querySelector('.show-all-reviews');
        if (showAllReviewsBtn) {
            showAllReviewsBtn.addEventListener('click', function() {
                alert('This would open a modal with all reviews');
            });
        }
        
        // Read more button
        document.querySelector('.read-more').addEventListener('click', function() {
            alert('This would expand the description to show the full text');
        });
    </script>
</body>
</html>

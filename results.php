<?php
session_start();
include 'config.php';

// Function to safely retrieve data from $_POST
function get_post_data($key) {
    return isset($_POST[$key]) ? $_POST[$key] : '';
}

// Function to safely retrieve data from $_GET
function get_get_data($key) {
    return isset($_GET[$key]) ? $_GET[$key] : '';
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle login
    if (isset($_POST['login'])) {
        $email = get_post_data('email');
        $password = get_post_data('password');

        $sql = "SELECT id, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php"); // Redirect to home page
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "Invalid email or password.";
        }
        $stmt->close();
    }

    // Handle registration
    if (isset($_POST['register'])) {
        $name = get_post_data('name');
        $email = get_post_data('email');
        $password = get_post_data('password');
        $confirm_password = get_post_data('confirm_password');

        if ($password != $confirm_password) {
            $register_error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                header("Location: index.php"); // Redirect to home page
                exit();
            } else {
                $register_error = "Registration failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Handle property submission
    if (isset($_POST['submit_property'])) {
        $name = get_post_data('name');
        $location = get_post_data('location');
        $description = get_post_data('description');
        $property_type = get_post_data('property_type');
        $price_per_night = get_post_data('price_per_night');
        $bedrooms = get_post_data('bedrooms');
        $bathrooms = get_post_data('bathrooms');
        $max_guests = get_post_data('max_guests');
        $amenities = isset($_POST['amenities']) ? implode(',', $_POST['amenities']) : ''; // Amenities are handled as an array

        // File upload handling
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $upload_error = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) {
            $upload_error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $upload_error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            $upload_error = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert property data into the database
                $sql = "INSERT INTO properties (name, location, description, property_type, price_per_night, bedrooms, bathrooms, max_guests, amenities) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssddddd", $name, $location, $description, $property_type, $price_per_night, $bedrooms, $bathrooms, $max_guests, $amenities);

                if ($stmt->execute()) {
                    $property_id = $conn->insert_id;

                    // Insert image URL into the images table
                    $image_url = $target_file; // URL to the uploaded image
                    $sql_image = "INSERT INTO images (property_id, image_url, is_primary) VALUES (?, ?, 1)"; // Assuming it's the primary image
                    $stmt_image = $conn->prepare($sql_image);
                    $stmt_image->bind_param("is", $property_id, $image_url);

                    if ($stmt_image->execute()) {
                        $upload_success = "Property submitted successfully!";
                    } else {
                        $upload_error = "Error submitting image: " . $stmt_image->error;
                    }

                    $stmt_image->close();
                } else {
                    $upload_error = "Error submitting property: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $upload_error = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // Redirect to home page
    exit();
}

?>
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Airbnb Clone</title>
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
            display: flex;
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Filters Section */
        .filters {
            width: 280px;
            padding-right: 20px;
            border-right: 1px solid #ddd;
        }
        
        .filter-section {
            margin-bottom: 30px;
        }
        
        .filter-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-checkbox input {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .filter-checkbox label {
            cursor: pointer;
        }
        
        .price-range {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .price-inputs {
            display: flex;
            gap: 10px;
        }
        
        .price-input {
            flex: 1;
        }
        
        .price-input label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .price-input input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .filter-button {
            background-color: #FF385C;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .filter-button:hover {
            background-color: #E31C5F;
        }
        
        /* Results Section */
        .results {
            flex: 1;
            padding-left: 20px;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .results-count {
            font-size: 18px;
            font-weight: 600;
        }
        
        .sort-options {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sort-options label {
            font-weight: 500;
        }
        
        .sort-options select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
        }
        
        .property-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
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
        
        .property-image-container {
            position: relative;
            height: 200px;
        }
        
        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .property-wishlist {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            text-shadow: 0 0 3px rgba(0, 0, 0, 0.5);
        }
        
        .property-info {
            padding: 20px;
        }
        
        .property-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .property-location {
            font-size: 16px;
            font-weight: 500;
        }
        
        .property-rating {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .property-rating i {
            color: #FF385C;
        }
        
        .property-name {
            font-size: 16px;
            color: #717171;
            margin-bottom: 8px;
        }
        
        .property-features {
            color: #717171;
            margin-bottom: 8px;
        }
        
        .property-dates {
            color: #717171;
            margin-bottom: 12px;
        }
        
        .property-price {
            font-weight: bold;
        }
        
        .property-total {
            text-decoration: underline;
            cursor: pointer;
        }
        
        .no-results {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            color: #717171;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 10px;
        }
        
        .pagination-button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 20px;
            background-color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .pagination-button:hover {
            background-color: #f7f7f7;
        }
        
        .pagination-button.active {
            background-color: #222;
            color: white;
            border-color: #222;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .header-container {
                padding: 15px 20px;
            }
            
            .main-container {
                flex-direction: column;
            }
            
            .filters {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ddd;
                padding-right: 0;
                padding-bottom: 20px;
                margin-bottom: 20px;
            }
            
            .results {
                padding-left: 0;
            }
        }
        
        @media (max-width: 768px) {
            .property-list {
                grid-template-columns: 1fr;
            }
            
            .results-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
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
        <!-- Filters Section -->
        <aside class="filters">
            <div class="filter-section">
                <h3 class="filter-title">Property type</h3>
                <div class="filter-options">
                    <div class="filter-checkbox">
                        <input type="checkbox" id="entire-home" name="property_type" value="Entire home" <?php if(isset($_GET['property_type']) && $_GET['property_type'] == 'Entire home') echo 'checked'; ?>>
                        <label for="entire-home">Entire home</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="private-room" name="property_type" value="Private room" <?php if(isset($_GET['property_type']) && $_GET['property_type'] == 'Private room') echo 'checked'; ?>>
                        <label for="private-room">Private room</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="shared-room" name="property_type" value="Shared room" <?php if(isset($_GET['property_type']) && $_GET['property_type'] == 'Shared room') echo 'checked'; ?>>
                        <label for="shared-room">Shared room</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Price range</h3>
                <div class="price-range">
                    <div class="price-inputs">
                        <div class="price-input">
                            <label for="min-price">Min price</label>
                            <input type="number" id="min-price" name="min_price" placeholder="$" min="0" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
                        </div>
                        <div class="price-input">
                            <label for="max-price">Max price</label>
                            <input type="number" id="max-price" name="max_price" placeholder="$" min="0" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Rooms and beds</h3>
                <div class="filter-options">
                    <div class="filter-checkbox">
                        <input type="checkbox" id="one-bedroom" name="bedrooms" value="1" <?php if(isset($_GET['bedrooms']) && $_GET['bedrooms'] == '1') echo 'checked'; ?>>
                        <label for="one-bedroom">1 bedroom</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="two-bedrooms" name="bedrooms" value="2" <?php if(isset($_GET['bedrooms']) && $_GET['bedrooms'] == '2') echo 'checked'; ?>>
                        <label for="two-bedrooms">2 bedrooms</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="three-bedrooms" name="bedrooms" value="3" <?php if(isset($_GET['bedrooms']) && $_GET['bedrooms'] == '3') echo 'checked'; ?>>
                        <label for="three-bedrooms">3+ bedrooms</label>
                    </div>
                </div>
            </div>
            
            <div class="filter-section">
                <h3 class="filter-title">Amenities</h3>
                <div class="filter-options">
                    <div class="filter-checkbox">
                        <input type="checkbox" id="wifi" name="amenities" value="1" <?php if(isset($_GET['amenities']) && $_GET['amenities'] == '1') echo 'checked'; ?>>
                        <label for="wifi">Wifi</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="kitchen" name="amenities" value="2" <?php if(isset($_GET['amenities']) && $_GET['amenities'] == '2') echo 'checked'; ?>>
                        <label for="kitchen">Kitchen</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="free-parking" name="amenities" value="3" <?php if(isset($_GET['amenities']) && $_GET['amenities'] == '3') echo 'checked'; ?>>
                        <label for="free-parking">Free parking</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="pool" name="amenities" value="4" <?php if(isset($_GET['amenities']) && $_GET['amenities'] == '4') echo 'checked'; ?>>
                        <label for="pool">Pool</label>
                    </div>
                    <div class="filter-checkbox">
                        <input type="checkbox" id="air-conditioning" name="amenities" value="5" <?php if(isset($_GET['amenities']) && $_GET['amenities'] == '5') echo 'checked'; ?>>
                        <label for="air-conditioning">Air conditioning</label>
                    </div>
                </div>
            </div>
            
            <button id="apply-filters" class="filter-button">Apply filters</button>
        </aside>
        
        <!-- Results Section -->
        <main class="results">
            <?php
            // Get search parameters
            $location = isset($_GET['location']) ? $_GET['location'] : '';
            $check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
            $check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
            $guests = isset($_GET['guests']) ? $_GET['guests'] : '';
            $property_type = isset($_GET['property_type']) ? $_GET['property_type'] : '';
            $min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
            $max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
            $bedrooms = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
            $amenities = isset($_GET['amenities']) ? $_GET['amenities'] : '';
            
            // Build SQL query - SIMPLIFIED to ensure results show up
            $sql = "SELECT p.*, i.image_url FROM properties p 
                    LEFT JOIN images i ON p.id = i.property_id";
            
            // Add WHERE clause only if we have specific filters
            $whereClause = [];
            
            if (!empty($location)) {
                $whereClause[] = "(p.location LIKE '%" . $conn->real_escape_string($location) . "%' 
                                OR p.name LIKE '%" . $conn->real_escape_string($location) . "%'
                                OR p.description LIKE '%" . $conn->real_escape_string($location) . "%')";
            }
            
            if (!empty($property_type)) {
                $whereClause[] = "p.property_type = '" . $conn->real_escape_string($property_type) . "'";
            }
            
            if (!empty($min_price)) {
                $whereClause[] = "p.price_per_night >= " . (float)$min_price;
            }
            
            if (!empty($max_price)) {
                $whereClause[] = "p.price_per_night <= " . (float)$max_price;
            }
            
            if (!empty($bedrooms)) {
                if ($bedrooms == 3) {
                    $whereClause[] = "p.bedrooms >= 3";
                } else {
                    $whereClause[] = "p.bedrooms = " . (int)$bedrooms;
                }
            }
            
            if (!empty($amenities)) {
                $whereClause[] = "EXISTS (SELECT 1 FROM property_amenities pa WHERE pa.property_id = p.id AND pa.amenity_id = " . (int)$amenities . ")";
            }
            
            if (!empty($guests)) {
                $whereClause[] = "p.max_guests >= " . (int)$guests;
            }
            
            // Add WHERE clause if we have conditions
            if (!empty($whereClause)) {
                $sql .= " WHERE " . implode(' AND ', $whereClause);
            }
            
            // Add GROUP BY to avoid duplicates
            $sql .= " GROUP BY p.id";
            
            // Add sorting
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'recommended';
            switch ($sort) {
                case 'price_low':
                    $sql .= " ORDER BY p.price_per_night ASC";
                    break;
                case 'price_high':
                    $sql .= " ORDER BY p.price_per_night DESC";
                    break;
                case 'rating':
                    $sql .= " ORDER BY p.rating DESC";
                    break;
                default:
                    $sql .= " ORDER BY p.rating DESC"; // Default to recommended (highest rated)
            }
            
            // Execute query
            $result = $conn->query($sql);
            $total_results = $result ? $result->num_rows : 0;
            
            // For debugging - remove in production
            // echo "<p>Debug SQL: " . $sql . "</p>";
            ?>
            
            <div class="results-header">
                <div class="results-count"><?php echo $total_results; ?> stays 
                    <?php if (!empty($location)) echo "in " . htmlspecialchars($location); ?>
                    <?php if (!empty($check_in) && !empty($check_out)) echo " · " . htmlspecialchars($check_in) . " to " . htmlspecialchars($check_out); ?>
                </div>
                
                <div class="sort-options">
                    <label for="sort">Sort by:</label>
                    <select id="sort" name="sort">
                        <option value="recommended" <?php if ($sort == 'recommended') echo 'selected'; ?>>Recommended</option>
                        <option value="price_low" <?php if ($sort == 'price_low') echo 'selected'; ?>>Price (low to high)</option>
                        <option value="price_high" <?php if ($sort == 'price_high') echo 'selected'; ?>>Price (high to low)</option>
                        <option value="rating" <?php if ($sort == 'rating') echo 'selected'; ?>>Top rated</option>
                    </select>
                </div>
            </div>
            
            <?php if ($total_results > 0): ?>
                <div class="property-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="property-card" onclick="goToProperty(<?php echo $row['id']; ?>)">
                            <div class="property-image-container">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="property-image">
                                <button class="property-wishlist"><i class="far fa-heart"></i></button>
                            </div>
                            <div class="property-info">
                                <div class="property-header">
                                    <div class="property-location"><?php echo htmlspecialchars($row['location']); ?></div>
                                    <div class="property-rating">
                                        <i class="fas fa-star"></i>
                                        <span><?php echo htmlspecialchars($row['rating']); ?></span>
                                    </div>
                                </div>
                                <div class="property-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="property-features">
                                    <?php echo htmlspecialchars($row['property_type']); ?> · 
                                    <?php echo htmlspecialchars($row['bedrooms']); ?> bedroom<?php if ($row['bedrooms'] > 1) echo 's'; ?> · 
                                    <?php echo htmlspecialchars($row['bathrooms']); ?> bathroom<?php if ($row['bathrooms'] > 1) echo 's'; ?>
                                </div>
                                <?php if (!empty($check_in) && !empty($check_out)): ?>
                                    <div class="property-dates">
                                        <?php 
                                            $check_in_date = new DateTime($check_in);
                                            $check_out_date = new DateTime($check_out);
                                            $nights = $check_in_date->diff($check_out_date)->days;
                                            echo htmlspecialchars($check_in_date->format('M d')) . ' - ' . htmlspecialchars($check_out_date->format('M d'));
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <div class="property-price">
                                    $<?php echo htmlspecialchars($row['price_per_night']); ?> night
                                    <?php if (!empty($check_in) && !empty($check_out)): ?>
                                        <span class="property-total">
                                            $<?php echo htmlspecialchars($row['price_per_night'] * $nights); ?> total
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <?php if ($total_results > 12): ?>
                    <div class="pagination">
                        <button class="pagination-button active">1</button>
                        <button class="pagination-button">2</button>
                        <button class="pagination-button">3</button>
                        <button class="pagination-button">Next</button>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="no-results">
                    <h3>No properties found matching your criteria</h3>
                    <p>Try adjusting your filters or search for a different location</p>
                    <button onclick="window.location.href='results.php'" class="filter-button" style="margin-top: 20px;">Clear all filters</button>
                </div>
            <?php endif; ?>
        </main>
    </div>
    
    <script>
        // Function to navigate to property details page
        function goToProperty(id) {
            window.location.href = `property.php?id=${id}`;
        }
        
        // Function to get current URL parameters
        function getUrlParams() {
            const params = new URLSearchParams(window.location.search);
            return params;
        }
        
        // Function to update URL with filters
        function updateUrlWithFilters() {
            const params = getUrlParams();
            
            // Get property type filters
            const propertyTypeCheckboxes = document.querySelectorAll('input[name="property_type"]:checked');
            if (propertyTypeCheckboxes.length > 0) {
                params.set('property_type', propertyTypeCheckboxes[0].value);
            } else {
                params.delete('property_type');
            }
            
            // Get price range
            const minPrice = document.getElementById('min-price').value;
            const maxPrice = document.getElementById('max-price').value;
            if (minPrice) {
                params.set('min_price', minPrice);
            } else {
                params.delete('min_price');
            }
            if (maxPrice) {
                params.set('max_price', maxPrice);
            } else {
                params.delete('max_price');
            }
            
            // Get bedroom filters
            const bedroomCheckboxes = document.querySelectorAll('input[name="bedrooms"]:checked');
            if (bedroomCheckboxes.length > 0) {
                params.set('bedrooms', bedroomCheckboxes[0].value);
            } else {
                params.delete('bedrooms');
            }
            
            // Get amenity filters
            const amenityCheckboxes = document.querySelectorAll('input[name="amenities"]:checked');
            if (amenityCheckboxes.length > 0) {
                params.set('amenities', amenityCheckboxes[0].value);
            } else {
                params.delete('amenities');
            }
            
            // Get sort option
            const sort = document.getElementById('sort').value;
            params.set('sort', sort);
            
            // Update URL
            window.location.href = `results.php?${params.toString()}`;
        }
        
        // Apply filters button click event
        document.getElementById('apply-filters').addEventListener('click', updateUrlWithFilters);
        
        // Sort select change event
        document.getElementById('sort').addEventListener('change', updateUrlWithFilters);
        
        // Set filter values from URL parameters
        window.addEventListener('DOMContentLoaded', function() {
            const params = getUrlParams();
            
            // Set property type checkboxes
            if (params.has('property_type')) {
                const propertyType = params.get('property_type');
                const checkbox = document.querySelector(`input[name="property_type"][value="${propertyType}"]`);
                if (checkbox) checkbox.checked = true;
            }
            
            // Set price range inputs
            if (params.has('min_price')) {
                document.getElementById('min-price').value = params.get('min_price');
            }
            if (params.has('max_price')) {
                document.getElementById('max-price').value = params.get('max_price');
            }
            
            // Set bedroom checkboxes
            if (params.has('bedrooms')) {
                const bedrooms = params.get('bedrooms');
                const checkbox = document.querySelector(`input[name="bedrooms"][value="${bedrooms}"]`);
                if (checkbox) checkbox.checked = true;
            }
            
            // Set amenity checkboxes
            if (params.has('amenities')) {
                const amenities = params.get('amenities');
                const checkbox = document.querySelector(`input[name="amenities"][value="${amenities}"]`);
                if (checkbox) checkbox.checked = true;
            }
            
            // Set sort option
            if (params.has('sort')) {
                document.getElementById('sort').value = params.get('sort');
            }
        });
    </script>
</body>
</html>

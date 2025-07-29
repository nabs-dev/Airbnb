<?php
$host = "localhost";
$username = "u8gr0sjr9p4p4";
$password = "9yxuqyo3mt85";
$database = "dbedvmvqltlhza";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Insert sample data if the properties table is empty
$check_data = $conn->query("SELECT COUNT(*) as count FROM properties");
$row = $check_data->fetch_assoc();

if ($row['count'] == 0) {
    // Insert sample properties
    $sample_properties = [
        [
            'name' => 'Luxury Beach Villa',
            'description' => 'Beautiful villa with ocean views and private pool.',
            'price_per_night' => 250.00,
            'location' => 'Miami, FL',
            'address' => '123 Ocean Drive, Miami, FL 33139',
            'property_type' => 'Entire home',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'max_guests' => 6,
            'rating' => 4.8,
            'image_url' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914'
        ],
        [
            'name' => 'Downtown Loft',
            'description' => 'Modern loft in the heart of downtown with city views.',
            'price_per_night' => 150.00,
            'location' => 'New York, NY',
            'address' => '456 Broadway, New York, NY 10013',
            'property_type' => 'Entire home',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'max_guests' => 2,
            'rating' => 4.5,
            'image_url' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb'
        ],
        [
            'name' => 'Cozy Mountain Cabin',
            'description' => 'Rustic cabin with fireplace and mountain views.',
            'price_per_night' => 120.00,
            'location' => 'Aspen, CO',
            'address' => '789 Pine Road, Aspen, CO 81611',
            'property_type' => 'Entire home',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'max_guests' => 4,
            'rating' => 4.7,
            'image_url' => 'https://images.unsplash.com/photo-1542718610-a1d656d1884c'
        ],
        [
            'name' => 'Beachfront Condo',
            'description' => 'Stunning condo steps away from the beach.',
            'price_per_night' => 180.00,
            'location' => 'San Diego, CA',
            'address' => '101 Pacific Ave, San Diego, CA 92109',
            'property_type' => 'Entire home',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'max_guests' => 4,
            'rating' => 4.6,
            'image_url' => 'https://images.unsplash.com/photo-1523217582562-09d0def993a6'
        ],
        [
            'name' => 'Private Room in Townhouse',
            'description' => 'Comfortable private room in a shared townhouse.',
            'price_per_night' => 75.00,
            'location' => 'Boston, MA',
            'address' => '202 Commonwealth Ave, Boston, MA 02116',
            'property_type' => 'Private room',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'max_guests' => 2,
            'rating' => 4.4,
            'image_url' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511'
        ],
        [
            'name' => 'Modern Apartment',
            'description' => 'Sleek and modern apartment with all amenities.',
            'price_per_night' => 135.00,
            'location' => 'Chicago, IL',
            'address' => '303 Michigan Ave, Chicago, IL 60601',
            'property_type' => 'Entire home',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'max_guests' => 3,
            'rating' => 4.3,
            'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267'
        ],
        [
            'name' => 'Lakefront Cottage',
            'description' => 'Charming cottage with direct lake access.',
            'price_per_night' => 160.00,
            'location' => 'Lake Tahoe, CA',
            'address' => '404 Lakeview Dr, South Lake Tahoe, CA 96150',
            'property_type' => 'Entire home',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'max_guests' => 5,
            'rating' => 4.9,
            'image_url' => 'https://images.unsplash.com/photo-1475087542963-13ab5e611954'
        ],
        [
            'name' => 'Historic Brownstone',
            'description' => 'Elegant brownstone with classic architecture.',
            'price_per_night' => 200.00,
            'location' => 'Brooklyn, NY',
            'address' => '505 Park Place, Brooklyn, NY 11238',
            'property_type' => 'Entire home',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'max_guests' => 6,
            'rating' => 4.7,
            'image_url' => 'https://images.unsplash.com/photo-1501183638710-841dd1904471'
        ]
    ];

    // Insert properties
    foreach ($sample_properties as $property) {
        $sql = "INSERT INTO properties (name, description, price_per_night, location, address, property_type, bedrooms, bathrooms, max_guests, rating) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsssiiid", 
            $property['name'], 
            $property['description'], 
            $property['price_per_night'], 
            $property['location'], 
            $property['address'], 
            $property['property_type'], 
            $property['bedrooms'], 
            $property['bathrooms'], 
            $property['max_guests'], 
            $property['rating']
        );
        $stmt->execute();
        
        $property_id = $conn->insert_id;
        
        // Insert image
        $sql_image = "INSERT INTO images (property_id, image_url, is_primary) VALUES (?, ?, 1)";
        $stmt_image = $conn->prepare($sql_image);
        $stmt_image->bind_param("is", $property_id, $property['image_url']);
        $stmt_image->execute();
    }
    
    // Insert amenities if they don't exist
    $amenities = ['Wifi', 'Kitchen', 'Free parking', 'Pool', 'Air conditioning', 
                 'Washing machine', 'TV', 'Heating', 'Dedicated workspace', 'Hair dryer'];
    
    foreach ($amenities as $amenity) {
        $sql = "INSERT IGNORE INTO amenities (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $amenity);
        $stmt->execute();
    }
    
    // Add amenities to properties
    $properties_count = count($sample_properties);
    $amenities_count = count($amenities);
    
    for ($i = 1; $i <= $properties_count; $i++) {
        // Add 3-5 random amenities to each property
        $num_amenities = rand(3, 5);
        $amenity_ids = array_rand(range(1, $amenities_count), $num_amenities);
        if (!is_array($amenity_ids)) {
            $amenity_ids = [$amenity_ids];
        }
        
        foreach ($amenity_ids as $amenity_id) {
            $amenity_id = $amenity_id + 1; // Adjust for 1-based IDs
            $sql = "INSERT IGNORE INTO property_amenities (property_id, amenity_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $i, $amenity_id);
            $stmt->execute();
        }
    }
}
?>

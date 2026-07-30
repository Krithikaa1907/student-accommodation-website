USE student_accommodation;

-- Sample Users
INSERT INTO users (name, email, password, phone) VALUES
('Arun Kumar', 'arun@example.com', 'hashed_pass_123', '9876543210'),
('Priya Sharma', 'priya@example.com', 'hashed_pass_456', '9876543211');

-- Sample Properties
INSERT INTO properties (name, city, price, gender, rating, image_url, description) VALUES
('Green Stay PG', 'Chennai', 7500.00, 'Male', 4.5, 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=500', 'Spacious rooms with modern amenities near IT corridor.'),
('Comfort Ladies Hostel', 'Chennai', 6000.00, 'Female', 4.8, 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=500', 'Safe and secure stay with home-cooked food.'),
('Urban Student Living', 'Coimbatore', 8500.00, 'Unisex', 4.2, 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=500', 'Fully furnished PG with high-speed WiFi.');

-- Sample Amenities
INSERT INTO amenities (name) VALUES
('WiFi'),
('Air Conditioner'),
('Food Included'),
('Laundry'),
('24/7 Power Backup');

-- Mapping Amenities to Properties
INSERT INTO property_amenities (property_id, amenity_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 5),
(2, 1), (2, 3), (2, 4),
(3, 1), (3, 2), (3, 4), (3, 5);
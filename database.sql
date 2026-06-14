-- ============================================
-- OffHour Watches - Database Setup
-- Import this file in phpMyAdmin
-- ============================================

CREATE DATABASE IF NOT EXISTS offhours_watches;
USE offhours_watches;

-- ============================================
-- Table: watches
-- ============================================
CREATE TABLE IF NOT EXISTS watches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    title VARCHAR(150) NOT NULL,
    collection_name VARCHAR(50) DEFAULT NULL,
    price DECIMAL(12,2) NOT NULL,
    image VARCHAR(500) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table: orders
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cod',
    total_amount DECIMAL(12,2) NOT NULL,
    status VARCHAR(30) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table: order_items
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    watch_id INT DEFAULT NULL,
    title VARCHAR(150) NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (watch_id) REFERENCES watches(id) ON DELETE SET NULL
);

-- ============================================
-- Seed data: 12 Tissot-themed watches
-- ============================================
INSERT INTO watches (brand, title, collection_name, price, image, description) VALUES
('TISSOT', 'PRX Powermatic 80 - Silver', 'PRX', 185000.00, 'https://m.media-amazon.com/images/I/61vT+XaxmRL.jpg', 'A retro-inspired automatic dress watch with an 80-hour power reserve and integrated steel bracelet.'),
('TISSOT', 'PRX Chronograph - Blue', 'PRX', 245000.00, 'https://images.squarespace-cdn.com/content/v1/5c78138211f784469d4817df/b94cac64-2583-4bb9-8eae-92a89ab7aead/DSC_0573.jpg', 'A bold chronograph variant of the iconic PRX line with a striking blue dial.'),
('TISSOT', 'PRX Powermatic 80 - 35mm', 'PRX', 168000.00, 'https://monochrome-watches.com/app/uploads/2023/06/Tissot-PRX-Powermatic-80-35mm-vs-Tissot-PRX-Powermatic-80-40mm-Comparative-review-2.jpg', 'A compact 35mm automatic PRX, perfect for a slimmer wrist profile.'),
('TISSOT', 'Gentleman Powermatic 80', 'Gentleman', 220000.00, 'https://monochrome-watches.com/wp-content/uploads/2020/11/Tissot-Gentleman-Powermatic-80-Silicium-Blue-Dial-Steel-bracelet-review-value-proposition-1.jpg', 'A classic dress watch with a silicon balance spring and 80-hour power reserve.'),
('TISSOT', 'Seastar 1000 Powermatic', 'Seastar', 210000.00, 'https://cdn.accentuate.io/41569115766850/2742991323202/Tissot_Seastar1000_Blue_header-v1722636962763.jpg?2000x1333&transform=resize=2000', 'A robust dive watch rated to 300m water resistance with a striking blue dial.'),
('TISSOT', 'PRX Powermatic 80 - Black Dial', 'PRX', 192000.00, 'https://images.unsplash.com/photo-1547996160-81dfa63595aa?auto=format&fit=crop&q=80&w=800', 'A sleek black-dialed PRX automatic with a steel integrated bracelet.'),
('TISSOT', 'Gentleman Chronograph - Steel', 'Gentleman', 235000.00, 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&q=80&w=800', 'A refined chronograph from the Gentleman collection with a steel case and bracelet.'),
('TISSOT', 'Seastar 2000 Professional', 'Seastar', 265000.00, 'https://images.unsplash.com/photo-1539874754764-5a96559165b0?auto=format&fit=crop&q=80&w=800', 'A professional-grade dive watch built for serious water resistance and durability.'),
('TISSOT', 'Ballade Powermatic 80', 'Ballade', 198000.00, 'https://images.unsplash.com/photo-1533139502658-0198f920d8e8?auto=format&fit=crop&q=80&w=800', 'An elegant rectangular dress watch combining Art Deco styling with automatic movement.'),
('TISSOT', 'PRX Powermatic 80 - Rose Gold', 'PRX', 258000.00, 'https://images.unsplash.com/photo-1612817159949-195b6eb9e31a?auto=format&fit=crop&q=80&w=800', 'A premium rose-gold-toned PRX automatic for a touch of luxury on the wrist.'),
('TISSOT', 'Gentleman Powermatic 80 - Green', 'Gentleman', 228000.00, 'https://images.unsplash.com/photo-1518131945642-26ddae23c530?auto=format&fit=crop&q=80&w=800', 'A modern take on the Gentleman line with a sunburst green dial.'),
('TISSOT', 'Seastar 1000 Chronograph', 'Seastar', 232000.00, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=800', 'A sporty chronograph from the Seastar line built for both diving and everyday wear.');

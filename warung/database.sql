CREATE DATABASE IF NOT EXISTS warung_db;
USE warung_db;

-- Tabel Users (Admin & User biasa)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Menu Makanan & Minuman
CREATE TABLE menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    kategori ENUM('makanan', 'minuman') NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    deskripsi TEXT,
    tersedia TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    menu_id INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'diproses', 'selesai') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (menu_id) REFERENCES menu(id)
);

-- =============================================
-- DATA AWAL (Sample Data)
-- =============================================

-- Akun Admin (password: admin123)
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin', MD5('admin123'), 'admin');

-- Akun User biasa (password: user123)
INSERT INTO users (nama, username, password, role) VALUES
('Budi Santoso', 'budi', MD5('user123'), 'user'),
('Siti Rahayu', 'siti', MD5('user123'), 'user');

-- Data Menu
INSERT INTO menu (nama_menu, kategori, harga, deskripsi) VALUES
('Nasi Goreng', 'makanan', 15000, 'Nasi goreng spesial dengan telur dan ayam'),
('Mie Goreng', 'makanan', 13000, 'Mie goreng lezat dengan sayuran segar'),
('Ayam Goreng', 'makanan', 18000, 'Ayam goreng renyah bumbu rempah'),
('Nasi Putih', 'makanan', 5000, 'Nasi putih hangat'),
('Es Teh Manis', 'minuman', 5000, 'Teh manis dingin menyegarkan'),
('Es Jeruk', 'minuman', 7000, 'Jeruk peras segar dengan es'),
('Kopi Hitam', 'minuman', 8000, 'Kopi hitam panas atau dingin'),
('Air Mineral', 'minuman', 4000, 'Air mineral botol 600ml');

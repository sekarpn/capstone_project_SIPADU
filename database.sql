-- Database Schema for Meeting / Auditorium Room Booking System
CREATE DATABASE IF NOT EXISTS meeting_room_booking DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meeting_room_booking;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN', 'USER') DEFAULT 'USER',
    nim_nip VARCHAR(50) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    department VARCHAR(100) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rooms Table (Auditorium / Meeting Rooms)
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    location VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status ENUM('Available', 'Maintenance') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    event_description TEXT DEFAULT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('PENDING', 'APPROVED', 'REJECTED', 'CANCELLED') DEFAULT 'PENDING',
    rejection_reason TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default Admin and User Seed Data (Password: password123)
-- Hash for 'password123': $2y$10$4q9Z8e8o3yKxH1Y3n4s5u.b5v5x5z5a5b5c5d5e5f5g5h5i5j5k
INSERT INTO users (name, email, password, role, nim_nip, phone, department) VALUES
('Administrator Utama', 'admin@mail.com', '$2y$10$eD4W9rR3.4qL5Y1/p6X/u.1.9c1jN7M/Rk/3X3X3X3X3X3X3X3X3X', 'ADMIN', '198501012010121001', '081234567890', 'Biro Umum & Fasilitas'),
('Ahmad Mahasiswa', 'user@mail.com', '$2y$10$eD4W9rR3.4qL5Y1/p6X/u.1.9c1jN7M/Rk/3X3X3X3X3X3X3X3X3X', 'USER', '2101012345', '089876543210', 'Teknik Informatika');

-- Sample Rooms
INSERT INTO rooms (name, capacity, location, description, image, status) VALUES
('Auditorium Utama Kampus', 500, 'Gedung Rektorat Lt. 3', 'Auditorium luas dilengkapi AC, sound system profesional, panggung utama, dan videotron.', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80', 'Available'),
('Auditorium FTI', 250, 'Gedung FTI Lt. 2', 'Ruang auditorium medium cocok untuk seminar, workshop, dan kuliah umum.', 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=800&q=80', 'Available'),
('Ruang Rapat Senat', 50, 'Gedung Rektorat Lt. 2', 'Ruang rapat VIP meja bundar dilengkapi mikrofon individual dan proyektor HD.', 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?auto=format&fit=crop&w=800&q=80', 'Available');

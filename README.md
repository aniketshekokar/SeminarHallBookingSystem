# 🎓 Seminar Hall Booking System

A **web-based application** that allows users to book seminar halls efficiently and helps administrators manage hall bookings seamlessly.  
Built with **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**, this project provides an easy-to-use interface for students and admins alike.

---

## 🚀 Features

### 👤 User Module
- User registration and login with secure password hashing  
- View available halls with details like location and capacity  
- Book seminar halls for a specific date and time  
- View personal booking history and status updates  

### 🛠️ Admin Module
- Secure admin login  
- View, approve, or reject booking requests  
- Add, edit, and delete hall information  
- Manage all users and bookings in one dashboard  

---

## 🧩 Tech Stack

| Category | Technology |
|-----------|-------------|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP (Procedural) |
| Database | MySQL |
| Styling | Custom CSS (Gradient & Modern UI) |
| Hosting | Localhost (XAMPP / WAMP) |

---

## 🗂️ Project Structure
SeminarHallBookingSystem/
│
├── css/ # Stylesheets
├── includes/ # PHP helper files (database connection, functions, etc.)
├── index.php # Homepage
├── login.php # User login page
├── register.php # User registration
├── dashboard.php # User dashboard
├── admin_dashboard.php # Admin dashboard
├── book_hall.php # Booking form
├── logout.php # Logout logic
├── database.sql # Database structure and sample data
└── README.md # Project documentation


---

## ⚙️ Installation Steps

Follow these simple steps to run the project locally:

### 1️⃣ Prerequisites
- [XAMPP](https://www.apachefriends.org/download.html) or WAMP server
- PHP 7.4+ and MySQL
- Web browser (Chrome / Edge / Firefox)
- Git (optional, for version control)

### 2️⃣ Setup Instructions

1. **Copy the Project to XAMPP:**

2. **Start Apache and MySQL** in XAMPP Control Panel.

3. **Import Database:**
- Open [phpMyAdmin](http://localhost/phpmyadmin)
- Create a new database named `seminar_booking`
- Click **Import → Choose File → Select `database.sql` → Go**

4. **Create an Admin Account:**
Run the following SQL (use your own email if needed):

```sql
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'aniketshekokar92@gmail.com', '$2y$10$UsYip5HwCvlbim64n22hP.3ZcpFP5fA0UbqQ2pn7z.EIUk8WvBhaS', 'admin');


---

## 🗃️ 2️⃣ Sample `database.sql` File

Create another file in your project root:  
📄 `database.sql`

Paste this inside:

```sql
-- Database: seminar_booking

CREATE DATABASE IF NOT EXISTS seminar_booking;
USE seminar_booking;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------
-- Table structure for halls
-- ----------------------------
CREATE TABLE halls (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hall_name VARCHAR(100) NOT NULL,
  location VARCHAR(100),
  capacity INT,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------
-- Table structure for bookings
-- ----------------------------
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  hall_id INT,
  event_title VARCHAR(100),
  booking_date DATE,
  time_slot VARCHAR(50),
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (hall_id) REFERENCES halls(id) ON DELETE CASCADE
);

-- ----------------------------
-- Sample data
-- ----------------------------
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@example.com', '$2y$10$UsYip5HwCvlbim64n22hP.3ZcpFP5fA0UbqQ2pn7z.EIUk8WvBhaS', 'admin');

INSERT INTO halls (hall_name, location, capacity, description)
VALUES 
('Seminar Hall A', 'First Floor', 100, 'Air-conditioned hall with projector.'),
('Seminar Hall B', 'Second Floor', 80, 'Medium-size hall suitable for workshops.'),
('Main Auditorium', 'Ground Floor', 300, 'Large hall for college events.');




# Premium Attendance Management Hub

A premium, feature-rich, and secure Web-based Attendance Management System designed for educational institutions and organizations. The system features dynamic QR Code generation with time limits, GPS Geofencing, Excel roster import/export capabilities, and a modern, responsive Light SaaS user interface.

---

## 🚀 Key Features

### 1. Secure QR Code Check-in with Expiration Limits
* **Dynamic QR Codes**: Generates real-time attendance QR codes per class session.
* **Scan Expiration Durations**: Teachers can set validity periods (1, 2, 5, 10, 15 minutes, or No Limit).
* **Live Timer & Fullscreen Presentation**: Interactive countdown timer displays on both the presentation overlay and the student scanning screens.
* **Anti-tamper Verification**: Server-side timestamp check against the server clock prevents students from manipulating their local device time to check in late.

### 2. Location-Based GPS Geofencing
* **Campus Lock**: Restricts student attendance registration to physical campus coordinates.
* **Precision Distance Math**: Uses the **Haversine formula** to calculate distance between school GPS coordinates and the student's mobile device GPS.
* **Anti-Spoofing privacy**: Suppresses precise GPS metadata from students, displaying only status logs to prevent coordinate manipulation.
* **Geofence Controls**: Teachers can update school coordinates and radius limit directly in the settings control panel.

### 3. Excel & CSV Integrations
* **Smart Bulk Import**: Upload class rosters instantly using `.xlsx`, `.xls`, or `.csv` files.
* **Native Excel Template Generator**: Built-in client-side template download powered by SheetJS, preserving Khmer Unicode naming structures perfectly.
* **Monthly Attendance Exports**: Download beautifully formatted, color-coded monthly attendance spreadsheets with a full summary index.
* **Excel Freeze Panes**: Native sheet styling ensures that the downloaded Excel files open with header rows and student names frozen in place.

### 4. Real-time Dashboard & Control Panel
* **Live Syncing**: Real-time JavaScript polling updates the teacher's roll call sheet instantly as students scan their QR codes.
* **Manual Override**: Allows manual roll call entries for Present, Late, Absent, or Excused markers.
* **Sticky Header & Freeze Panes**: Column headers and student names stay fixed on the screen while scrolling vertically or horizontally, providing a seamless mobile experience.
* **Re-indexing Utility**: Safely reset and re-sequence student ID indexes starting from 1 without losing historical attendance records.

---

## 🛠️ Technology Stack
* **Backend**: PHP 8.0+ (OOP & MVC modular structuring)
* **Database**: MySQL (using optimized MySQLi prepared statements)
* **Frontend**: HTML5, Vanilla CSS (Premium HSL-tailored SaaS styling), JavaScript, jQuery, Bootstrap 5
* **Libraries**: SheetJS (XLSX template generator), SimpleXLSXReader (server-side Excel processor), QRCode JS generator

---

## 📦 Local Installation Guide

### Prerequisites
Make sure you have a local web server environment installed:
* [Laragon](https://laragon.org/) (Recommended for Windows)
* [XAMPP](https://www.apachefriends.org/)
* PHP 8.0 or higher
* MySQL 5.7+ / MariaDB 10.3+

### Step-by-Step Setup

1. **Clone the Repository**
   Place the cloned repository folder into your web server's root directory (e.g. `C:\laragon\www\` or `C:\xampp\htdocs\`).
   ```bash
   git clone https://github.com/Sovanvichea-debug/Attendance_System.git php-attendance
   ```

2. **Database Configuration**
   * Open your database management tool (such as phpMyAdmin or database client) and create a new database:
     ```sql
     CREATE DATABASE attendance_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   * Import the SQL schema file located inside the `db/` folder:
     ```bash
     db/attendance_db.sql
     ```

3. **Set Up Connection Credentials**
   * Edit [db-connect.php](file:///d:/laragon/www/php-attendance/db-connect.php) and adjust your database connection credentials:
     ```php
     <?php
     $host = "localhost";
     $username = "root";
     $password = "your_password";
     $dbname = "attendance_db";
     
     $conn = new mysqli($host, $username, $password, $dbname);
     if($conn->connect_error){
         die("Database connection failed: " . $conn->connect_error);
     }
     ```

4. **Access the System**
   * **Teacher Admin Panel**: Access the app in your browser at `http://localhost/php-attendance`.
     * *Default Credentials*:
       * **Username**: `admin`
       * **Password**: `admin123`
   * **Student QR Scan View**: Students scan QR codes which redirect to `http://localhost/php-attendance/scan.php`.

---

## 🔒 Security Recommendations
1. **SSL/HTTPS**: The HTML5 Geolocation API requires a secure connection (`https://`) to request GPS permissions in modern mobile browsers. Ensure your production site is loaded via SSL.
2. **Database Security**: Ensure that the database credentials in `db-connect.php` are secure and change the default admin password in the Profile settings immediately after installation.

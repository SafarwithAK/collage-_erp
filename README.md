# College ERP Management System (PHP & MySQL)

A web-based **College ERP (Enterprise Resource Planning) System** developed using **PHP, MySQL, HTML, CSS, JavaScript, and Tailwind CSS**. This project is designed for colleges to manage students, staff, courses, attendance, and academic data in a centralized system.

---

## 🚀 Key Features

### 👨‍💼 Admin Module

* Admin login & logout
* Dashboard with statistics
* Manage students (Add / View / Update)
* Manage teachers & staff
* Course & subject management
* View reports

### 👨‍🏫 Teacher Module

* Teacher login & dashboard
* Manage student attendance
* Upload marks / results
* View assigned classes & subjects

### 🎓 Student Module

* Student login & dashboard
* View personal profile
* Check attendance
* View marks & academic details

### 🗂 General Features

* Role-based access control (Admin / Teacher / Student)
* Secure session handling
* Clean & responsive UI
* MySQL database integration

---

## 🛠 Tech Stack

* **Frontend:** HTML, CSS, JavaScript, Tailwind CSS
* **Backend:** PHP (Core PHP)
* **Database:** MySQL
* **Server:** Apache (XAMPP / WAMP / LAMP)

---

## 📁 Project Structure

```
COLLEGE_ERP/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── students.php
│   ├── teachers.php
│   └── db.php
│
├── teacher/
│   ├── dashboard.php
│   ├── attendance.php
│   ├── marks.php
│   ├── login.php
│   └── db.php
│
├── student/
│   ├── dashboard.php
│   ├── profile.php
│   ├── attendance.php
│   ├── results.php
│   └── login.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── database.sql
└── README.md
```

---

## ⚙️ Installation & Setup

### 1️⃣ Requirements

* XAMPP / WAMP / LAMP Server
* PHP 7.x or above
* MySQL Database

### 2️⃣ Project Setup

1. Extract the project zip file
2. Copy the folder into:

   ```
   C:/xampp/htdocs/
   ```
3. Start **Apache** and **MySQL** services

### 3️⃣ Database Configuration

1. Open phpMyAdmin:

   ```
   http://localhost/phpmyadmin
   ```
2. Create a database (example: `college_erp`)
3. Import:

   ```
   database.sql
   ```
4. Configure database connection in all `db.php` files:

```php
$conn = mysqli_connect("localhost", "root", "", "college_erp");
```

---

## ▶️ How to Run

* **Admin Panel:**

  ```
  http://localhost/COLLEGE_ERP/admin/login.php
  ```

* **Teacher Panel:**

  ```
  http://localhost/COLLEGE_ERP/teacher/login.php
  ```

* **Student Panel:**

  ```
  http://localhost/COLLEGE_ERP/student/login.php
  ```

---

## 🔐 Security Notes

* Uses PHP sessions for authentication
* Recommended improvements for production:

  * Password hashing (`password_hash()`)
  * Prepared statements (SQL Injection prevention)
  * Input validation & CSRF protection

---

## 🎓 Academic Use

* B.Tech / BCA / MCA Major or Mini Project
* PHP & MySQL Practice Project
* College ERP Demo System

---

## 📌 Future Enhancements

* Online fee payment module
* Library management system
* Timetable & exam scheduling
* Notice board & announcements
* REST API integration

---

## 👨‍💻 Author

**Ajit Kumar**
B.Tech (Computer Science) Student
Web Developer | PHP | MySQL | JavaScript

---

## 📄 License

This project is developed for **educational purposes only**.
Free to use, modify, and enhance for learning.

---

⭐ If you find this project useful, feel free to star or fork it!
🏠 Student Accommodation Website (CampusStay)

A responsive, dynamic full-stack web application built for students to discover, filter, and shortlist Paying Guest (PG) accommodations seamlessly.

---

✨ Features
* **Dynamic Search & Filtering:** Filter properties by City, Gender, and Maximum Budget dynamically using **AJAX** (No page reloads!).
* **Detailed Property View:** View individual property specs including images, ratings, price, description, and available amenities.
* **Real-time Shortlist System:** Instant shortlist/mark-as-interested feature powered by AJAX backend toggles.
* **React Widget Integration:** Dedicated shortlist panel integrated using CDN-based React components.

---

 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (ES6+), React.js (CDN)
* **Backend:** PHP (Native)
* **Database:** MySQL
* **Asynchronous Calls:** AJAX / Fetch API

---

🗄️ Database Structure (`schema.sql`)
The database `student_accommodation` consists of 5 normalized tables:
1. `users` — Stores student user accounts.
2. `properties` — Stores PG listings, pricing, ratings, and image links.
3. `amenities` — List of available facilities (WiFi, AC, Food, etc.).
4. `property_amenities` — Junction table linking properties and amenities.
5. `interested_users` — Tracks shortlisted PGs for users.

---
 🚀 How to Run Locally

1. **Clone/Download** this repository into your XAMPP `htdocs` folder:
   `C:\xampp\htdocs\student_accommodation\`
2. Start **Apache** and **MySQL** in XAMPP Control Panel.
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and create a database named `student_accommodation`.
4. Import the `schema.sql` file into the database.
5. Open your browser and navigate to:
   `http://localhost/student_accommodation/index.php`

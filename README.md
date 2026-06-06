# toyshop_final  

A simple PHP‑based e‑commerce prototype for a toy shop. It includes a public storefront, user authentication, a shopping‑cart system, and an admin panel for managing categories, gifts, orders, and user feedback.

---

## Overview  

`toyshop_final` demonstrates core concepts of a web‑based shop:

* **Public pages** – home, product listings, contact & feedback forms.  
* **User accounts** – login / logout with session handling.  
* **Shopping cart** – add items to cart, persist across pages.  
* **Admin dashboard** – secure area for managing categories, gifts, orders, and responding to user feedback.  

The project is intentionally lightweight, making it ideal for learning PHP, MySQL, and basic MVC‑style organization.

---

## Features  

| Feature | Description |
|---------|-------------|
| **Product catalog** | Categories and gifts are stored in MySQL and displayed dynamically. |
| **Shopping cart** | `add_to_cart.php` handles adding items; cart data is kept in the session. |
| **User authentication** | Simple login (`login.php`) and logout (`logout.php`) with password hashing. |
| **Admin panel** | Secure area (`admin/`) with CRUD operations for categories, gifts, and orders. |
| **Feedback system** | Users can submit feedback (`feedback.php`); admins can view and reply (`admin/admin_feedback.php`). |
| **Responsive styling** | Minimal CSS (`css/style.css`) for a clean, mobile‑friendly UI. |
| **Database dump** | `Database/toyshop_db.sql` contains the schema and sample data. |

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3 (no JS frameworks) |
| **Server** | Apache (or any server supporting PHP) |
| **Version control** | Git |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/your-username/toyshop_final.git
   cd toyshop_final
   ```

2. **Create a MySQL database**  

   ```sql
   CREATE DATABASE toyshop;
   ```

3. **Import the schema & sample data**  

   ```bash
   mysql -u your_user -p toyshop < Database/toyshop_db.sql
   ```

4. **Configure database connection**  

   Edit `config.php` (and `admin/config.php` if you prefer a separate admin config) and replace the placeholder values with your credentials:

   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'toyshop');
   define('DB_USER', 'YOUR_DB_USERNAME');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   ```

5. **Set up a web server**  

   *Place the project folder inside your web‑root (e.g., `htdocs` for XAMPP or `www` for WAMP).  
   *Ensure the `admin/uploads/` directory is writable for image uploads.

6. **Adjust file permissions (optional)**  

   ```bash
   chmod -R 755 admin/uploads
   ```

7. **Start the server**  

   ```bash
   # Using the built‑in PHP server (development only)
   php -S localhost:8000
   ```

   Then open `http://localhost:8000/index.php` in a browser.

---

## Usage  

### Public side  

| Action | Path |
|--------|------|
| Browse home page | `index.php` |
| View product listings | `home.php` |
| Add a gift to cart | `add_to_cart.php?gift_id=XX` |
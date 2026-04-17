# Lavantine Eatery

A full stack restaurant ordering web application built with PHP, MySQL, JavaScript, HTML, and CSS.

## Team Members
- Razan Shams
- Zeinab Zeine

## Project Overview
Lavantine Eatery allows customers to browse a Lebanese-inspired menu, add items to a cart, and place orders online. Restaurant staff can log into a secure admin panel to manage the menu and track incoming orders.

## Tech Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Server:** Apache (XAMPP)

## Setup Instructions

### Requirements
- XAMPP (Apache + MySQL)
- A modern web browser (Chrome, Firefox, Edge)

### Steps

**1. Install XAMPP**
- Download from https://www.apachefriends.org
- Install and open the XAMPP Control Panel
- Start **Apache** and **MySQL**

**2. Clone the project**
- Clone or download this repository
- Copy the project folder into `C:\xampp\htdocs\`
- It should be at: `C:\xampp\htdocs\Lavantine-Eatery\`

**3. Import the database**
- Open your browser and go to `http://localhost/phpmyadmin`
- Click **New** in the left sidebar
- Name it `restaurant_db` and click **Create**
- Click on `restaurant_db` in the left sidebar
- Click the **Import** tab
- Click **Choose File** and select `restaurant_db.sql` from the project folder
- Click **Go**

**4. Open the application**
- Open your browser and go to `http://localhost/Lavantine-Eatery`
- You should see the home page!

## Admin Access
- URL: `http://localhost/Lavantine-Eatery/admin/admin_login.php`
- Username: `admin`
- Password: `password`

## Project Structure
Lavantine-Eatery/
├── admin/
│   ├── admin_login.php   # Secure admin login page
│   ├── dashboard.php     # Admin dashboard (CRUD)
│   └── logout.php        # Destroys session and redirects
├── css/
│   ├── index.css         # Home page styles
│   ├── menu.css          # Menu page styles
│   ├── order.css         # Order cart styles
│   ├── checkout.css      # Checkout page styles
│   └── admin.css         # Admin panel styles
├── includes/
│   ├── db.php            # Database connection
│   └── nav.php           # Shared navigation bar
├── js/
│   ├── index.js          # Slideshow functionality
│   ├── menu.js           # Search and filter
│   ├── cart.js           # Live cart updates
│   └── admin.js          # Admin edit form
├── images/               # Food images
├── index.php             # Home page
├── menu.php              # Menu page
├── order.php             # Order cart page
├── checkout.php          # Checkout page
├── update_cart.php       # Handles cart AJAX updates
└── restaurant_db.sql     # Database dump

## Database Tables
- `menu_items` — stores all menu items
- `orders` — stores customer orders
- `order_items` — stores items within each order
- `admins` — stores admin login credentials
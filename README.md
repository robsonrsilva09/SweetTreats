﻿# SweetTreats
Sweet Treats Bakery - Project README
1. Project Overview
Sweet Treats Bakery is a dynamic, database-driven website built as a practical assessment for the Dynamic Website Development module. The application serves as a professional online presence for a local bakery, allowing customers to view products and leave feedback, while providing a secure and comprehensive administration panel for the owner to manage all aspects of the site content.

The project is built from the ground up using core web technologies, focusing on secure backend logic, a clear separation of concerns, and a user-friendly interface for both customers and administrators.

2. Core Technologies
This project was built strictly using the technologies covered during the semester, without relying on external frameworks.

Frontend: HTML5, CSS3 (utilising Flexbox and Grid for layout)

Backend: PHP (procedural approach)

Database: MySQL (managed via phpMyAdmin)

Server Environment: XAMPP (Apache Server)

3. Key Features
The website is divided into two main sections: the public-facing customer area and the secure administrator dashboard.

3.1. Public-Facing Features
These features are accessible to all visitors.

Homepage / Full Product Catalogue (index.php)

Displays all 15 products from the bakery's catalogue in a responsive 5-column grid.

Each product card shows an image, name, description, and price.

Search Functionality: A search bar allows users to filter the product list by name in real-time. The search is processed securely on the backend to prevent SQL injection. A "Clear Search" button is provided to easily return to the full catalogue view.

Daily Specials Page (daily_menu.php)

Displays up to 3 products designated as the "specials for the day".

Calculates and displays a discounted price, showing the original price struck through for marketing appeal.

Persistent Offers Logic: If no specials are set for the current day, the page intelligently displays the most recent day's offers, ensuring the page is never empty and reducing the daily management burden on the admin.

Feedback Form (feedback.php)

A simple form for customers to submit their name, email, and feedback.

Server-Side Validation: The form uses robust PHP validation (filter_var) to ensure a correctly formatted email address is submitted.

User-Friendly Error Handling: If validation fails, the user is redirected back to the form with a clear error message, and the data they had already entered is pre-filled to avoid re-typing.

3.2. Administrator Features
This section is protected and requires a login.

Secure Admin Authentication

Login (admin_login.php): A secure login form that uses prepared statements to prevent SQL injection. Passwords are checked against securely hashed values in the database using password_verify().

Session Management: Uses PHP sessions ($_SESSION) to track login status. Protected pages will redirect unauthorised users back to the login page.

Hierarchical Admin Roles

Super-Admin vs. Admin: The system supports two user roles. The first user to register automatically becomes a superadmin. All subsequent users are regular admin accounts.

Approval System: New admin accounts are created in a "pending" state (is_approved = 0) and must be manually approved by a superadmin before they can log in.

Admin Dashboard (admin_dashboard.php)

A central control panel with a modern, responsive grid layout.

Displays different management options based on the logged-in user's role (only a superadmin can see the "Manage Administrators" option).

Product Management (manage_products.php)

Full CRUD Functionality: Admins can Create, Read, Update, and Delete any product in the catalogue.

Image Uploads: The "Add" and "Edit" forms allow for image uploads. The system handles file saving and deletes the old image file from the server when a new one is uploaded.

Custom Display Ordering: Admins can set a numerical display order for products. This custom order is reflected on the public homepage, giving the admin full control over the product layout.

Dynamic Table Sorting: The admin view itself has clickable table headers, allowing the admin to sort the product list by name, price, or date for their own convenience without affecting the public view.

Daily Specials Management (manage_daily_menu.php)

An intuitive interface for setting the daily specials.

Features a date selector. When a date is chosen, the form automatically pre-fills with any specials already set for that day, making editing easy.

Admins can select up to 3 products and apply a unique discount percentage to each.

Administrator Management (Super-Admin Only)

manage_admins.php: A secure page where a superadmin can view all admin accounts.

Approve/Delete: The super-admin can approve pending accounts with a single click or delete existing admin accounts. A security check prevents a super-admin from deleting their own account.

Feedback Viewer (view_feedback.php)

A protected page that displays all customer feedback in a clean, card-based layout, with the most recent submissions appearing first.

Privacy-Respecting: As per the requirements, this page displays the customer's name and feedback but correctly hides their email address.

4. Setup & Installation
To run this project locally, follow these steps:

Server Environment: Ensure you have XAMPP installed and the Apache and MySQL services are running.

Database Setup:

Open phpMyAdmin.

Create a new database named sweet_treats_db.

Import the provided .sql file to create all necessary tables and populate the product list.

File Placement:

Place the entire project folder (sweet_treats/) inside your XAMPP htdocs directory.

Create the 'uploads' Folder:

Inside the sweet_treats/ folder, create a new, empty folder named uploads. The web server needs permission to write to this folder.

First Admin User:

Navigate to http://localhost/sweet_treats/register_admin.php in your browser.

Create the first superadmin account.

Access the Site:

The public site is available at(https://studyrbs.com.br/sweettreats/index.php).


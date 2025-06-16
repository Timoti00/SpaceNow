# Space Now

**Space Now** is a web-based room reservation system that streamlines the process of booking and managing room availability within a building. The platform supports three main user roles: **Students**, **Lecturers**, and **Admins**.

---

## 🚀 Features

### 👤 Role-Based Access
- **Admin**
  - Manage master data: Floors, Rooms, Users.
  - Map rooms to specific floors for a visual layout.
  - Approve or reject room booking requests.
  
- **Students & Lecturers**
  - View room layout and availability by floor.
  - Submit room booking requests.
  - Wait for admin approval.

### 🛠️ Master Data Management
Admins have full control over the core data of the application:
- **Floors** – Define levels in the building.
- **Rooms** – Assign rooms to floors and manage room information.
- **Users** – Manage accounts and roles.

### 🗺️ Visual Booking Interface
- Floor-wise interactive room layout.
- Easy room selection and booking.

### 🔄 Semi Real-Time Availability
- Uses **AJAX polling** to keep room availability status up-to-date.
- Ensures users always see the latest room status without refreshing the page.

## 💡 Technologies Used
- Laravel (Back-end)
- AJAX (Real-time updates)
- Blade (Front-end)
- MySQL (Database)
- Template(sb-admin 2)
  https://startbootstrap.com/theme/sb-admin-2

---

# Laravel Project Installation Guide

## Prerequisites

Make sure you have the following installed on your system:

-   **PHP >= 8.1**  
     You can check your version using:
    ```bash
    php -v
    **omposer Requirements & Setup for Laravel**
    ```

## What is Composer?

Composer is a PHP dependency manager used to manage and install libraries required by your Laravel application.

Official site: [https://getcomposer.org](https://getcomposer.org)

Make sure Composer is installed on your machine:

-   ```bash
    composer -V
    ```

---
## How to install?
```bash
# 1. Clone the repository
git clone https://github.com/Timoti00/SpaceNow.git
cd repository

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Set environment variables in .env (edit manually if needed)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=space_now
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Generate app key
php artisan key:generate

# 6. Create storage link
php artisan storage:link

# 7. Migrate & seed database
php artisan migrate:fresh --seed

# 8. Start Laravel development server
php artisan serve
```

## Accounts

Default Account:

-   **Role Admin**  
    Username: admin@example.com
    Password: password
-   **Role User**  
    Username: user@example.com
    Password: password



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



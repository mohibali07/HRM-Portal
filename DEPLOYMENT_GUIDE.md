# Deployment Guide: Hostinger

This guide will walk you through deploying your CodeIgniter application to Hostinger.

## Prerequisites

- Access to your Hostinger hPanel.
- A domain name (or subdomain) set up on Hostinger.
- Your project files (which we will zip).

## Step 1: Prepare Your Files

1.  **Zip your project files**:

    - Go to your project folder: `d:\xampp\htdocs\hrm`
    - Select all files and folders **EXCEPT** `.git`, `.gitignore`, `.editorconfig`, and `contributing.md` (optional).
    - Create a ZIP archive named `hrm_project.zip`.

2.  **Export your Local Database**:
    - Open **phpMyAdmin** on your local machine (`http://localhost/phpmyadmin`).
    - Select your database (`hrm_db`).
    - Click the **Export** tab.
    - Click **Go** to download the `.sql` file (e.g., `hrm_db.sql`).

## Step 2: Create Database on Hostinger

1.  Log in to **Hostinger hPanel**.
2.  Go to **Databases** -> **Management**.
3.  Create a new MySQL Database:
    - **Database Name**: (e.g., `u123456789_hrm`) - _Note this down!_
    - **Database User**: (e.g., `u123456789_admin`) - _Note this down!_
    - **Password**: Choose a strong password - _Note this down!_
4.  Click **Create**.

## Step 3: Upload Files

1.  Go to **Files** -> **File Manager** in hPanel.
2.  Navigate to `public_html`.
    - If you are deploying to a subdomain, navigate to that folder (e.g., `public_html/hrm`).
3.  **Upload** your `hrm_project.zip` file.
4.  Right-click the zip file and select **Extract**.
    - Extract it to the current directory (`.` or leave blank).
5.  Delete the `hrm_project.zip` file after extraction.

## Step 4: Import Database

1.  Go to **Databases** -> **phpMyAdmin** in hPanel.
2.  Click **Enter phpMyAdmin** for the database you created in Step 2.
3.  Click the **Import** tab.
4.  Choose your exported `.sql` file (`hrm_db.sql`).
5.  Click **Go**.

## Step 5: Configure Application

You need to update two files on the server using the File Manager's **Edit** feature.

### 1. Update `application/config/database.php`

Open `application/config/database.php` and update the `default` array with your Hostinger database details:

```php
$db['default'] = array(
    'dsn'   => '',
    'hostname' => 'localhost', // Usually 'localhost' on Hostinger
    'username' => 'u123456789_admin', // Your Hostinger DB User
    'password' => 'YourStrongPassword', // Your Hostinger DB Password
    'database' => 'u123456789_hrm', // Your Hostinger DB Name
    // ... leave the rest as is
);
```

### 2. Update `application/config/config.php`

Open `application/config/config.php` and update the `base_url`:

```php
// Replace with your actual domain or subdomain
$config['base_url'] = 'https://yourdomain.com/';
// OR
$config['base_url'] = 'https://hrm.yourdomain.com/';
```

## Step 6: Final Checks

1.  Visit your website URL.
2.  **Login**: Try logging in with your admin credentials.
3.  **Check Pages**: Navigate to a few pages to ensure links are working.

    - If you get 404 errors on inner pages, ensure the `.htaccess` file was uploaded correctly. It should look like this:

    ```apache
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase /
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^(.*)$ index.php/$1 [L]
    </IfModule>
    ```

## Troubleshooting

- **Database Error**: Double-check your username, password, and database name in `database.php`. Ensure the user is assigned to the database.
- **Blank Page**: Check `application/config/config.php` and ensure `$config['log_threshold'] = 1;` to see errors in `application/logs`.
- **404 Not Found**: Verify `.htaccess` exists and `mod_rewrite` is enabled (it is by default on Hostinger).

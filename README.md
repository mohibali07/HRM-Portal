# HRM System

## Project Overview

This is a Human Resource Management (HRM) System built with CodeIgniter 3. It provides a comprehensive solution for managing employees, payroll, attendance, leaves, and more.

## Features

- **Dashboard**: Overview of company stats.
- **Employees**: Manage employee profiles, documents, and qualifications.
- **Payroll**: Manage salaries, payslips, and payment history.
- **Attendance**: Track employee attendance, clock-in/out.
- **Leaves**: Manage leave applications and types.
- **Recruitment**: Manage job posts and candidates.
- **Organization**: Manage departments, designations, and policies.
- **Settings**: Configure system settings, email templates, and database backups.

## Technical Details

- **Framework**: CodeIgniter 3 (PHP)
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript (jQuery, Bootstrap)

## Installation

1.  **Clone/Download**: Place the project in your web server's root directory (e.g., `htdocs` or `www`).
2.  **Database Setup**:
    - Create a MySQL database (e.g., `hrm_db`).
    - Import the provided SQL file (if available) or run migrations.
    - Update `application/config/database.php` with your database credentials.
3.  **Configuration**:
    - Update `application/config/config.php` with your `base_url`.
4.  **Run**: Access the application via your browser (e.g., `http://localhost/hrm/`).

## Folder Structure

- `application/`: Core application code.
  - `config/`: Configuration files.
  - `controllers/`: Controllers handling requests.
  - `models/`: Database interaction logic.
  - `views/`: HTML templates.
- `system/`: CodeIgniter framework core.
- `uploads/`: Directory for uploaded files (images, documents).
- `skin/`: CSS, JS, and image assets.

## Usage

- **Login**: Use the login page to access the admin panel.
- **Admin Panel**: Navigate through the sidebar to access different modules.
- **Settings**: Go to the Settings page to configure company info and system preferences.

## Recent Updates

- **Security**: Disabled deprecated global XSS filtering.
- **Backups**: Improved database backup functionality using CodeIgniter's `dbutil`.
- **Code Quality**: Refactored models to use Query Builder for better security and consistency.

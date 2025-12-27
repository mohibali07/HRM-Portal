<?php
/**
 * Quick Database Setup Script for ZKTeco Integration
 * Run this file once via browser: http://localhost/hrm/create_zkteco_tables.php
 * Then delete this file for security
 */

// Database configuration - Update these if needed
$host = 'localhost';
$username = 'hrm_secure';
$password = 'HRM_Secure_Pass_2025!';
$database = 'hrm_db';

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Creating ZKTeco Integration Tables...</h2>";

// SQL statements
$sql_statements = [
    "CREATE TABLE IF NOT EXISTS `xin_zkteco_settings` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `device_ip` varchar(50) NOT NULL,
      `device_port` int(11) NOT NULL DEFAULT '4370',
      `last_sync` datetime DEFAULT NULL,
      `created_at` datetime DEFAULT NULL,
      `updated_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    
    "CREATE TABLE IF NOT EXISTS `xin_zkteco_employee_mapping` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `zk_user_id` varchar(50) NOT NULL,
      `employee_id` int(11) NOT NULL,
      `zk_name` varchar(255) DEFAULT NULL,
      `created_at` datetime DEFAULT NULL,
      `updated_at` datetime DEFAULT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `zk_user_id` (`zk_user_id`),
      KEY `employee_id` (`employee_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
    
    "CREATE TABLE IF NOT EXISTS `xin_zkteco_sync_log` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `status` varchar(20) NOT NULL DEFAULT 'success',
      `message` text NOT NULL,
      `records_synced` int(11) NOT NULL DEFAULT '0',
      `errors` text DEFAULT NULL,
      `sync_date` datetime NOT NULL,
      PRIMARY KEY (`id`),
      KEY `sync_date` (`sync_date`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
];

$success_count = 0;
$error_count = 0;

foreach ($sql_statements as $index => $sql) {
    $table_name = '';
    if (strpos($sql, 'xin_zkteco_settings') !== false) {
        $table_name = 'xin_zkteco_settings';
    } elseif (strpos($sql, 'xin_zkteco_employee_mapping') !== false) {
        $table_name = 'xin_zkteco_employee_mapping';
    } elseif (strpos($sql, 'xin_zkteco_sync_log') !== false) {
        $table_name = 'xin_zkteco_sync_log';
    }
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>✓ Table '{$table_name}' created successfully</p>";
        $success_count++;
    } else {
        echo "<p style='color: red;'>✗ Error creating table '{$table_name}': " . $conn->error . "</p>";
        $error_count++;
    }
}

$conn->close();

if ($success_count == 3) {
    echo "<h3 style='color: green;'>All tables created successfully!</h3>";
    echo "<p><a href='" . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/zkteco/settings'>Go to ZKTeco Settings</a></p>";
    echo "<p style='color: red;'><strong>IMPORTANT: Delete this file (create_zkteco_tables.php) for security!</strong></p>";
} else {
    echo "<h3 style='color: red;'>Some errors occurred. Please check the errors above.</h3>";
}


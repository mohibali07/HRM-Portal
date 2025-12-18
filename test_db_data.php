<?php
$hostname = "localhost";
$username = "hrm_secure";
$password = "HRM_Secure_Pass_2025!";
$database = "hrm_db";

// Create a connection
$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected successfully\n";

// Check xin_countries
$result = $mysqli->query("SELECT count(*) as count FROM xin_countries");
if ($result) {
    $row = $result->fetch_assoc();
    echo "xin_countries count: " . $row['count'] . "\n";
} else {
    echo "Error querying xin_countries: " . $mysqli->error . "\n";
}

// Check xin_document_type
$result = $mysqli->query("SELECT count(*) as count FROM xin_document_type");
if ($result) {
    $row = $result->fetch_assoc();
    echo "xin_document_type count: " . $row['count'] . "\n";
} else {
    echo "Error querying xin_document_type: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
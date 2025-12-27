<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=hrm_db", "hrm_secure", "HRM_Secure_Pass_2025!");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SHOW COLUMNS FROM xin_zkteco_logs");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
<?php
$host = 'localhost';
$db = 'hrm_db';
$user = 'hrm_secure';
$pass = 'HRM_Secure_Pass_2025!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Check existing
    $stmt = $pdo->query("SELECT * FROM xin_email_template");
    $results = $stmt->fetchAll();

    echo "Existing templates: " . count($results) . "\n";
    foreach ($results as $row) {
        echo "ID: " . $row['template_id'] . " - " . $row['name'] . "\n";
    }

    // Insert if missing
    $stmt = $pdo->prepare("SELECT count(*) FROM xin_email_template WHERE template_id = ?");
    $stmt->execute([2]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        echo "Template 2 missing. Inserting...\n";
        $sql = "INSERT INTO xin_email_template (template_id, name, subject, message, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            2,
            'Forgot Password',
            'Password Reset Request',
            '<p>Hello {var username},</p><p>You recently requested to reset your password for your account. Your new password is: {var password}</p><p>If you did not request a password reset, please ignore this email or reply to let us know.</p><p>Thanks,</p><p>{var site_name}</p>',
            1
        ]);
        echo "Insert successful.\n";
    } else {
        echo "Template 2 already exists.\n";
    }

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
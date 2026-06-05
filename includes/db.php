<?php
// Update these values if your XAMPP MySQL username or password is different.
$host = 'localhost';
$dbName = 'college_course_management';
$dbUser = 'root';
$dbPass = '';

$dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    die('Database connection failed. Import database.sql and check includes/db.php.');
}

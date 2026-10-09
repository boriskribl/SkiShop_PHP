<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = '127.0.0.1';
$port = '3309'; // Eksplicitno podešavamo port 3307
$db   = 'ski_shop';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Spajamo host i port u ispravan DSN string
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Greška pri povezivanju sa bazom: " . $e->getMessage());
}

function proveriLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function proveriAdmin() {
    proveriLogin();
    if ($_SESSION['uloga'] !== 'admin') {
        header('Location: index.php');
        exit;
    }
}
?>

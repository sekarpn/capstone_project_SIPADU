<?php
// config/database.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menambahkan port=3307 pada host jika port MySQL diubah
$host = "127.0.0.1;port=3307";
$user = "root";
$pass = "";
$dbname = "meeting_room_booking";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage() . "<br>Pastikan XAMPP MySQL aktif dan database 'meeting_room_booking' telah di-import.");
}

function checkAuth($requiredRole = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
    if ($requiredRole && $_SESSION['user_role'] !== $requiredRole) {
        if ($_SESSION['user_role'] === 'ADMIN') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit();
    }
}
?>
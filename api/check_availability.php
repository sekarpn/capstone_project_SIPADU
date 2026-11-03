<?php
// api/check_availability.php
header('Content-Type: application/json');
require_once "../config/database.php";

$roomId = $_GET['room_id'] ?? 1;
$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? $startDate;
$sessionTime = $_GET['session_time'] ?? 'Full Day';

if (empty($startDate)) {
    echo json_encode(['available' => false, 'message' => 'Tanggal belum dipilih']);
    exit();
}

// Menentukan jam berdasarkan sesi
if ($sessionTime === 'Pagi') {
    $startTime = '08:00:00';
    $endTime = '12:00:00';
} elseif ($sessionTime === 'Siang') {
    $startTime = '13:00:00';
    $endTime = '17:00:00';
} else {
    $startTime = '08:00:00';
    $endTime = '17:00:00';
}

// Query untuk mengecek apakah ada jadwal bentrok yang statusnya APPROVED atau PENDING
$sql = "SELECT COUNT(*) FROM bookings 
        WHERE room_id = ? 
        AND status IN ('APPROVED', 'PENDING')
        AND booking_date BETWEEN ? AND ?
        AND (
            (start_time < ? AND end_time > ?)
        )";

$stmt = $pdo->prepare($sql);
$stmt->execute([$roomId, $startDate, $endDate, $endTime, $startTime]);
$conflictCount = $stmt->fetchColumn();

if ($conflictCount > 0) {
    echo json_encode([
        'available' => false, 
        'message' => 'Tanggal atau sesi waktu ini sudah dipesan/terisi. Silakan pilih tanggal/sesi lain.'
    ]);
} else {
    echo json_encode([
        'available' => true, 
        'message' => 'Auditorium tersedia pada rentang tanggal dan sesi ini!'
    ]);
}
?>
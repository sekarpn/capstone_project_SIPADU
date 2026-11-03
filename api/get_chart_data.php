<?php
// api/get_chart_data.php
header('Content-Type: application/json');
require_once "../config/database.php";

$year = date('Y');

// Mengambil jumlah peminjaman per bulan pada tahun berjalan
$sql = "SELECT MONTH(booking_date) as month_num, COUNT(*) as total 
        FROM bookings 
        WHERE YEAR(booking_date) = ? AND status = 'APPROVED'
        GROUP BY MONTH(booking_date)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$year]);
$results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Susun array 12 bulan (Jan - Des)
$monthlyData = [];
for ($m = 1; $m <= 12; $m++) {
    $monthlyData[] = (int)($results[$m] ?? 0);
}

echo json_encode([
    'success' => true,
    'year' => $year,
    'data' => $monthlyData
]);
?>
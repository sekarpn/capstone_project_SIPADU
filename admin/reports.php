<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Laporan - Admin";
$activePage = "reports";
$title = "Laporan Peminjaman";

$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-t');

$stmt = $pdo->prepare("SELECT b.*, r.name as room_name, u.name as user_name FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN users u ON b.user_id = u.id WHERE b.booking_date BETWEEN ? AND ? ORDER BY b.booking_date DESC");
$stmt->execute([$startDate, $endDate]);
$reports = $stmt->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <form method="GET" class="flex flex-col sm:flex-row items-end gap-4">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="<?= $startDate ?>" class="p-2 border rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="<?= $endDate ?>" class="p-2 border rounded-lg text-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i> Filter Laporan
                </button>
            </form>
        </div>

        <!-- Tabel Laporan -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Rekap Data Peminjaman Auditorium</h3>
                <button onclick="window.print()" class="px-3 py-1.5 border rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50 flex items-center gap-1">
                    <i data-lucide="printer" class="w-3.5 h-3.5"></i> Cetak Laporan
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-500">
                            <th class="p-3">No</th>
                            <th class="p-3">Kegiatan</th>
                            <th class="p-3">Auditorium</th>
                            <th class="p-3">Pemohon</th>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Waktu</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($reports as $i => $row): ?>
                            <tr>
                                <td class="p-3 font-semibold text-gray-500"><?= $i + 1 ?></td>
                                <td class="p-3 font-medium text-gray-900"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($row['room_name']) ?></td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($row['user_name']) ?></td>
                                <td class="p-3 text-gray-600"><?= $row['booking_date'] ?></td>
                                <td class="p-3 text-gray-600"><?= substr($row['start_time'], 0, 5) ?> - <?= substr($row['end_time'], 0, 5) ?></td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?= $row['status'] === 'APPROVED' ? 'bg-emerald-100 text-emerald-700' : ($row['status'] === 'PENDING' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
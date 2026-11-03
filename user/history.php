<?php
require_once "../config/database.php";
checkAuth('USER');

$pageTitle = "Peminjaman Saya - SIPADU";
$activePage = "history";
$title = "Peminjaman Saya";

$userId = $_SESSION['user_id'];
$statusFilter = $_GET['status'] ?? 'ALL';

$sql = "SELECT b.*, r.name as room_name, r.location FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ?";
$params = [$userId];

if ($statusFilter !== 'ALL') {
    $sql .= " AND b.status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY b.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$historyList = $stmt->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0 bg-gray-50">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <!-- Status Filter Tabs -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-500 mr-2">Filter Status:</span>
                <a href="history.php?status=ALL" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $statusFilter === 'ALL' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Semua</a>
                <a href="history.php?status=PENDING" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $statusFilter === 'PENDING' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Menunggu</a>
                <a href="history.php?status=APPROVED" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $statusFilter === 'APPROVED' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Disetujui</a>
                <a href="history.php?status=REJECTED" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition <?= $statusFilter === 'REJECTED' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">Ditolak</a>
            </div>
            <a href="book.php" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-semibold hover:bg-emerald-700 transition flex items-center gap-1.5">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajukan Peminjaman Baru
            </a>
        </div>

        <!-- Tabel Riwayat Peminjaman -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4">Daftar Pengajuan Peminjaman Saya</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-500 text-xs">
                            <th class="p-3">No</th>
                            <th class="p-3">Kegiatan & Detail</th>
                            <th class="p-3">Auditorium</th>
                            <th class="p-3">Tanggal & Sesi Pemakaian</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Catatan Verifikator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($historyList)): ?>
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-400">Belum ada riwayat pengajuan peminjaman pada kategori ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historyList as $idx => $item): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-semibold text-gray-500 text-xs"><?= $idx + 1 ?></td>
                                    <td class="p-3">
                                        <p class="font-bold text-gray-800 text-sm"><?= htmlspecialchars($item['title']) ?></p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?= htmlspecialchars($item['event_description'] ?? '-') ?></p>
                                    </td>
                                    <td class="p-3">
                                        <p class="font-semibold text-gray-800 text-xs"><?= htmlspecialchars($item['room_name']) ?></p>
                                        <p class="text-[11px] text-gray-400"><?= htmlspecialchars($item['location']) ?></p>
                                    </td>
                                    <td class="p-3 text-xs text-gray-600">
                                        <p class="font-semibold text-gray-800"><?= $item['booking_date'] ?></p>
                                        <span class="text-[11px] text-emerald-600 font-medium"><?= substr($item['start_time'], 0, 5) ?> - <?= substr($item['end_time'], 0, 5) ?> WIB</span>
                                    </td>
                                    <td class="p-3">
                                        <?php if ($item['status'] === 'APPROVED'): ?>
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Disetujui</span>
                                        <?php elseif ($item['status'] === 'PENDING'): ?>
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                                        <?php elseif ($item['status'] === 'REJECTED'): ?>
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-3 text-xs text-gray-500"><?= htmlspecialchars($item['rejection_reason'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
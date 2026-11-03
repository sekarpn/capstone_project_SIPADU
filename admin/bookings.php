<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Kelola Peminjaman - Admin";
$activePage = "bookings";
$title = "Kelola Peminjaman";

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = $_POST['booking_id'];
    $status = $_POST['status'];
    $reason = trim($_POST['rejection_reason'] ?? '');

    $stmt = $pdo->prepare("UPDATE bookings SET status = ?, rejection_reason = ? WHERE id = ?");
    $stmt->execute([$status, $reason, $bookingId]);
    $msg = "Status peminjaman berhasil diperbarui!";
}

$bookings = $pdo->query("SELECT b.*, r.name as room_name, u.name as user_name, u.nim_nip FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN users u ON b.user_id = u.id ORDER BY b.id DESC")->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <?php if ($msg): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Permohonan Peminjaman</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-500">
                            <th class="p-3">No</th>
                            <th class="p-3">Kegiatan</th>
                            <th class="p-3">Auditorium</th>
                            <th class="p-3">Pemohon (NIM/NIP)</th>
                            <th class="p-3">Tanggal & Waktu</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($bookings as $idx => $b): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-semibold text-gray-500"><?= $idx + 1 ?></td>
                                <td class="p-3">
                                    <p class="font-bold text-gray-800"><?= htmlspecialchars($b['title']) ?></p>
                                    <p class="text-xs text-gray-500 line-clamp-1"><?= htmlspecialchars($b['event_description'] ?? '-') ?></p>
                                </td>
                                <td class="p-3 font-medium text-gray-700"><?= htmlspecialchars($b['room_name']) ?></td>
                                <td class="p-3">
                                    <p class="font-medium text-gray-800"><?= htmlspecialchars($b['user_name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($b['nim_nip'] ?? '-') ?></p>
                                </td>
                                <td class="p-3 text-gray-600">
                                    <?= $b['booking_date'] ?><br>
                                    <span class="text-xs text-gray-500"><?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?></span>
                                </td>
                                <td class="p-3">
                                    <?php if ($b['status'] === 'APPROVED'): ?>
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Disetujui</span>
                                    <?php elseif ($b['status'] === 'PENDING'): ?>
                                        <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                                    <?php elseif ($b['status'] === 'REJECTED'): ?>
                                        <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-center">
                                    <?php if ($b['status'] === 'PENDING'): ?>
                                        <div class="flex items-center justify-center gap-2">
                                            <form method="POST">
                                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                                <input type="hidden" name="status" value="APPROVED">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700">Setujui</button>
                                            </form>
                                            <form method="POST">
                                                <input type="hidden" name="booking_id" value="<?= $b['id'] ?>">
                                                <input type="hidden" name="status" value="REJECTED">
                                                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700">Tolak</button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">Selesai Diverifikasi</span>
                                    <?php endif; ?>
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
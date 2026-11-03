<?php
require_once "../config/database.php";
checkAuth('USER');

$pageTitle = "Dashboard User - Auditorium Booking";
$activePage = "dashboard";
$title = "Dashboard Mahasiswa / User";

$userId = $_SESSION['user_id'];

// Hitung statistik khusus user ini
$myTotal = $pdo->query("SELECT COUNT(*) FROM bookings WHERE user_id = $userId")->fetchColumn();
$myPending = $pdo->query("SELECT COUNT(*) FROM bookings WHERE user_id = $userId AND status='PENDING'")->fetchColumn();
$myApproved = $pdo->query("SELECT COUNT(*) FROM bookings WHERE user_id = $userId AND status='APPROVED'")->fetchColumn();
$myRejected = $pdo->query("SELECT COUNT(*) FROM bookings WHERE user_id = $userId AND status='REJECTED'")->fetchColumn();

// Ambil data peminjaman terbaru milik user
$stmt = $pdo->prepare("SELECT b.*, r.name as room_name FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.id DESC LIMIT 5");
$stmt->execute([$userId]);
$myBookingsList = $stmt->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <!-- 4 Stat Cards User -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Peminjaman</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $myTotal ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Menunggu Persetujuan</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1"><?= $myPending ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Peminjaman Disetujui</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1"><?= $myApproved ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Peminjaman Ditolak</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1"><?= $myRejected ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Section: Status Peminjaman Terbaru -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Status Peminjaman Terbaru Saya</h3>
                <a href="book.php" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition flex items-center gap-1.5">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Ajukan Peminjaman
                </a>
            </div>

            <?php if (empty($myBookingsList)): ?>
                <!-- Graphic Empty State bila belum ada peminjaman -->
                <div class="text-center py-12 space-y-3">
                    <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto">
                        <i data-lucide="calendar-x" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800">Belum Ada Riwayat Peminjaman</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">Anda belum pernah mengajukan peminjaman auditorium. Klik tombol di bawah untuk mengajukan permohonan baru.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50 text-gray-500 font-medium">
                                <th class="p-3">Kegiatan</th>
                                <th class="p-3">Auditorium</th>
                                <th class="p-3">Tanggal & Waktu</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($myBookingsList as $b): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-medium text-gray-900"><?= htmlspecialchars($b['title']) ?></td>
                                    <td class="p-3 text-gray-600"><?= htmlspecialchars($b['room_name']) ?></td>
                                    <td class="p-3 text-gray-600"><?= $b['booking_date'] ?> (<?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?>)</td>
                                    <td class="p-3">
                                        <?php if ($b['status'] === 'APPROVED'): ?>
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Disetujui</span>
                                        <?php elseif ($b['status'] === 'PENDING'): ?>
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                                        <?php elseif ($b['status'] === 'REJECTED'): ?>
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
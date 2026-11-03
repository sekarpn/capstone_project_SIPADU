<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Dashboard Admin - Auditorium Booking";
$activePage = "dashboard";
$title = "Dashboard";

// Fetch Stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='USER'")->fetchColumn();
$totalRooms = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$totalPending = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='PENDING'")->fetchColumn();
$totalApproved = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status='APPROVED'")->fetchColumn();

// Fetch Latest Bookings
$latestBookings = $pdo->query("SELECT b.*, r.name as room_name, u.name as user_name FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN users u ON b.user_id = u.id ORDER BY b.id DESC LIMIT 5")->fetchAll();

// Fetch Jadwal Hari Ini (Status APPROVED)
$todayDate = date('Y-m-d');
$todayStmt = $pdo->prepare("SELECT b.*, r.name as room_name, u.name as user_name FROM bookings b JOIN rooms r ON b.room_id = r.id JOIN users u ON b.user_id = u.id WHERE b.booking_date = ? AND b.status = 'APPROVED' ORDER BY b.start_time ASC");
$todayStmt->execute([$todayDate]);
$todaySchedules = $todayStmt->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<!-- Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex-1 flex flex-col min-w-0 bg-gray-50">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Pengguna</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $totalUsers ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Auditorium</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $totalRooms ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="building-2" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Pengajuan Pending</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1"><?= $totalPending ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-medium">Peminjaman Disetujui</p>
                    <h3 class="text-2xl font-bold text-emerald-600 mt-1"><?= $totalApproved ?></h3>
                </div>
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Featured Banner Image -->
        <div class="relative rounded-2xl overflow-hidden shadow-md h-56 bg-gray-900">
            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80" alt="Auditorium" class="w-full h-full object-cover opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/30 to-transparent p-6 flex flex-col justify-end text-white">
                <h2 class="text-2xl font-bold">Selamat Datang di Portal Admin Auditorium</h2>
                <p class="text-sm text-gray-200 mt-1">Kelola jadwal peminjaman ruangan, verifikasi permohonan mahasiswa, dan pantau ketersediaan auditorium secara akurat.</p>
            </div>
        </div>

        <!-- Section Grafik Realtime & Widget Jadwal Hari Ini -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Tren Peminjaman (2 Kolom) -->
            <div class="lg:col-span-2 bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Tren Peminjaman Auditorium Realtime (<?= date('Y') ?>)</h4>
                        <p class="text-xs text-gray-400">Statistik Bulanan Peminjaman Disetujui</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Update
                    </span>
                </div>
                <div class="h-64 relative">
                    <canvas id="bookingTrendChart"></canvas>
                </div>
            </div>

            <!-- Widget Jadwal Hari Ini (1 Kolom) -->
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <h4 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i> Jadwal Hari Ini
                    </h4>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold"><?= date('d M Y') ?></span>
                </div>
                
                <div class="space-y-3 max-h-60 overflow-y-auto">
                    <?php if (empty($todaySchedules)): ?>
                        <div class="text-center py-6 text-gray-400 text-xs">
                            <i data-lucide="calendar-x" class="w-8 h-8 mx-auto mb-1 text-gray-300"></i>
                            Tidak ada peminjaman gedung hari ini.
                        </div>
                    <?php else: ?>
                        <?php foreach ($todaySchedules as $ts): ?>
                            <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs space-y-1">
                                <div class="flex items-center justify-between font-bold text-gray-800">
                                    <span><?= htmlspecialchars($ts['room_name']) ?></span>
                                    <span class="text-emerald-700 font-semibold"><?= substr($ts['start_time'], 0, 5) ?> - <?= substr($ts['end_time'], 0, 5) ?></span>
                                </div>
                                <p class="text-gray-700 font-medium"><?= htmlspecialchars($ts['title']) ?></p>
                                <p class="text-[11px] text-gray-400">PJ: <?= htmlspecialchars($ts['user_name']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Section: Recent Activity / Bookings Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-800">Permohonan Peminjaman Terbaru</h3>
                <a href="bookings.php" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-500 font-medium">
                            <th class="p-3">Kegiatan</th>
                            <th class="p-3">Auditorium</th>
                            <th class="p-3">Pemohon</th>
                            <th class="p-3">Tanggal & Waktu</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (empty($latestBookings)): ?>
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400">Belum ada data peminjaman.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($latestBookings as $b): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="p-3 font-medium text-gray-900"><?= htmlspecialchars($b['title']) ?></td>
                                    <td class="p-3 text-gray-600"><?= htmlspecialchars($b['room_name']) ?></td>
                                    <td class="p-3 text-gray-600"><?= htmlspecialchars($b['user_name']) ?></td>
                                    <td class="p-3 text-gray-600"><?= $b['booking_date'] ?> (<?= substr($b['start_time'], 0, 5) ?> - <?= substr($b['end_time'], 0, 5) ?>)</td>
                                    <td class="p-3">
                                        <?php if ($b['status'] === 'APPROVED'): ?>
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Disetujui</span>
                                        <?php elseif ($b['status'] === 'PENDING'): ?>
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                                        <?php elseif ($b['status'] === 'REJECTED'): ?>
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Ditolak</span>
                                        <?php else: ?>
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">Dibatalkan</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
let realtimeChart;

function initChart() {
    const ctx = document.getElementById('bookingTrendChart').getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(5, 150, 105, 0.35)');
    gradient.addColorStop(1, 'rgba(5, 150, 105, 0.0)');

    realtimeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                borderColor: '#059669',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#059669',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    fetchChartData();
    setInterval(fetchChartData, 5000); // Polling otomatis setiap 5 detik
}

async function fetchChartData() {
    try {
        const response = await fetch('../api/get_chart_data.php');
        const result = await response.json();

        if (result.success && realtimeChart) {
            realtimeChart.data.datasets[0].data = result.data;
            realtimeChart.update('none');
        }
    } catch (error) {
        console.error("Gagal memperbarui grafik realtime:", error);
    }
}

document.addEventListener('DOMContentLoaded', initChart);
</script>

<?php include "../includes/footer.php"; ?>
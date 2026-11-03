<?php
// includes/sidebar.php
$activePage = $activePage ?? '';
$role = $_SESSION['user_role'] ?? 'USER';
?>
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between hidden md:flex min-h-screen">
    <div>
        <div class="p-6 border-b flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-xl">
                A
            </div>
            <div>
                <h1 class="font-bold text-gray-900 leading-tight">Auditorium</h1>
                <p class="text-xs text-gray-500">System Booking</p>
            </div>
        </div>

        <nav class="p-4 space-y-1">
            <?php if ($role === 'ADMIN'): ?>
                <a href="../admin/dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'dashboard' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="../admin/users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'users' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="users" class="w-5 h-5"></i> Data Pengguna
                </a>
                <a href="../admin/rooms.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'rooms' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="building-2" class="w-5 h-5"></i> Data Auditorium
                </a>
                <a href="../admin/bookings.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'bookings' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="calendar-check" class="w-5 h-5"></i> Kelola Peminjaman
                </a>
                <a href="../admin/reports.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'reports' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="file-text" class="w-5 h-5"></i> Laporan
                </a>
                <a href="../admin/account.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'account' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="user-cog" class="w-5 h-5"></i> Kelola Akun
                </a>
            <?php else: ?>
                <a href="../user/dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'dashboard' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                </a>
                <a href="../user/book.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'book' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Ajukan Peminjaman
                </a>
                <a href="../user/history.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'history' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="history" class="w-5 h-5"></i> Peminjaman Saya
                </a>
                <a href="../user/account.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition <?= $activePage === 'account' ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' ?>">
                    <i data-lucide="user" class="w-5 h-5"></i> Kelola Akun
                </a>
            <?php endif; ?>
        </nav>
    </div>

    <div class="p-4 border-t">
        <a href="../logout.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition">
            <i data-lucide="log-out" class="w-5 h-5"></i> Keluar
        </a>
    </div>
</aside>
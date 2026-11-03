<?php
// includes/topbar.php
?>
<header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <h2 class="text-xl font-bold text-gray-800"><?= $title ?? 'Dashboard' ?></h2>
    </div>
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="hidden sm:block text-right">
            <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></p>
            <span class="text-xs px-2 py-0.5 rounded-full <?= $_SESSION['user_role'] === 'ADMIN' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' ?> font-medium">
                <?= $_SESSION['user_role'] === 'ADMIN' ? 'Admin' : 'Mahasiswa / User' ?>
            </span>
        </div>
    </div>
</header>

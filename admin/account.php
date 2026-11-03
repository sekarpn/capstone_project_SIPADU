<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Kelola Akun - Admin";
$activePage = "account";
$title = "Kelola Akun";

$userId = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $department = trim($_POST['department']);

        $stmt = $pdo->prepare("UPDATE users SET name = ?, phone = ?, department = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $department, $userId]);
        $_SESSION['user_name'] = $name;
        $msg = "Profil berhasil diperbarui!";
    } elseif (isset($_POST['update_password'])) {
        $newPass = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newPass, $userId]);
        $msg = "Password berhasil diubah!";
    }
}

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$userId]);
$userData = $user->fetch();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto max-w-4xl">
        <?php if ($msg): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <!-- Form Edit Profil -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-800">Profil Administrator</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_profile" value="1">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required class="w-full p-2.5 border rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Email (Utama)</label>
                    <input type="email" value="<?= htmlspecialchars($userData['email']) ?>" disabled class="w-full p-2.5 border rounded-lg text-sm bg-gray-50 text-gray-500">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">No HP / WhatsApp</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" class="w-full p-2.5 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Unit Kerja / Departemen</label>
                        <input type="text" name="department" value="<?= htmlspecialchars($userData['department'] ?? '') ?>" class="w-full p-2.5 border rounded-lg text-sm">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">Simpan Perubahan Profil</button>
            </form>
        </div>

        <!-- Form Ubah Password -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="text-lg font-bold text-gray-800">Ubah Kata Sandi</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_password" value="1">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Password Baru</label>
                    <input type="password" name="password" required class="w-full p-2.5 border rounded-lg text-sm" placeholder="••••••••">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-semibold hover:bg-gray-900">Perbarui Password</button>
            </form>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
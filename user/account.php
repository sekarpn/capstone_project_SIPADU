<?php
require_once "../config/database.php";
checkAuth('USER');

$pageTitle = "Kelola Akun - User Mahasiswa";
$activePage = "account";
$title = "Kelola Akun Mahasiswa";

$userId = $_SESSION['user_id'];
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = trim($_POST['name']);
        $nim_nip = trim($_POST['nim_nip']);
        $phone = trim($_POST['phone']);
        $department = trim($_POST['department']);

        $stmt = $pdo->prepare("UPDATE users SET name = ?, nim_nip = ?, phone = ?, department = ? WHERE id = ?");
        $stmt->execute([$name, $nim_nip, $phone, $department, $userId]);
        $_SESSION['user_name'] = $name;
        $msg = "Profil akun mahasiswa berhasil diperbarui!";
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

        <!-- Form Edit Profil Mahasiswa -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3 border-b pb-4">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-700 font-bold rounded-full flex items-center justify-center text-lg">
                    <?= strtoupper(substr($userData['name'], 0, 1)) ?>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-800"><?= htmlspecialchars($userData['name']) ?></h3>
                    <p class="text-xs text-gray-500">Mahasiswa / Pengguna</p>
                </div>
            </div>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_profile" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required class="w-full p-2.5 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                        <input type="email" value="<?= htmlspecialchars($userData['email']) ?>" disabled class="w-full p-2.5 border rounded-lg text-sm bg-gray-50 text-gray-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">NIM / NIP</label>
                        <input type="text" name="nim_nip" value="<?= htmlspecialchars($userData['nim_nip'] ?? '') ?>" class="w-full p-2.5 border rounded-lg text-sm" placeholder="2101012345">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">No HP / WhatsApp</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" class="w-full p-2.5 border rounded-lg text-sm" placeholder="089876543210">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Fakultas / Program Studi</label>
                        <input type="text" name="department" value="<?= htmlspecialchars($userData['department'] ?? '') ?>" class="w-full p-2.5 border rounded-lg text-sm" placeholder="Teknik Informatika">
                    </div>
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Form Ubah Password -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="text-base font-bold text-gray-800">Keamanan & Ubah Password</h3>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_password" value="1">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Password Baru</label>
                    <input type="password" name="password" required class="w-full p-2.5 border rounded-lg text-sm" placeholder="••••••••">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-semibold hover:bg-gray-900 transition">
                    Perbarui Password
                </button>
            </form>
        </div>
    </main>
</div>

<?php include "../includes/footer.php"; ?>
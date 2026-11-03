<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Data Pengguna - Admin";
$activePage = "users";
$title = "Data Pengguna";

// Handle Actions (Add, Delete)
$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $nim_nip = trim($_POST['nim_nip']);
        $phone = trim($_POST['phone']);
        $department = trim($_POST['department']);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, nim_nip, phone, department) VALUES (?, ?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $password, $role, $nim_nip, $phone, $department]);
            $msg = "Pengguna berhasil ditambahkan!";
        } catch (PDOException $e) {
            $msg = "Gagal menambah pengguna: Email mungkin sudah terdaftar.";
        }
    } elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $userId = $_POST['user_id'];
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $msg = "Pengguna berhasil dihapus!";
    }
}

// Fetch Users
$users = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();

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

        <!-- Stat Header summary cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Total Account</p>
                    <h4 class="text-xl font-bold text-gray-800"><?= count($users) ?></h4>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Mahasiswa / User</p>
                    <h4 class="text-xl font-bold text-gray-800"><?= count(array_filter($users, fn($u) => $u['role'] === 'USER')) ?></h4>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">Administrator</p>
                    <h4 class="text-xl font-bold text-gray-800"><?= count(array_filter($users, fn($u) => $u['role'] === 'ADMIN')) ?></h4>
                </div>
                <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Add User Modal / Form Toggle & User Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Daftar Pengguna Sistem</h3>
                    <p class="text-xs text-gray-500">Kelola hak akses akun mahasiswa dan pengelola</p>
                </div>
                <button onclick="document.getElementById('addUserModal').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Tambah Pengguna
                </button>
            </div>

            <!-- Add User Form (Modal Style Toggle) -->
            <div id="addUserModal" class="hidden mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <h4 class="font-bold text-sm text-gray-800 mb-3">Form Pengguna Baru</h4>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <input type="hidden" name="action" value="add">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Role</label>
                        <select name="role" class="w-full p-2 text-sm border rounded-lg">
                            <option value="USER">User / Mahasiswa</option>
                            <option value="ADMIN">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">NIM / NIP</label>
                        <input type="text" name="nim_nip" class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">No. HP / WhatsApp</label>
                        <input type="text" name="phone" class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-xs text-gray-600 mb-1">Fakultas / Departemen</label>
                        <input type="text" name="department" class="w-full p-2 text-sm border rounded-lg">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3 flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold">Simpan Pengguna</button>
                    </div>
                </form>
            </div>

            <!-- Table Users -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-500">
                            <th class="p-3">No</th>
                            <th class="p-3">Nama Pengguna</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">NIM / NIP</th>
                            <th class="p-3">Role</th>
                            <th class="p-3">No HP</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($users as $index => $u): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 font-semibold text-gray-500"><?= $index + 1 ?></td>
                                <td class="p-3 font-medium text-gray-900"><?= htmlspecialchars($u['name']) ?></td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($u['nim_nip'] ?? '-') ?></td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $u['role'] === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                        <?= $u['role'] ?>
                                    </span>
                                </td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($u['phone'] ?? '-') ?></td>
                                <td class="p-3 text-center">
                                    <form method="POST" onsubmit="return confirm('Yakin menghapus pengguna ini?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
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

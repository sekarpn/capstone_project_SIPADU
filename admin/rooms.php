<?php
require_once "../config/database.php";
checkAuth('ADMIN');

$pageTitle = "Kelola Auditorium - Admin";
$activePage = "rooms";
$title = "Kelola Data Auditorium";

$msg = "";
$error = "";

// Handle Tambah, Edit, dan Hapus Auditorium
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name']);
        $location = trim($_POST['location']);
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        $description = trim($_POST['description']);
        $image = trim($_POST['image']) ?: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80';

        $stmt = $pdo->prepare("INSERT INTO rooms (name, location, capacity, status, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $location, $capacity, $status, $description, $image]);
        $msg = "Auditorium baru berhasil ditambahkan!";
    } elseif ($action === 'update') {
        $id = $_POST['room_id'];
        $name = trim($_POST['name']);
        $location = trim($_POST['location']);
        $capacity = (int)$_POST['capacity'];
        $status = $_POST['status'];
        $description = trim($_POST['description']);
        $image = trim($_POST['image']);

        $stmt = $pdo->prepare("UPDATE rooms SET name = ?, location = ?, capacity = ?, status = ?, description = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $location, $capacity, $status, $description, $image, $id]);
        $msg = "Data auditorium berhasil diperbarui!";
    } elseif ($action === 'delete') {
        $id = $_POST['room_id'];
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "Auditorium berhasil dihapus!";
    }
}

$rooms = $pdo->query("SELECT * FROM rooms ORDER BY id DESC")->fetchAll();

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<div class="flex-1 flex flex-col min-w-0 bg-gray-50">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <?php if ($msg): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Auditorium / Ruangan</h3>
                <p class="text-xs text-gray-500">Kelola kapasitas, lokasi, dan status ketersediaan auditorium.</p>
            </div>
            <button onclick="openCreateModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-semibold shadow-sm flex items-center gap-2 hover:bg-emerald-700 transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Auditorium
            </button>
        </div>

        <!-- Grid Katalog Auditorium -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($rooms as $room): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="relative h-48 bg-gray-900">
                            <img src="<?= htmlspecialchars($room['image']) ?>" class="w-full h-full object-cover">
                            <span class="absolute top-3 right-3 px-2.5 py-1 text-[10px] font-bold rounded-full <?= $room['status'] === 'Available' ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' ?>">
                                <?= $room['status'] === 'Available' ? 'Tersedia' : 'Maintenance / Perbaikan' ?>
                            </span>
                        </div>
                        <div class="p-5 space-y-2">
                            <h4 class="font-bold text-gray-800 text-base"><?= htmlspecialchars($room['name']) ?></h4>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <?= htmlspecialchars($room['location']) ?>
                            </p>
                            <p class="text-xs text-gray-600">Kapasitas: <b class="text-gray-800"><?= $room['capacity'] ?> Orang</b></p>
                            <p class="text-xs text-gray-500 line-clamp-2"><?= htmlspecialchars($room['description'] ?? 'Tidak ada deskripsi.') ?></p>
                        </div>
                    </div>
                    <div class="p-4 border-t bg-gray-50 flex items-center justify-end gap-2">
                        <!-- Tombol Edit -->
                        <button onclick='openEditModal(<?= json_encode($room) ?>)' class="px-3 py-1.5 bg-yellow-500 text-white rounded-lg text-xs font-semibold hover:bg-yellow-600 transition flex items-center gap-1">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                        </button>

                        <!-- Form Hapus -->
                        <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus auditorium ini?');" class="inline">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Modal Form (Tambah / Edit Auditorium) -->
<div id="roomModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="font-bold text-base text-gray-800" id="modalTitle">Tambah Auditorium Baru</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form method="POST" class="space-y-4">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="room_id" id="roomId">

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Auditorium</label>
                <input type="text" name="name" id="roomName" required class="w-full p-2.5 border rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500" placeholder="Auditorium Bhinneka Tunggal Ika">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi Gedung / Lantai</label>
                    <input type="text" name="location" id="roomLocation" required class="w-full p-2.5 border rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500" placeholder="Gedung Rektorat Lt. 4">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kapasitas (Orang)</label>
                    <input type="number" name="capacity" id="roomCapacity" required class="w-full p-2.5 border rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500" placeholder="800">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Status Ketersediaan</label>
                <select name="status" id="roomStatus" class="w-full p-2.5 border rounded-xl text-xs font-medium focus:ring-2 focus:ring-emerald-500">
                    <option value="Available">Available / Tersedia</option>
                    <option value="Maintenance">Maintenance / Perbaikan</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">URL Gambar Gedung</label>
                <input type="url" name="image" id="roomImage" class="w-full p-2.5 border rounded-xl text-xs font-medium" placeholder="https://images.unsplash.com/...">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi & Fasilitas Ruangan</label>
                <textarea name="description" id="roomDescription" rows="3" class="w-full p-2.5 border rounded-xl text-xs" placeholder="Lengkap dengan Sound System JBL, 800 Kursi, VIP Lounge..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Auditorium Baru';
    document.getElementById('formAction').value = 'create';
    document.getElementById('roomId').value = '';
    document.getElementById('roomName').value = '';
    document.getElementById('roomLocation').value = '';
    document.getElementById('roomCapacity').value = '';
    document.getElementById('roomStatus').value = 'Available';
    document.getElementById('roomImage').value = '';
    document.getElementById('roomDescription').value = '';
    document.getElementById('roomModal').classList.remove('hidden');
}

function openEditModal(room) {
    document.getElementById('modalTitle').innerText = 'Edit Data Auditorium';
    document.getElementById('formAction').value = 'update';
    document.getElementById('roomId').value = room.id;
    document.getElementById('roomName').value = room.name;
    document.getElementById('roomLocation').value = room.location;
    document.getElementById('roomCapacity').value = room.capacity;
    document.getElementById('roomStatus').value = room.status;
    document.getElementById('roomImage').value = room.image;
    document.getElementById('roomDescription').value = room.description || '';
    document.getElementById('roomModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('roomModal').classList.add('hidden');
}
</script>

<?php include "../includes/footer.php"; ?>
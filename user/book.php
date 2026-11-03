<?php
require_once "../config/database.php";
checkAuth('USER');

$pageTitle = "Ajukan Peminjaman - User";
$activePage = "book";
$title = "Ajukan Peminjaman Auditorium";

$userId = $_SESSION['user_id'];
$rooms = $pdo->query("SELECT * FROM rooms WHERE status='Available'")->fetchAll();

$msg = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $titleInput = trim($_POST['title']);
    $startDate = $_POST['start_booking_date'];
    $endDate = $_POST['end_booking_date'] ?: $startDate;
    $sessionTime = $_POST['session_time'];
    $participants = (int)($_POST['participants'] ?? 0);
    $category = $_POST['category'] ?? 'Organisasi Mahasiswa (Ormawa)';
    $facilities = isset($_POST['facilities']) ? implode(', ', $_POST['facilities']) : 'Tanpa fasilitas tambahan';
    $userNote = trim($_POST['event_description'] ?? '');

    if ($sessionTime === 'Pagi') {
        $startTime = '08:00:00';
        $endTime = '12:00:00';
    } elseif ($sessionTime === 'Siang') {
        $startTime = '13:00:00';
        $endTime = '17:00:00';
    } else {
        $startTime = '08:00:00';
        $endTime = '17:00:00';
    }

    $fullDesc = "Kategori: $category | Rentang: $startDate s.d. $endDate | Peserta: $participants orang | Fasilitas: $facilities | Catatan: $userNote";

    if (!empty($titleInput) && !empty($startDate)) {
        $checkSql = "SELECT COUNT(*) FROM bookings WHERE room_id = ? AND status IN ('APPROVED', 'PENDING') AND booking_date BETWEEN ? AND ? AND (start_time < ? AND end_time > ?)";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([$roomId, $startDate, $endDate, $endTime, $startTime]);

        if ($checkStmt->fetchColumn() > 0) {
            $error = "Gagal mengajukan: Ruangan telah terisi/bentrok pada rentang tanggal tersebut.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO bookings (room_id, user_id, title, event_description, booking_date, start_time, end_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'PENDING')");
            $stmt->execute([$roomId, $userId, $titleInput, $fullDesc, $startDate, $startTime, $endTime]);
            $msg = "Pengajuan peminjaman rentang tanggal berhasil dikirim! Silakan menunggu verifikasi admin.";
        }
    } else {
        $error = "Mohon lengkapi semua field yang wajib diisi.";
    }
}

$namaHari = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];

include "../includes/header.php";
include "../includes/sidebar.php";
?>

<style>
/* Sembunyikan scrollbar bawaan */
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Mengaktifkan cursor drag pada slider */
.drag-scroll {
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
}
.drag-scroll:active {
    cursor: grabbing;
}
</style>

<div class="flex-1 flex flex-col min-w-0">
    <?php include "../includes/topbar.php"; ?>

    <main class="p-6 space-y-6 overflow-y-auto">
        <?php if ($msg): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <?= $msg ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Pilih Auditorium / Ruangan</h3>
            <span class="text-xs text-gray-500">Tersedia <?= count($rooms) ?> Ruangan</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($rooms as $room): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <img src="<?= htmlspecialchars($room['image']) ?>" class="w-full h-48 object-cover">
                        <div class="p-5 space-y-3">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-gray-800 text-base"><?= htmlspecialchars($room['name']) ?></h4>
                                <span class="px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">Tersedia</span>
                            </div>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <?= htmlspecialchars($room['location']) ?>
                            </p>
                            <p class="text-xs text-gray-600">Kapasitas: <b class="text-gray-800"><?= $room['capacity'] ?> Orang</b></p>
                            <p class="text-xs text-gray-500 line-clamp-2"><?= htmlspecialchars($room['description'] ?? 'Ruangan auditorium lengkap dengan fasilitas standar.') ?></p>
                        </div>
                    </div>
                    <div class="p-4 border-t bg-gray-50">
                        <button onclick="openBookingModal(<?= $room['id'] ?>, '<?= htmlspecialchars($room['name']) ?>')" class="w-full py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
                            Pesan Ruangan Ini
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<!-- Modal Form Booking Interaktif -->
<div id="bookingModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 space-y-5 shadow-2xl">
        
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">SIPADU V2 • Form Reservasi</span>
                <h3 class="font-bold text-lg text-gray-800" id="modalRoomTitle">Pesan Auditorium</h3>
            </div>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form method="POST" class="space-y-5">
            <input type="hidden" name="room_id" id="modalRoomId">

            <!-- Section 1: Slider Tanggal Interaktif Drag/Touch & Input Rentang -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i> Pilih Rentang Tanggal (Geser / Swipe 30 Hari Kedepan)
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold text-emerald-600" id="modalRealtimeStatus">Mengecek ketersediaan...</span>
                        <div class="flex items-center gap-1">
                            <button type="button" onclick="scrollDateSlider(-220)" class="p-1 rounded border bg-white hover:bg-gray-100"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                            <button type="button" onclick="scrollDateSlider(220)" class="p-1 rounded border bg-white hover:bg-gray-100"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Slider Container (Mendukung Touch & Mouse Dragging) -->
                <div id="dateSlider" class="flex items-center gap-2 overflow-x-auto scroll-smooth no-scrollbar py-2 drag-scroll">
                    <?php
                    $today = new DateTime();
                    for ($i = 0; $i < 30; $i++):
                        $currDate = clone $today;
                        $currDate->modify("+$i day");
                        $val = $currDate->format('Y-m-d');
                        $dayName = $namaHari[$currDate->format('w')];
                        $dateNum = $currDate->format('d');
                    ?>
                        <div onclick="selectSliderDate('<?= $val ?>')" class="min-w-[72px] cursor-pointer p-2.5 rounded-xl border text-center transition flex flex-col items-center bg-white border-gray-200 hover:border-emerald-500 date-card shrink-0" data-date="<?= $val ?>">
                            <span class="text-[9px] font-semibold uppercase tracking-wider text-gray-400 pointer-events-none"><?= $dayName ?></span>
                            <span class="text-sm font-bold my-0.5 text-gray-800 pointer-events-none"><?= $dateNum ?></span>
                            <span class="text-[9px] text-emerald-600 font-medium pointer-events-none">Tersedia</span>
                        </div>
                    <?php endfor; ?>
                </div>

                <!-- Input Rentang Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-200">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Tanggal Mulai Peminjaman</label>
                        <input type="date" name="start_booking_date" id="start_booking_date" required value="<?= date('Y-m-d') ?>" class="w-full p-2 border rounded-lg text-xs font-semibold bg-white" onchange="checkRealtimeAvailability()">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-700 mb-1">Tanggal Selesai Peminjaman</label>
                        <input type="date" name="end_booking_date" id="end_booking_date" required value="<?= date('Y-m-d') ?>" class="w-full p-2 border rounded-lg text-xs font-semibold bg-white" onchange="checkRealtimeAvailability()">
                    </div>
                </div>
            </div>

            <!-- Section 2: Sesi Waktu Pemakaian -->
            <div class="space-y-2">
                <label class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-4 h-4 text-emerald-600"></i> Pilih Sesi Waktu Pemakaian
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="session_time" value="Pagi" class="hidden peer" onchange="checkRealtimeAvailability()">
                        <div class="p-3.5 rounded-xl border border-gray-200 bg-white peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition">
                            <h4 class="text-xs font-bold text-gray-800">Sesi Pagi</h4>
                            <p class="text-xs font-semibold text-emerald-600 mt-0.5">08:00 - 12:00 WIB</p>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="session_time" value="Siang" class="hidden peer" onchange="checkRealtimeAvailability()">
                        <div class="p-3.5 rounded-xl border border-gray-200 bg-white peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition">
                            <h4 class="text-xs font-bold text-gray-800">Sesi Siang</h4>
                            <p class="text-xs font-semibold text-emerald-600 mt-0.5">13:00 - 17:00 WIB</p>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="session_time" value="Full Day" class="hidden peer" checked onchange="checkRealtimeAvailability()">
                        <div class="p-3.5 rounded-xl border border-gray-200 bg-white peer-checked:border-emerald-600 peer-checked:bg-emerald-50/50 transition">
                            <h4 class="text-xs font-bold text-gray-800">Sesi Penuh (Full Day)</h4>
                            <p class="text-xs font-semibold text-emerald-600 mt-0.5">08:00 - 17:00 WIB</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 3: Detail Kegiatan -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama / Judul Kegiatan</label>
                    <input type="text" name="title" required placeholder="Seminar Nasional 2026" class="w-full p-2.5 border rounded-lg text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Kategori Kegiatan</label>
                    <select name="category" class="w-full p-2.5 border rounded-lg text-xs font-medium">
                        <option value="Organisasi Mahasiswa (Ormawa)">Organisasi Mahasiswa (Ormawa / BEM / HIMA)</option>
                        <option value="Fakultas / Program Studi">Fakultas / Program Studi</option>
                        <option value="Kegiatan Eksternal">Kegiatan Eksternal / Kerjasama</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Jumlah Peserta (Orang)</label>
                    <input type="number" name="participants" required value="100" class="w-full p-2.5 border rounded-lg text-xs font-medium">
                </div>
            </div>

            <!-- Section 4: Deskripsi -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi & Keperluan Kegiatan</label>
                <textarea name="event_description" rows="2" class="w-full p-2.5 border rounded-lg text-xs" placeholder="Jelaskan secara ringkas kebutuhan kegiatan..."></textarea>
            </div>

            <div class="flex items-center justify-between pt-3 border-t">
                
                <div class="flex gap-2">
                    <button type="button" onclick="closeBookingModal()" class="px-4 py-2 border rounded-lg text-xs font-semibold text-gray-600">Batal</button>
                    <button type="submit" id="modalBtnSubmit" class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition flex items-center gap-1.5 shadow-md shadow-emerald-100">
                        <i data-lucide="send" class="w-4 h-4"></i> Kirim Pengajuan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentRoomId = null;
let clickStep = 0;
let isDragging = false;
let startX, scrollLeft;

// Navigation Scroll via Tombol Chevron
function scrollDateSlider(amount) {
    document.getElementById('dateSlider').scrollBy({ left: amount, behavior: 'smooth' });
}

// Inisialisasi Mouse Drag Scroll & Touch Gesture
function initDragScroll() {
    const slider = document.getElementById('dateSlider');

    slider.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });

    slider.addEventListener('mouseleave', () => { isDragging = false; });
    slider.addEventListener('mouseup', () => { isDragging = false; });

    slider.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2;
        slider.scrollLeft = scrollLeft - walk;
    });
}

// Klik pada Kartu Tanggal Slider
function selectSliderDate(dateVal) {
    const startInput = document.getElementById('start_booking_date');
    const endInput = document.getElementById('end_booking_date');

    if (clickStep === 0 || dateVal < startInput.value) {
        startInput.value = dateVal;
        endInput.value = dateVal;
        clickStep = 1;
    } else {
        endInput.value = dateVal;
        clickStep = 0;
    }
    checkRealtimeAvailability();
}

function openBookingModal(id, name) {
    currentRoomId = id;
    document.getElementById('modalRoomId').value = id;
    document.getElementById('modalRoomTitle').innerText = 'Pesan: ' + name;
    document.getElementById('bookingModal').classList.remove('hidden');
    clickStep = 0;
    setTimeout(initDragScroll, 100);
    checkRealtimeAvailability();
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Validasi Realtime & Visual Highlight Block Rentang Tanggal
async function checkRealtimeAvailability() {
    if (!currentRoomId) return;

    const startDateInput = document.getElementById('start_booking_date');
    const endDateInput = document.getElementById('end_booking_date');
    
    if (endDateInput.value < startDateInput.value) {
        endDateInput.value = startDateInput.value;
    }

    const startVal = startDateInput.value;
    const endVal = endDateInput.value;
    const sessionRadio = document.querySelector('input[name="session_time"]:checked');
    const sessionTime = sessionRadio ? sessionRadio.value : 'Full Day';

    // Visual Block Hijau pada Slider
    document.querySelectorAll('.date-card').forEach(card => {
        const cDate = card.getAttribute('data-date');
        
        if (cDate >= startVal && cDate <= endVal) {
            card.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
            card.classList.remove('bg-white', 'border-gray-200');
            const subText = card.querySelector('span:last-child');
            if (subText) subText.className = 'text-[9px] text-emerald-100 font-medium pointer-events-none';
        } else {
            card.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
            card.classList.add('bg-white', 'border-gray-200');
            const subText = card.querySelector('span:last-child');
            if (subText) subText.className = 'text-[9px] text-emerald-600 font-medium pointer-events-none';
        }
    });

    const statusEl = document.getElementById('modalRealtimeStatus');
    const btnSubmit = document.getElementById('modalBtnSubmit');

    statusEl.innerText = "Memeriksa...";
    statusEl.className = "text-[11px] text-yellow-600 font-semibold";

    try {
        const res = await fetch(`../api/check_availability.php?room_id=${currentRoomId}&start_date=${startVal}&end_date=${endVal}&session_time=${sessionTime}`);
        const data = await res.json();

        if (data.available) {
            statusEl.innerText = "✓ Available / Tersedia";
            statusEl.className = "text-[11px] text-emerald-600 font-semibold";
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            statusEl.innerText = "✕ Ruangan Bentrok!";
            statusEl.className = "text-[11px] text-red-600 font-semibold";
            btnSubmit.disabled = true;
            btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
        }
    } catch (e) {
        statusEl.innerText = "✓ Tersedia";
        statusEl.className = "text-[11px] text-emerald-600 font-semibold";
    }
}
</script>

<?php include "../includes/footer.php"; ?>
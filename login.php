<?php
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Check password (for demo simplicity, accept password123 or hashed)
        if ($user && ($password === 'password123' || password_verify($password, $user['password']))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'ADMIN') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit();
        } else {
            $error = "Email atau password salah!";
        }
    } else {
        $error = "Silakan isi semua field.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Auditorium Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-3 shadow-lg shadow-emerald-200">
                <i data-lucide="building-2" class="w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Masuk ke Sistem</h2>
            <p class="text-sm text-gray-500 mt-1">Sistem Peminjaman Auditorium & Ruangan</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-200 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input type="email" name="email" required placeholder="admin@mail.com / user@mail.com" class="w-full pl-10 pr-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white font-semibold py-2.5 rounded-lg hover:bg-emerald-700 transition shadow-md shadow-emerald-100 flex items-center justify-center gap-2">
                Masuk
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <div class="mt-6 p-4 bg-emerald-50 rounded-lg text-xs text-emerald-800 space-y-1">
            <p class="font-bold">Akun Testing bawaan:</p>
            <p>• Admin: <b>admin@mail.com</b> / <b>password123</b></p>
            <p>• User: <b>user@mail.com</b> / <b>password123</b></p>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>

<?php
session_start();

// Jika sudah login langsung arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Mobil Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center relative" 
      style="background-image: url('https://images.unsplash.com/photo-1502877338535-766e1452684a?auto=format&fit=crop&w=1350&q=80');">

    <!-- Overlay hitam transparan -->
    <div class="absolute inset-0 bg-black bg-opacity-60"></div>

    <!-- Konten utama -->
    <div class="relative z-10 text-center max-w-2xl p-8 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-600 mb-4">
            🚘 Selamat Datang di Rental Mobil Jaya
        </h1>
        <p class="text-gray-700 mb-8">
            Platform sederhana untuk penyewaan mobil dengan cepat, mudah, dan nyaman.
        </p>

        <!-- Tombol -->
        <div class="flex justify-center gap-4">
            <a href="auth/login.php" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md font-semibold transition">
               🔑 Login
            </a>
            <a href="auth/register.php" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md font-semibold transition">
               📝 Register
            </a>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-gray-600 text-sm">
            &copy; <?= date("Y") ?> Rental Mobil Jaya. All rights reserved.
        </div>
    </div>

</body>
</html>

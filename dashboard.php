<?php
session_start();

// Jika belum login, arahkan ke index
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Ambil data dari session
$name = $_SESSION['name'];
$role_id = $_SESSION['role_id']; // 1 = admin, 2 = user
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-blue-700 text-white flex flex-col shadow-lg">
    <div class="p-6 text-center border-b border-blue-500">
      <h2 class="text-2xl font-bold">🚗 Rental App</h2>
    </div>
    <div class="p-4 border-b border-blue-500">
      <p class="font-semibold"><?= htmlspecialchars($name) ?></p>
      <p class="text-sm text-blue-200">
        <?= ($role_id == 1) ? "Admin" : "User"; ?>
      </p>
    </div>
    <nav class="flex-1 px-4 py-6 space-y-2">
      <?php if ($role_id == 1) { ?>
        <a href="admin/mobil/mobil.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">🚘 Kelola Mobil</a>
        <a href="admin/mobil/pelanggan.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">👥 Kelola Pelanggan</a>
        <a href="admin/mobil/transaksi.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">💳 Kelola Transaksi</a>
      <?php } else { ?>
        <a href="user/sewa.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">📄 Sewa Mobil</a>
        <a href="user/transaksi.php" class="block px-4 py-2 rounded hover:bg-blue-600 transition">📑 Transaksi Saya</a>
      <?php } ?>
    </nav>
    <div class="p-4 border-t border-blue-500">
      <a href="auth/logout.php" class="block px-4 py-2 text-red-300 rounded hover:bg-red-500 hover:text-white transition">🔒 Logout</a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-8">
    <!-- Header -->
    <header class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
      <p class="text-gray-600">
        Halo, <span class="font-semibold"><?= htmlspecialchars($name) ?></span>! 
        Anda login sebagai <span class="font-semibold"><?= ($role_id == 1) ? "Admin" : "User"; ?></span>.
      </p>
    </header>

    <!-- Welcome Box -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
      <h2 class="text-2xl font-bold mb-2">Selamat Datang 👋</h2>
      <p class="text-gray-600">Gunakan menu di sebelah kiri untuk mengakses fitur yang tersedia.</p>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php if ($role_id == 1) { ?>
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
          <h3 class="text-xl font-bold mb-2">🚘 Kelola Mobil</h3>
          <p class="text-gray-600 mb-4">Tambah, ubah, atau hapus data mobil.</p>
          <a href="admin/mobil/mobil.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Kelola</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
          <h3 class="text-xl font-bold mb-2">👥 Kelola Pelanggan</h3>
          <p class="text-gray-600 mb-4">Lihat dan kelola data pelanggan.</p>
          <a href="admin/mobil/pelanggan.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Kelola</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
          <h3 class="text-xl font-bold mb-2">💳 Kelola Transaksi</h3>
          <p class="text-gray-600 mb-4">Pantau dan kelola transaksi penyewaan.</p>
          <a href="admin/mobil/transaksi.php" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Kelola</a>
        </div>
      <?php } else { ?>
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
          <h3 class="text-xl font-bold mb-2">📄 Sewa Mobil</h3>
          <p class="text-gray-600 mb-4">Pilih mobil yang tersedia untuk disewa.</p>
          <a href="user/sewa.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mulai Sewa</a>
        </div>
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
          <h3 class="text-xl font-bold mb-2">📑 Transaksi Saya</h3>
          <p class="text-gray-600 mb-4">Lihat riwayat transaksi penyewaan Anda.</p>
          <a href="user/transaksi.php" class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Lihat</a>
        </div>
      <?php } ?>
    </div>
  </main>
</body>
</html>

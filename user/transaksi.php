<?php
session_start();
include '../config.php';

// Jika belum login, kembalikan ke index
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id']; // 1=admin, 2=user

// Jika role user → ambil transaksi miliknya
if ($role_id == 2) {
    $query = "SELECT t.*, 
                     m.merk, 
                     m.name AS mobil, 
                     m.gambar, 
                     u.name AS pelanggan
              FROM transaksi t
              JOIN mobil m ON t.id_mobil = m.id
              JOIN pelanggan p ON t.id_pelanggan = p.id
              JOIN users u ON p.user_id = u.id
              WHERE p.user_id = '$user_id'
              ORDER BY t.id DESC";
} else {
    // Jika admin → ambil semua transaksi
    $query = "SELECT t.*, 
                     m.merk, 
                     m.name AS mobil, 
                     m.gambar, 
                     u.name AS pelanggan
              FROM transaksi t
              JOIN mobil m ON t.id_mobil = m.id
              JOIN pelanggan p ON t.id_pelanggan = p.id
              JOIN users u ON p.user_id = u.id
              ORDER BY t.id DESC";
}

$transaksi = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Transaksi - Rental Mobil Jaya</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-blue-600 text-white px-6 py-4 shadow">
        <h1 class="text-xl font-bold">📊 Data Transaksi</h1>
    </div>

    <!-- Container -->
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-200 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Pelanggan</th>
                            <th class="px-4 py-2 border">Mobil</th>
                            <th class="px-4 py-2 border">Gambar</th>
                            <th class="px-4 py-2 border">Tanggal Sewa</th>
                            <th class="px-4 py-2 border">Tanggal Kembali</th>
                            <th class="px-4 py-2 border">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($t = mysqli_fetch_assoc($transaksi)) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border text-center"><?= $t['id'] ?></td>
                            <td class="px-4 py-2 border"><?= $t['pelanggan'] ?></td>
                            <td class="px-4 py-2 border"><?= $t['merk'] ?> - <?= $t['mobil'] ?></td>
                            <td class="px-4 py-2 border text-center">
                                <?php if (!empty($t['gambar'])) : ?>
                                    <img src="../uploads/mobil/<?= $t['gambar'] ?>" class="h-20 mx-auto rounded-md shadow">
                                <?php else : ?>
                                    <img src="../uploads/mobil/default.jpg" class="h-20 mx-auto rounded-md shadow">
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border"><?= $t['tgl_sewa'] ?></td>
                            <td class="px-4 py-2 border"><?= $t['tgl_kembali'] ?></td>
                            <td class="px-4 py-2 border font-semibold text-green-600">
                                Rp <?= number_format($t['total_bayar'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol kembali -->
        <div class="mt-6 text-center">
            <a href="../dashboard.php" 
               class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow-md transition">
                ⬅ Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>

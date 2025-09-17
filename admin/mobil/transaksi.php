<?php
include '../../config.php';

// Hapus transaksi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Ambil id_mobil agar status mobil bisa dikembalikan ke 'tersedia'
    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_mobil FROM transaksi WHERE id=$id"));
    $id_mobil = $cek['id_mobil'];

    // Hapus transaksi
    mysqli_query($conn, "DELETE FROM transaksi WHERE id=$id");

    // Update status mobil
    mysqli_query($conn, "UPDATE mobil SET status='tersedia' WHERE id=$id_mobil");
}

// Update status pembayaran → Lunas
if (isset($_GET['lunas'])) {
    $id = $_GET['lunas'];
    mysqli_query($conn, "UPDATE transaksi SET status_bayar='Lunas' WHERE id=$id");
}

// Ambil data transaksi
$query = "SELECT t.id, p.name AS pelanggan, m.merk, m.name AS mobil, 
                 t.tgl_sewa, t.tgl_kembali, t.total_bayar, t.status_bayar
          FROM transaksi t
          JOIN pelanggan p ON t.id_pelanggan = p.id
          JOIN mobil m ON t.id_mobil = m.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-blue-600 text-white px-6 py-4 shadow">
        <h1 class="text-xl font-bold">💳 Kelola Transaksi</h1>
    </div>

    <div class="container mx-auto px-4 py-6">
        <!-- Tabel Data Transaksi -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">📋 Daftar Transaksi</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-3 py-2">ID</th>
                            <th class="border px-3 py-2">Pelanggan</th>
                            <th class="border px-3 py-2">Mobil</th>
                            <th class="border px-3 py-2">Tgl Sewa</th>
                            <th class="border px-3 py-2">Tgl Kembali</th>
                            <th class="border px-3 py-2">Total Bayar</th>
                            <th class="border px-3 py-2">Status</th>
                            <th class="border px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2 text-center"><?= $row['id'] ?></td>
                            <td class="border px-3 py-2"><?= $row['pelanggan'] ?></td>
                            <td class="border px-3 py-2"><?= $row['merk'] ?> - <?= $row['mobil'] ?></td>
                            <td class="border px-3 py-2"><?= $row['tgl_sewa'] ?></td>
                            <td class="border px-3 py-2"><?= $row['tgl_kembali'] ?></td>
                            <td class="border px-3 py-2 font-semibold text-green-600">
                                Rp <?= number_format($row['total_bayar'],0,',','.') ?>
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <?php if ($row['status_bayar'] == "Lunas") { ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                                        ✅ Lunas
                                    </span>
                                <?php } else { ?>
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                                        ⏳ Belum Lunas
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="border px-3 py-2 text-center space-x-2">
                                <?php if ($row['status_bayar'] != "Lunas") { ?>
                                    <a href="?lunas=<?= $row['id'] ?>" 
                                       onclick="return confirm('Tandai transaksi ini sebagai Lunas?')" 
                                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded shadow">
                                        ✅ Tandai Lunas
                                    </a>
                                <?php } ?>
                                <a href="?hapus=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin hapus transaksi ini?')" 
                                   class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow">
                                    🗑️ Hapus
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol kembali -->
        <div class="mt-6 text-center">
            <a href="../../dashboard.php" 
               class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-5 py-2 rounded-lg shadow-md">
                ⬅ Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>

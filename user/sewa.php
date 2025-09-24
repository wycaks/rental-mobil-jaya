<?php
// Pastikan session hanya dipanggil sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Cek apakah user punya data pelanggan
$pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE user_id='$user_id'"));

if (!$pelanggan) {
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'"));
    mysqli_query($conn, "INSERT INTO pelanggan (user_id, name, alamat, nomor) 
                         VALUES ('$user_id', '{$user['name']}', '', '')");
    $pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE user_id='$user_id'"));
}

// Ambil mobil tersedia
$mobil = mysqli_query($conn, "SELECT * FROM mobil WHERE status='tersedia'");

// Jika form disubmit
if (isset($_POST['sewa'])) {
    $id_mobil    = $_POST['id_mobil'];
    $tgl_sewa    = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali'];
    $alamat      = $_POST['alamat'];
    $nomor       = $_POST['nomor'];

    // Update data pelanggan dengan alamat & nomor terbaru
    mysqli_query($conn, "UPDATE pelanggan 
                         SET alamat='$alamat', nomor='$nomor' 
                         WHERE id='{$pelanggan['id']}'");

    // Ambil harga mobil
    $m = mysqli_fetch_assoc(mysqli_query($conn, "SELECT harga_sewa FROM mobil WHERE id=$id_mobil"));
    $harga = $m['harga_sewa'];

    // Hitung lama sewa
    $lama  = (strtotime($tgl_kembali) - strtotime($tgl_sewa)) / (60*60*24);
    if ($lama < 1) $lama = 1;
    $total = $lama * $harga;

    // Simpan transaksi
    mysqli_query($conn, "INSERT INTO transaksi (id_pelanggan, id_mobil, tgl_sewa, tgl_kembali, total_bayar) 
                         VALUES ('{$pelanggan['id']}','$id_mobil','$tgl_sewa','$tgl_kembali','$total')");

    // Ubah status mobil
    mysqli_query($conn, "UPDATE mobil SET status='disewa' WHERE id=$id_mobil");

    // Tampilan sukses dengan Tailwind
    echo '
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Sewa Berhasil</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-green-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-xl text-center max-w-md">
            <div class="text-5xl mb-4">✅</div>
            <h1 class="text-2xl font-bold text-green-600 mb-2">Sewa Berhasil!</h1>
            <p class="text-gray-600 mb-6">
                Terima kasih telah menyewa mobil di <b>Rental Mobil Jaya</b>.<br>
                Silakan cek detail transaksi Anda di dashboard.
            </p>
            <a href="../dashboard.php" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow-md font-semibold transition">
                ⬅ Kembali ke Dashboard
            </a>
        </div>
    </body>
    </html>
    ';
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-3xl bg-white shadow-lg rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="bg-blue-600 text-white px-6 py-4">
        <h2 class="text-xl font-bold">🚘 Rental Mobil Jaya</h2>
    </div>

    <!-- Body -->
    <div class="p-6">
        <!-- Data pelanggan -->
        <h3 class="text-lg font-semibold mb-2">📌 Data Pelanggan</h3>
        <p class="mb-2"><b>Nama:</b> <?= $pelanggan['name'] ?></p>

        <form method="POST" class="space-y-4">
            <!-- Input Alamat -->
            <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <input type="text" name="alamat" 
                    class="w-full rounded-lg border border-gray-400 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                    value="<?= htmlspecialchars($pelanggan['alamat']) ?>" required>
            </div>

            <!-- Input Nomor HP -->
                <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" name="nomor" 
                        class="w-full rounded-lg border border-gray-400 bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                        value="<?= htmlspecialchars($pelanggan['nomor']) ?>" required>
                </div>

            <hr class="my-4">

            <!-- Pilih mobil -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Mobil</label>
                <div class="grid md:grid-cols-2 gap-4">
                    <?php while ($m = mysqli_fetch_assoc($mobil)) { ?>
                        <label class="border rounded-xl p-4 cursor-pointer hover:shadow-lg transition bg-gray-50 flex flex-col items-center">
                            <input type="radio" name="id_mobil" value="<?= $m['id'] ?>" class="mb-2" required>
                            <span class="font-semibold"><?= $m['merk'] ?> - <?= $m['name'] ?></span>
                            <span class="text-sm text-gray-600">Rp<?= number_format($m['harga_sewa'],0,',','.') ?>/hari</span>
                            <img src="../uploads/mobil/<?= $m['gambar'] ?: 'default.jpg' ?>" class="mt-2 rounded-lg max-h-40 object-cover">
                        </label>
                    <?php } ?>
                </div>
            </div>

            <!-- Tanggal sewa -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sewa</label>
                <input type="date" name="tgl_sewa" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Tanggal kembali -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
                <input type="date" name="tgl_kembali" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Tombol -->
            <button type="submit" name="sewa" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                Sewa Sekarang
            </button>
        </form>
    </div>

    <!-- Footer -->
    <div class="bg-gray-100 text-center py-3">
        <a href="../dashboard.php" class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md transition">
            ⬅ Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>

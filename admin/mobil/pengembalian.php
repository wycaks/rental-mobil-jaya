<?php
session_start();
include "../../config.php"; // sesuaikan path config

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi
$query = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) == 0) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$data = mysqli_fetch_assoc($result);

// Proses update pengembalian
if (isset($_POST['konfirmasi'])) {
    $tgl_pengembalian = date("Y-m-d");

    // Update transaksi jadi selesai
    $update_transaksi = "UPDATE transaksi 
                         SET status = 'Selesai', tgl_pengembalian = '$tgl_pengembalian' 
                         WHERE id_transaksi = '$id_transaksi'";
    mysqli_query($koneksi, $update_transaksi);

    // Update status mobil jadi tersedia kembali
    $id_mobil = $data['id_mobil'];
    $update_mobil = "UPDATE mobil SET status = 'Tersedia' WHERE id_mobil = '$id_mobil'";
    mysqli_query($koneksi, $update_mobil);

    echo "<script>alert('Pengembalian berhasil dikonfirmasi!'); window.location='transaksi.index.php';</script>";
    exit;
}
?>

<h2>Konfirmasi Pengembalian Mobil</h2>
<p>Mobil: <?= $data['id_mobil']; ?></p>
<p>ID Transaksi: <?= $data['id_transaksi']; ?></p>
<p>Status: <?= $data['status']; ?></p>

<form method="POST">
    <button type="submit" name="konfirmasi">Konfirmasi Pengembalian</button>
    <a href="transaksi.index.php">Batal</a>
</form>

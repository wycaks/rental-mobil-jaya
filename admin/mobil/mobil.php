<?php
include '../../config.php';

// Tambah mobil
if (isset($_POST['tambah'])) {
    $merk  = $_POST['merk'];
    $name  = $_POST['name'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];
    $folder = "../../uploads/mobil/";
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    if ($gambar != "") {
        move_uploaded_file($tmp, $folder.$gambar);
    } else {
        $gambar = "default.jpg";
    }

    mysqli_query($conn, "INSERT INTO mobil (merk, name, gambar, harga_sewa, status) 
                         VALUES ('$merk','$name','$gambar','$harga','$status')");
}

// Update mobil
if (isset($_POST['edit'])) {
    $id    = $_POST['id'];
    $merk  = $_POST['merk'];
    $name  = $_POST['name'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        $folder = "../../uploads/mobil/";
        move_uploaded_file($tmp, $folder.$gambar);
        $q = "UPDATE mobil SET merk='$merk', name='$name', harga_sewa='$harga', status='$status', gambar='$gambar' WHERE id=$id";
    } else {
        $q = "UPDATE mobil SET merk='$merk', name='$name', harga_sewa='$harga', status='$status' WHERE id=$id";
    }

    mysqli_query($conn, $q);
    echo "<script>alert('Mobil berhasil diupdate!'); window.location='mobil.php';</script>";
    exit();
}

// Hapus mobil
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_mobil=$id"); 
    mysqli_query($conn, "DELETE FROM mobil WHERE id=$id");

    echo "<script>alert('Mobil berhasil dihapus permanen!'); window.location='mobil.php';</script>";
    exit();
}

// Ambil data
$result = mysqli_query($conn, "SELECT * FROM mobil");

// Jika klik tombol edit, ambil data mobil yang dipilih
$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mobil WHERE id=$idEdit"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mobil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-xl p-6">
        <h2 class="text-2xl font-bold mb-4">🚗 Data Mobil</h2>

        <!-- Form Tambah Mobil -->
        <?php if (!$editData) { ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-3 mb-6">
            <h3 class="text-lg font-semibold">Tambah Mobil</h3>
            <div>
                <label class="block">Merk</label>
                <input type="text" name="merk" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Nama</label>
                <input type="text" name="name" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Harga Sewa</label>
                <input type="number" name="harga" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="tersedia">Tersedia</option>
                    <option value="disewa">Disewa</option>
                    <option value="perbaikan">Perbaikan</option>
                </select>
            </div>
            <div>
                <label class="block">Gambar</label>
                <input type="file" name="gambar" class="w-full border rounded px-3 py-2">
            </div>
            <button type="submit" name="tambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                ➕ Tambah Mobil
            </button>
        </form>
        <?php } ?>

        <!-- Form Edit Mobil -->
        <?php if ($editData) { ?>
        <form method="POST" enctype="multipart/form-data" class="space-y-3 mb-6">
            <h3 class="text-lg font-semibold">✏️ Edit Mobil ID <?= $editData['id'] ?></h3>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <div>
                <label class="block">Merk</label>
                <input type="text" name="merk" value="<?= $editData['merk'] ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Nama</label>
                <input type="text" name="name" value="<?= $editData['name'] ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Harga Sewa</label>
                <input type="number" name="harga" value="<?= $editData['harga_sewa'] ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="tersedia" <?= ($editData['status']=="tersedia" ? "selected" : "") ?>>Tersedia</option>
                    <option value="disewa" <?= ($editData['status']=="disewa" ? "selected" : "") ?>>Disewa</option>
                    <option value="perbaikan" <?= ($editData['status']=="perbaikan" ? "selected" : "") ?>>Perbaikan</option>
                </select>
            </div>
            <div>
                <label class="block">Gambar Saat Ini</label>
                <?php if ($editData['gambar'] != "") { ?>
                    <img src="../../uploads/mobil/<?= $editData['gambar'] ?>" width="120" class="rounded mb-2">
                <?php } else { ?>
                    <p class="text-gray-500">Tidak ada gambar</p>
                <?php } ?>
                <input type="file" name="gambar" class="w-full border rounded px-3 py-2">
            </div>
            <div class="space-x-2">
                <button type="submit" name="edit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    💾 Simpan Perubahan
                </button>
                <a href="mobil.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
            </div>
        </form>
        <?php } ?>

        <!-- Tabel Data Mobil -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-3 py-2">ID</th>
                        <th class="border px-3 py-2">Merk</th>
                        <th class="border px-3 py-2">Nama</th>
                        <th class="border px-3 py-2">Gambar</th>
                        <th class="border px-3 py-2">Status</th>
                        <th class="border px-3 py-2">Harga</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2 text-center"><?= $row['id'] ?></td>
                        <td class="border px-3 py-2"><?= $row['merk'] ?></td>
                        <td class="border px-3 py-2"><?= $row['name'] ?></td>
                        <td class="border px-3 py-2 text-center">
                            <?php if ($row['gambar'] != "") { ?>
                                <img src="../../uploads/mobil/<?= $row['gambar'] ?>" width="100" class="rounded">
                            <?php } ?>
                        </td>
                        <td class="border px-3 py-2 text-center"><?= ucfirst($row['status']) ?></td>
                        <td class="border px-3 py-2 text-right">Rp<?= number_format($row['harga_sewa'],0,',','.') ?></td>
                        <td class="border px-3 py-2 text-center space-x-2">
                            <a href="?edit=<?= $row['id'] ?>" 
                               class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition">
                                ✏️ Edit
                            </a>
                            <a href="?hapus=<?= $row['id'] ?>" 
                               onclick="return confirm('Yakin hapus mobil ini permanen?')" 
                               class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                                🗑️ Hapus
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="../../dashboard.php" class="inline-block bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded">
                ⬅ Kembali ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>

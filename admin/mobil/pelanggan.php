<?php
include '../../config.php';

// Tambah pelanggan
if (isset($_POST['tambah'])) {
    $name   = $_POST['name'];
    $alamat = $_POST['alamat'];
    $nomor  = $_POST['nomor'];

    mysqli_query($conn, "INSERT INTO pelanggan (name, alamat, nomor) VALUES ('$name','$alamat','$nomor')");
    header("Location: pelanggan.php");
    exit();
}

// Hapus pelanggan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pelanggan WHERE id=$id");
    header("Location: pelanggan.php");
    exit();
}

// Ambil data pelanggan untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editData = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE id=$id"));
}

// Update pelanggan
if (isset($_POST['update'])) {
    $id     = $_POST['id'];
    $name   = $_POST['name'];
    $alamat = $_POST['alamat'];
    $nomor  = $_POST['nomor'];

    mysqli_query($conn, "UPDATE pelanggan SET name='$name', alamat='$alamat', nomor='$nomor' WHERE id=$id");
    header("Location: pelanggan.php");
    exit();
}

// Ambil semua data pelanggan
$result = mysqli_query($conn, "SELECT * FROM pelanggan");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Header -->
    <div class="bg-blue-600 text-white px-6 py-4 shadow">
        <h1 class="text-xl font-bold">👥 Kelola Data Pelanggan</h1>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="bg-white shadow-lg rounded-xl p-6 mb-6">
            <?php if ($editData) { ?>
                <!-- Form Edit -->
                <h2 class="text-lg font-semibold mb-4">✏️ Edit Pelanggan</h2>
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                    <div>
                        <label class="block">Nama</label>
                        <input type="text" name="name" value="<?= $editData['name'] ?>" required 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block">Alamat</label>
                        <input type="text" name="alamat" value="<?= $editData['alamat'] ?>" required 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block">Nomor HP</label>
                        <input type="text" name="nomor" value="<?= $editData['nomor'] ?>" required 
                               class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="space-x-2">
                        <button type="submit" name="update" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">💾 Update</button>
                        <a href="pelanggan.php" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Batal</a>
                    </div>
                </form>
            <?php } else { ?>
                <!-- Form Tambah -->
                <h2 class="text-lg font-semibold mb-4">➕ Tambah Pelanggan</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block">Nama</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block">Alamat</label>
                        <input type="text" name="alamat" required class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block">Nomor HP</label>
                        <input type="text" name="nomor" required class="w-full border rounded px-3 py-2">
                    </div>

                    <button type="submit" name="tambah" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Tambah</button>
                </form>
            <?php } ?>
        </div>

        <!-- Tabel Data Pelanggan -->
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-lg font-semibold mb-4">📋 Daftar Pelanggan</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-3 py-2">ID</th>
                            <th class="border px-3 py-2">Nama</th>
                            <th class="border px-3 py-2">Alamat</th>
                            <th class="border px-3 py-2">Nomor HP</th>
                            <th class="border px-3 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2 text-center"><?= $row['id'] ?></td>
                            <td class="border px-3 py-2"><?= $row['name'] ?></td>
                            <td class="border px-3 py-2"><?= $row['alamat'] ?></td>
                            <td class="border px-3 py-2"><?= $row['nomor'] ?></td>
                            <td class="border px-3 py-2 text-center space-x-2">
                                <a href="?edit=<?= $row['id'] ?>" 
                                   class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">✏️ Edit</a>
                                <a href="?hapus=<?= $row['id'] ?>" 
                                   onclick="return confirm('Yakin hapus pelanggan ini?')" 
                                   class="inline-block bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">🗑️ Hapus</a>
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

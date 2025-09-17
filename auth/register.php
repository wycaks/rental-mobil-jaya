<?php
session_start();
include '../config.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $pass  = md5($_POST['password']); // simpan password pakai MD5

    // Role user = 2 (user biasa)
    $role_id = 2;

    // Simpan ke database
    $query = "INSERT INTO users (email, password, name, role_id) 
              VALUES ('$email', '$pass', '$name', '$role_id')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $success = "Registrasi berhasil. Silakan <a href='login.php'>Login</a>";
    } else {
        $error = "Gagal registrasi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://picsum.photos/1920/1080?blur=2') no-repeat center center fixed;
            background-size: cover;
        }
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }
        .card-register {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
        }
    </style>
</head>
<body>
<div class="overlay"></div>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card card-register shadow-lg p-4" style="width: 450px;">
        <h3 class="text-center mb-4">Register Akun</h3>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>
        <?php if (!empty($success)) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php } ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="register" class="btn btn-success w-100">Daftar</button>
        </form>

        <p class="mt-3 text-center text-dark">Sudah punya akun?
            <a href="login.php" class="text-primary">Login</a>
        </p>
    </div>
</div>
</body>
</html>

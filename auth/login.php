<?php
session_start();
include '../config.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass  = md5($_POST['password']); // ubah ke md5

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$pass'");
    $user  = mysqli_fetch_assoc($query);

    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['role_id']  = $user['role_id'];
        $_SESSION['name']     = $user['name'];

        header("Location: ../dashboard.php");
    } else {
        $error = "Login gagal! Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://picsum.photos/1920/1080?blur=3') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }
        .card-login {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-login:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .card-login h3 {
            font-weight: 700;
            color: #0d6efd;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px;
        }
        .btn-primary {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>
<div class="overlay"></div>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card card-login shadow-lg" style="width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center text-dark">Belum punya akun? 
            <a href="register.php" class="text-primary fw-semibold">Register</a>
        </p>
    </div>
</div>

</body>
</html>

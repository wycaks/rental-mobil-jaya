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
        echo "Login gagal!";
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
    <style>
        body {
            background: url('https://picsum.photos/1920/1080?blur=3') no-repeat center center fixed;
            background-size: cover;
        }
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: -1;
        }
        .card-login {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
        }
    </style>
</head>
<body>
<div class="overlay"></div>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card card-login shadow-lg p-4" style="width: 400px;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>
        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center text-dark">Belum punya akun? 
            <a href="register.php" class="text-primary">Register</a>
        </p>
    </div>
</div>
</body>
</html>
<form method="POST">
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <button type="submit" name="login">Login</button>
</form>

<p><a href="register.php">Register</a></p>

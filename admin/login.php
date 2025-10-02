<?php
session_start();
include("../inc/inc_koneksi.php"); // pastikan file koneksi ada

$err = "";
$username = isset($_POST['username']) ? trim($_POST['username']) : '';

if (isset($_POST['Login'])) {
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($username === '' || $password === '') {
        $err = "Silakan masukkan username dan password";
    } else {
        // Escape untuk mencegah SQL Injection
        $username_safe = mysqli_real_escape_string($koneksi, $username);

        // Query admin berdasarkan username
        $sql = "SELECT * FROM admin WHERE username = '$username_safe' LIMIT 1";
        $q = mysqli_query($koneksi, $sql);

        if (!$q) {
            $err = "Terjadi kesalahan database: " . mysqli_error($koneksi);
        } else {
            $r = mysqli_fetch_assoc($q);

            // Cek password plain text
            if ($r && $password === $r['password']) {
                $_SESSION['admin_username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $err = "Username atau password salah!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
            animation: fadeIn 0.5s ease-in-out;
        }
        .login-container h1 {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .form-control { border-radius: 10px; }
        .btn-primary { width: 100%; border-radius: 10px; font-weight: 600; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>TutorKu</h1>
        <h1>Login Admin</h1>
        <?php if ($err): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" 
                       placeholder="Masukkan username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
            </div>
            <button type="submit" class="btn btn-primary" name="Login">Login</button>
        </form>
    </div>
</body>
</html>

<?php 
session_start();
if(isset($_SESSION['admin_username'])!=''){
    header("location:index.php");
    exit();
}
include("../inc/inc_koneksi.php");

$username   ="";
$password   ="";
$err        ="";

if(isset($_POST['Login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    if($username == '' or $password == ''){
        $err = "Silakan masukkan semua isian";
    }else{
        $sql1 = "select * from admin where username = '$username'";
        $q1 = mysqli_query($koneksi,$sql1);
        $r1 = mysqli_fetch_array($q1);
        $n1 = mysqli_num_rows($q1);

        if($n1 < 1){
            $err ="username tidak ditemukan";
        }elseif($r1['password'] != md5($password)){
            $err = "password yang kamu masukkan tidak sesuai";
        }else{
            $_SESSION['admin_username']=$username;
            header("location:index.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <title>Login Admin</title>
</head>
<body style="width:100%;max-width:330px;margin:auto;padding:15px">
    <form action="" method="POST">
        <h1>Login Admin</h1>
        <?php 
        if($err){
        ?>
        <div class="alert alert-danger">
            <?php echo $err ?>
        </div>
        <?php
        }
        ?>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username anda"value="<?php echo $username ?>" />
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password"/>
        </div>
        <button type="submit" class="btn btn-primary" name="Login">Login</button>
    </form>
</body>
</html>
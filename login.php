<?php include("inc_header.php") ?>
<h3>Login ke Sistem</h3>

<?php
$email    = "";
$password = "";
$role     = "";
$err      = "";

if (isset($_POST['login'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if ($email == '' || $password == '' || $role == '') {
        $err .= "<li>Silakan masukkan semua isian</li>";
    } else {
        $email_escaped = mysqli_real_escape_string($koneksi, $email);
        $tabel = ($role == 'tutor') ? 'tutors' : 'members';

        $sql1 = "SELECT * FROM $tabel WHERE email = '$email_escaped' LIMIT 1";
        $q1 = mysqli_query($koneksi, $sql1);

        if ($q1 && mysqli_num_rows($q1) > 0) {
            $r1 = mysqli_fetch_assoc($q1);

            if ($r1['status'] != '1') {
                $err .= "<li>Akun belum aktif</li>";
            } elseif ($r1['password'] != md5($password)) {
                $err .= "<li>Password tidak sesuai</li>";
            }
        } else {
            $err .= "<li>Akun tidak ditemukan</li>";
        }

        if (empty($err)) {
            if ($role == 'member') {
                $_SESSION['id_members'] = $r1['id'];
                $_SESSION['members_email'] = $r1['email'];
                $_SESSION['members_nama_lengkap'] = $r1['nama_lengkap'];
                $_SESSION['is_langganan'] = $r1['is_langganan'];
                $_SESSION['role'] = 'member';
                header("Location: content.php");
            } else {
                $_SESSION['id_tutor'] = $r1['id'];
                $_SESSION['tutor_email'] = $r1['email'];
                $_SESSION['tutor_nama'] = $r1['nama'];
                $_SESSION['role'] = 'tutor';
                header("Location: tutor_dashboard.php");
            }
            exit();
        }
    }
}
?>

<?php if ($err) { ?>
    <div class='error'>
        <ul class='pesan'><?php echo $err ?></ul>
    </div>
<?php } ?>

<form action="" method="POST">
    <table>
        <tr>
            <td class="label">Email</td>
            <td><input type="text" name="email" class="input" value="<?php echo htmlentities($email) ?>"/></td>
        </tr>
        <tr>
            <td class="label">Password</td>
            <td><input type="password" name="password" class="input" /></td>
        </tr>
        <tr>
            <td class="label">Login Sebagai</td>
            <td>
                <select name="role" class="input">
                    <option value="">-- Pilih --</option>
                    <option value="member" <?php if ($role == 'member') echo 'selected'; ?>>Member</option>
                    <option value="tutor" <?php if ($role == 'tutor') echo 'selected'; ?>>Tutor</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="login" value="Login" class="tbl-biru"/></td>
        </tr>
    </table>
</form>

<?php include("inc_footer.php") ?>

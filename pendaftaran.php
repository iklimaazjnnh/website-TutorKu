<?php include("inc_header.php") ?>
<?php 
if(isset($_SESSION['members_email']) != ''){ // sudah login
    header("location:index.php");
    exit();
}
?>

<h3>Pendaftaran</h3>
<?php 
$email          = "";
$nama_lengkap   = "";
$err            = "";
$sukses         = "";

if(isset($_POST['simpan'])){
    $email                  = $_POST['email'];
    $nama_lengkap           = $_POST['nama_lengkap'];
    $password               = $_POST["password"];
    $konfirmasi_password    = $_POST["konfirmasi_password"];

    if($email == '' || $nama_lengkap == '' || $konfirmasi_password == '' || $password == '' ){
        $err .= "<li>Silakan masukan semua isian.</li>";
    }

    // cek di database, apakah email sudah ada
    if($email != ''){
        $sql1 = "SELECT email FROM members WHERE email = '".mysqli_real_escape_string($koneksi,$email)."'";
        $q1 = mysqli_query($koneksi,$sql1);
        $n1 = mysqli_num_rows($q1);
        if($n1 > 0){
            $err .= "<li>Email yang kamu masukan sudah terdaftar.</li>";
        }

        // validasi email
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $err .= "<li>Email yang kamu masukan tidak valid.</li>";
        }
    }

    // cek password
    if($password != $konfirmasi_password) {
        $err .= "<li>Password dan konfirmasi password tidak sesuai.</li>";
    }
    if (strlen($password) < 6){
        $err .= "<li>Panjang password minimal 6 karakter.</li>";
    }

    if(empty($err)){
        $status = md5(rand(0,1000));
        $judul_email = "Halaman Konfirmasi Pendaftaran";
        $isi_email  = "Akun yang kamu miliki dengan email <b>$email</b> telah siap digunakan.<br/>";
        $isi_email .= "Silakan lakukan aktivasi melalui link berikut:<br/>";
        $isi_email .= url_dasar()."/verifikasi.php?email=".urlencode($email)."&kode=$status";

        // kirim email
        $kirim = kirim_email($email, $nama_lengkap, $judul_email, $isi_email);

        if (strpos($kirim, "gagal") !== false) {
            // JIKA EMAIL GAGAL DIKIRIM, TIDAK MASUKKAN DATA
            $err .= "<li>Gagal mengirim email verifikasi. Coba lagi atau hubungi admin.</li>";
        } else {
            // EMAIL SUKSES DIKIRIM → SIMPAN DATA KE DATABASE
            $sql_insert = "INSERT INTO members (email, nama_lengkap, password, status) VALUES (
                '".mysqli_real_escape_string($koneksi, $email)."',
                '".mysqli_real_escape_string($koneksi, $nama_lengkap)."',
                '".md5($password)."',
                '".mysqli_real_escape_string($koneksi, $status)."'
            )";

            $q_insert = mysqli_query($koneksi, $sql_insert);
            if($q_insert){
                $sukses = "Proses berhasil. Silakan cek email kamu untuk verifikasi.";
                // Kosongkan form
                $email = "";
                $nama_lengkap = "";
            } else {
                $err .= "<li>Gagal menyimpan data ke database: ".mysqli_error($koneksi)."</li>";
            }
        }
    }
}
?>

<?php if($err){echo "<div class='error'><ul>$err</ul></div>";} ?>
<?php if($sukses){echo "<div class='sukses'>$sukses</div>";} ?>

<form action="" method="POST">
    <table>
        <tr>
            <td class="label">Email</td>
            <td><input type="text" name="email" class="input" value="<?php echo htmlspecialchars($email) ?>"/></td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td><input type="text" name="nama_lengkap" class="input" value="<?php echo htmlspecialchars($nama_lengkap) ?>"/></td>
        </tr>
        <tr>
            <td class="label">Password</td>
            <td><input type="password" name="password" class="input"/></td>
        </tr>
        <tr>
            <td class="label">Konfirmasi Password</td>
            <td>
                <input type="password" name="konfirmasi_password" class="input"/>
                <br/>
                Sudah punya akun? Silakan <a href='<?php echo url_dasar()?>/login.php'>login</a>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="simpan" value="Simpan" class="tbl-biru"/></td>
        </tr>
    </table>
</form>

<?php include("inc_footer.php") ?>

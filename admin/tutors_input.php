<?php include("inc_header.php") ?>
<?php 
$nama       = "";
$email      = "";
$password   = "";
$isi        = "";
$foto       = "";
$foto_name  = "";

$error      = "";
$sukses     = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = "";
}

if ($id != "") {
    $sql1 = "SELECT * FROM tutors WHERE id = '$id'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);
    if ($r1) {
        $nama     = $r1['nama'];
        $email    = $r1['email'];
        $password = ""; // kosongkan field password untuk keamanan
        $isi      = $r1['isi'];
        $foto     = $r1['foto'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $isi      = $_POST['isi'];

    // Hanya hash jika password diisi
    $password = ($_POST['password'] != "") ? md5($_POST['password']) : "";

    if ($nama == '' || $isi == '') {
        $error = "Silakan masukkan data nama dan isi.";
    }

    if ($_FILES['foto']['name']) {
        $foto_name = $_FILES['foto']['name'];
        $foto_file = $_FILES['foto']['tmp_name'];
        $detail_file = pathinfo($foto_name);
        $foto_ekstensi = strtolower($detail_file['extension']);

        $ekstensi_yang_diizinkan = array("jpg", "jpeg", "png", "gif");
        if (!in_array($foto_ekstensi, $ekstensi_yang_diizinkan)) {
            $error = "Ekstensi file yang diizinkan: jpg, jpeg, png, gif.";
        }
    }

    if (empty($error)) {
        if ($foto_name) {
            $direktori = "../gambar";
            @unlink($direktori . "/$foto");
            $foto_name = "tutors_" . time() . "_" . $foto_name;
            move_uploaded_file($foto_file, $direktori . "/" . $foto_name);
            $foto = $foto_name;
        } else {
            $foto_name = $foto;
        }

        if ($id != "") {
            $sql1 = "UPDATE tutors SET 
                        nama = '$nama',
                        email = '$email',
                        foto = '$foto_name',
                        isi = '$isi',
                        tgl_isi = NOW()";

            // Jika password diisi, update juga password
            if ($password != "") {
                $sql1 .= ", password = '$password'";
            }

            $sql1 .= " WHERE id = '$id'";
        } else {
            $sql1 = "INSERT INTO tutors (nama, email, password, foto, isi, status, tgl_isi)
                     VALUES ('$nama', '$email', '$password', '$foto_name', '$isi', '1', NOW())";
        }

        $q1 = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses = "Sukses menyimpan data.";
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}
?>

<h1>Halaman Admin Input Data Tutors</h1>
<div class="mb-3-row">
    <a href="tutors.php">&laquo; Kembali ke halaman tutors admin</a>
</div>

<?php if ($error) { ?>
<div class="alert alert-danger" role="alert">
    <?php echo $error ?>
</div>
<?php } ?>

<?php if ($sukses) { ?>
<div class="alert alert-primary" role="alert">
    <?php echo $sukses ?>
</div>
<?php } ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="email" class="col-sm-2 col-form-label">Email</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="password" class="col-sm-2 col-form-label">Password <?php echo ($id != "") ? '(Kosongkan jika tidak ingin mengubah)' : ''; ?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="password" value="">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="foto" class="col-sm-2 col-form-label">Foto</label>
        <div class="col-sm-10">
            <?php if ($foto) { ?>
                <img src="../gambar/<?php echo $foto ?>" style="max-height:100px;max-width:100px"/><br>
            <?php } ?>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="isi" class="col-sm-2 col-form-label">Isi</label>
        <div class="col-sm-10">
            <textarea name="isi" class="form-control" id="summernote"><?php echo $isi ?></textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary"/>
        </div>
    </div>
</form>

<?php include("inc_footer.php") ?>

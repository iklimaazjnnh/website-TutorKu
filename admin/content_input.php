<?php include("inc_header.php") ?>
<?php 
$judul      = "";
$isi        = "";
$foto       = "";
$foto_name  = "";

$error      = "";
$sukses     = "";

if(isset($_GET['id'])){
    $id = $_GET['id'];
}else{
    $id = "";
}

if ($id != "") {
    $sql1 = "SELECT * FROM content WHERE id = '$id'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);
    $judul     = $r1['judul'];
    $isi       = $r1['isi'];
    $foto      = $r1['foto'];
    $id_tutor  = $r1['id_tutor'];


    if($isi == ''){
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])){
    $id_tutor = isset($_POST['id_tutor']) ? $_POST['id_tutor'] : $id_tutor;
    $judul = $_POST['judul'];
    $isi   = $_POST['isi'];

    if ($judul == '' or $isi == '' or $id_tutor == '') {
    $error = "Silakan isi semua kolom, termasuk memilih tutor.";
    }


    if($_FILES['foto']['name']){
        $foto_name = $_FILES['foto']['name'];
        $foto_file = $_FILES['foto']['tmp_name'];
        $detail_file = pathinfo($foto_name);
        $foto_ekstensi = $detail_file['extension'];

        $ekstensi_yang_diizinkan = array("jpg", "jpeg", "png", "gif");
        if(!in_array(strtolower($foto_ekstensi), $ekstensi_yang_diizinkan)){
            $error = "Ekstensi yang diizinkan: jpg, jpeg, png, gif";
        }
    }

    if(empty($error)){
        if($foto_name){
            $direktori = "../gambar";
            @unlink($direktori . "/" . $foto); // delete foto lama jika ada
            $foto_name = "content_" . time() . "_" . $foto_name;
            move_uploaded_file($foto_file, $direktori . "/" . $foto_name);
            $foto = $foto_name;
        } else {
            $foto_name = $foto;
        }

    if ($id == "") {
        $cek = mysqli_query($koneksi, "SELECT id FROM content WHERE id_tutor = '$id_tutor'");
            if(mysqli_num_rows($cek) > 0){
        $error = "Tutor ini sudah memiliki content.";
        }
    }

        if($id != ""){
            $sql1 = "UPDATE content SET judul = '$judul', foto = '$foto_name', isi = '$isi', id_tutor = '$id_tutor', tgl_isi = NOW() WHERE id = '$id'";
        } else {
            $sql1 = "INSERT INTO content (judul, foto, isi, id_tutor) VALUES ('$judul', '$foto_name', '$isi', '$id_tutor')";
        }

        $q1 = mysqli_query($koneksi, $sql1);
        if($q1){
            $sukses = "Sukses menginput data";
        } else {
            $error = "Gagal input data: " . mysqli_error($koneksi);
        }
    }
}
?>
<h1>Halaman Admin Input Data Content</h1>
<div class="mb-3-row">
    <a href="content.php"><< Kembali ke halaman content admin</a>
</div>
<?php if($error): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error ?>
    </div>
<?php endif; ?>

<?php if($sukses): ?>
    <div class="alert alert-primary" role="alert">
        <?php echo $sukses ?>
    </div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label for="judul" class="col-sm-2 col-form-label">Judul</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $judul ?>">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="foto" class="col-sm-2 col-form-label">Foto</label>
        <div class="col-sm-10">
            <?php if($foto): ?>
                <img src="../gambar/<?php echo $foto ?>" style="max-height:100px;max-width:100px" />
            <?php endif; ?>
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
    <label for="id_tutor" class="col-sm-2 col-form-label">Pilih Tutor</label>
    <div class="col-sm-10">
        <select name="id_tutor" class="form-control">
            <option value="">-- Pilih Tutor --</option>
            <?php
            $qtutor = mysqli_query($koneksi, "SELECT id, nama FROM tutors");
            while($rtutor = mysqli_fetch_array($qtutor)){
                $selected = ($rtutor['id'] == @$r1['id_tutor']) ? "selected" : "";
                echo "<option value='{$rtutor['id']}' $selected>{$rtutor['nama']}</option>";
            }
            ?>
        </select>
    </div>
</div>

    <div class="mb-3 row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
        </div>
    </div>
</form>

<?php include("inc_footer.php") ?>

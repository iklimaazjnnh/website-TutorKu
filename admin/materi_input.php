<?php include("inc_header.php") ?>
<?php 
$id_kursus  = "";
$judul      = "";
$video      = "";
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

// Ambil data lama jika edit
if ($id != "") {
    $sql1 = "SELECT * FROM materi WHERE id = '$id'";
    $q1   = mysqli_query($koneksi, $sql1);
    $r1   = mysqli_fetch_array($q1);

    if ($r1) {
        $id_kursus = $r1['id_kursus'];
        $judul     = $r1['judul'];
        $video     = $r1['video'];
        $isi       = $r1['isi'];
        $foto      = $r1['foto'];
    } else {
        $error = "Data tidak ditemukan";
    }
}

// Simpan data baru atau update
if (isset($_POST['simpan'])) {
    $id_kursus = $_POST['id_kursus'];
    $judul     = $_POST['judul'];
    $video     = $_POST['video'];
    $isi       = $_POST['isi'];

    if ($id_kursus == "" || $judul == "" || $video == "") {
        $error = "Silakan lengkapi semua kolom yang wajib diisi.";
    }

    // Cek dan proses upload foto
    if ($_FILES['foto']['name']) {
        $foto_name = $_FILES['foto']['name'];
        $foto_file = $_FILES['foto']['tmp_name'];
        $detail_file = pathinfo($foto_name);
        $foto_ekstensi = strtolower($detail_file['extension']);

        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($foto_ekstensi, $ekstensi_diperbolehkan)) {
            $error = "Ekstensi gambar harus jpg, jpeg, png, atau gif.";
        }
    }

    if (empty($error)) {
        if ($foto_name) {
            $folder = "../gambar";
            @unlink($folder . "/" . $foto); // Hapus foto lama
            $foto_name = "materi_" . time() . "_" . $foto_name;
            move_uploaded_file($foto_file, $folder . "/" . $foto_name);
            $foto = $foto_name;
        }

        if ($id != "") {
            $sql = "UPDATE materi SET 
                        id_kursus = '$id_kursus',
                        judul = '$judul',
                        video = '$video',
                        isi = '$isi',
                        foto = '$foto'
                    WHERE id = '$id'";
        } else {
            $sql = "INSERT INTO materi (id_kursus, judul, video, isi, foto)
                    VALUES ('$id_kursus', '$judul', '$video', '$isi', '$foto')";
        }

        $q = mysqli_query($koneksi, $sql);
        if ($q) {
            $sukses = "Data berhasil disimpan.";
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}
?>

<h1>Halaman Admin Input Data Materi</h1>
<div class="mb-3-row">
    <a href="materi.php"><< Kembali ke daftar materi</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error ?></div>
<?php endif; ?>

<?php if ($sukses): ?>
    <div class="alert alert-primary"><?php echo $sukses ?></div>
<?php endif; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Kursus</label>
        <div class="col-sm-10">
            <select name="id_kursus" class="form-control">
                <option value="">-- Pilih Kursus --</option>
                <?php
                $res = mysqli_query($koneksi, "SELECT id, judul FROM content ORDER BY judul");
                while ($row = mysqli_fetch_array($res)) {
                    $selected = ($row['id'] == $id_kursus) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['judul']}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Judul Materi</label>
        <div class="col-sm-10">
            <input type="text" name="judul" class="form-control" value="<?php echo $judul ?>">
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Link Video</label>
        <div class="col-sm-10">
            <input type="text" name="video" class="form-control" value="<?php echo $video ?>">
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Foto</label>
        <div class="col-sm-10">
            <?php if ($foto): ?>
                <img src="../gambar/<?php echo $foto ?>" style="max-height: 100px;" />
            <?php endif; ?>
            <input type="file" name="foto" class="form-control">
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Isi Materi</label>
        <div class="col-sm-10">
            <textarea name="isi" class="form-control" id="summernote"><?php echo $isi ?></textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
        </div>
    </div>
</form>

<?php include("inc_footer.php") ?>

<?php include("inc_header.php") ?>

<?php
$sukses = "";
$katakunci = (isset($_GET['katakunci'])) ? trim($_GET['katakunci']) : "";

$op = isset($_GET['op']) ? $_GET['op'] : "";
if ($op == 'delete') {
    $id = $_GET['id'];

    // Ambil nama file foto untuk dihapus
    $sql1 = "SELECT foto FROM materi WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    @unlink("../gambar/" . $r1['foto']); // hapus file foto jika ada

    // Hapus data dari database
    $sql1 = "DELETE FROM materi WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    }
}
?>

<h1>Halaman Admin Materi</h1>

<p>
    <a href="materi_input.php">
        <button class="btn btn-primary">Buat Materi Baru</button>
    </a>
</p>

<?php if ($sukses): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $sukses ?>
    </div>
<?php endif; ?>

<form class="row mb-3">
    <div class="col-auto">
        <input type="text" name="katakunci" class="form-control" placeholder="Masukkan Kata Kunci"
    value="<?php echo $katakunci ?>" oninput="if(this.value===''){ window.location='materi.php'; }">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark">Cari Materi</button>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Judul</th>
            <th>Kursus</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sqltambahan = "";
        $per_halaman = 3;
        if ($katakunci != '') {
            $array_katakunci = explode(" ", $katakunci);
            foreach ($array_katakunci as $kunci) {
                $sqlcari[] = "(judul LIKE '%" . mysqli_real_escape_string($koneksi, $kunci) . "%')";
            }
            $sqltambahan = " WHERE " . implode(" OR ", $sqlcari);
        }

        // Hitung total data
        $sql_count = "SELECT COUNT(*) AS total FROM materi $sqltambahan";
        $q_count = mysqli_query($koneksi, $sql_count);
        $r_count = mysqli_fetch_assoc($q_count);
        $total_data = $r_count['total'];

        // Pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $mulai = ($page - 1) * $per_halaman;
        $pages = ceil($total_data / $per_halaman);

        // Ambil data dengan limit
        $sql = "SELECT * FROM materi $sqltambahan ORDER BY id DESC LIMIT $mulai, $per_halaman";
        $hasil = mysqli_query($koneksi, $sql);
        $no = $mulai + 1;
        while ($row = mysqli_fetch_array($hasil)) {
        ?>
            <tr>
                <td><?php echo $no++ ?></td>
                <td>
                    <?php if ($row['foto'] != ''): ?>
                        <img src="../gambar/<?php echo $row['foto'] ?>" style="max-height: 60px;">
                    <?php endif; ?>
                </td>
                <td><?php echo $row['judul'] ?></td>
                <td><?php echo $row['id_kursus'] ?></td>
                <td>
                    <a href="materi_input.php?id=<?php echo $row['id'] ?>">
                        <button class="btn btn-warning btn-sm">Edit</button>
                    </a>
                    <a href="materi.php?op=delete&id=<?php echo $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Pagination -->
<?php if ($pages > 1): ?>
<nav>
    <ul class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="materi.php?katakunci=<?php echo urlencode($katakunci) ?>&page=<?php echo $i ?>"><?php echo $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php include("inc_footer.php") ?>

<?php include("inc_header.php") ?>

<?php
$sukses = "";
$katakunci = (isset($_GET['katakunci'])) ? trim($_GET['katakunci']) : "";
$op = isset($_GET['op']) ? $_GET['op'] : "";

if ($op == 'delete') {
    $sql1 = "DELETE FROM members WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil hapus data";
    }
}
?>

<h1>Halaman Admin Members</h1>

<?php if ($sukses): ?>
<div class="alert alert-primary" role="alert">
    <?= $sukses ?>
</div>
<?php endif; ?>

<form class="row g-3" method="get" action="">
    <div class="col-auto">
        <input type="text" name="katakunci" class="form-control" placeholder="Masukkan Kata Kunci"
            value="<?= $katakunci ?>" 
            oninput="if(this.value===''){ window.location='members.php'; }">
    </div>
    <div class="col-auto">
        <input type="submit" name="cari" value="Cari Members" class="btn btn-secondary"/>
    </div>
</form>

<table class="table table-striped">
    <thead>
        <tr>
            <th class="col-1">No</th>
            <th class="col-2">ID Member</th>
            <th class="col-2">Email</th>
            <th>Nama</th>
            <th class="col-2">Status</th>
            <th class="col-2">Langganan</th>
        </tr>
    </thead>
    <tbody>
<?php
$sqltambahan = "";
$per_halaman = 3;

if ($katakunci != '') {
    $array_katakunci = explode(" ", $katakunci);
    for ($x = 0; $x < count($array_katakunci); $x++) {
        $sqlcari[] = "(m.nama_lengkap LIKE '%" . $array_katakunci[$x] . "%' 
                      OR m.email LIKE '%" . $array_katakunci[$x] . "%')";
    }
    $sqltambahan = " WHERE " . implode(" OR ", $sqlcari);
}

$sql1 = "SELECT m.*, 
        CASE 
            WHEN l.id IS NOT NULL THEN 1
            ELSE 0
        END AS is_langganan
        FROM members m
        LEFT JOIN langganan l 
            ON m.id = l.id_members
        $sqltambahan";

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
$q1 = mysqli_query($koneksi, $sql1);
$total = mysqli_num_rows($q1);
$pages = ceil($total / $per_halaman);
$nomor = $mulai + 1;
$sql1 = $sql1 . " ORDER BY m.id DESC LIMIT $mulai,$per_halaman";

$q1 = mysqli_query($koneksi, $sql1);
while ($r1 = mysqli_fetch_array($q1)) {
?>
    <tr>
        <td><?= $nomor++ ?></td>
        <td><?= $r1['id'] ?></td>
        <td><?= $r1['email'] ?></td>
        <td><?= $r1['nama_lengkap'] ?></td>
        <td>
            <?php if ($r1['status'] == '1'): ?>
                <span class="badge bg-success">Aktif</span>
            <?php else: ?>
                <span class="badge bg-light">Belum Aktif</span>
            <?php endif; ?>
        </td>
        <td>
            <?php if ($r1['is_langganan'] == '1'): ?>
                <span class="badge bg-success">Berlangganan</span>
            <?php else: ?>
                <span class="badge bg-light">Belum Berlangganan</span>
            <?php endif; ?>
        </td>
    </tr>
<?php
}
?>
    </tbody>
</table>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php 
        $cari = isset($_GET['cari']) ? $_GET['cari'] : "";
        for ($i=1; $i <= $pages; $i++): ?>
        <li class="page-item">
            <a class="page-link" href="members.php?katakunci=<?= $katakunci ?>&cari=<?= $cari ?>&page=<?= $i ?>">
                <?= $i ?>
            </a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php include("inc_footer.php") ?>

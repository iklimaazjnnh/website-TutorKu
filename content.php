<?php
include("inc/inc_koneksi.php");
include("inc_header.php");
?>


<h3 class="judul-sambutan">Welcome, <?php echo $_SESSION['members_nama_lengkap']; ?></h3>

<h4 class="judul-kategori">All Courses</h4>
<div class="course-container">
<?php 
$sql = "SELECT * FROM content ORDER BY id DESC";
$q = mysqli_query($koneksi, $sql);
if (!$q) {
    echo "Terjadi kesalahan saat mengambil data: " . mysqli_error($koneksi);
} else {
    while ($r = mysqli_fetch_array($q)) {
?>
    <div class="course-card">
        <img src="gambar/<?php echo $r['foto']; ?>" alt="<?php echo $r['judul']; ?>">
        <h5><?php echo $r['judul']; ?></h5>
        <a href="materi.php?id=<?php echo $r['id']; ?>" class="btn">View Course</a>
    </div>
<?php 
    }
}
?>
</div>

<?php include("inc_footer.php"); ?>


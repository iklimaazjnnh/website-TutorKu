<?php
include("inc/inc_koneksi.php");
include("inc_header.php");
?>

<div class="container">
    <h2 class="mt-4">Akses Materi Lengkap dengan Berlangganan</h2>
    <p>Untuk mengakses semua materi video dalam kursus ini, Anda perlu berlangganan.</p>

    <div class="card mt-4 mb-4" style="max-width: 500px;">
        <div class="card-body">
            <h4 class="card-title">Paket Premium</h4>
            <ul>
                <li>Akses semua video kursus tanpa batas</li>
                <li>Update materi terbaru</li>
                <li>Dukungan langsung dari tutor</li>
            </ul>
            <h5 class="text-success">Rp 50.000 / bulan</h5>

            <form action="proses_berlangganan.php" method="get">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <button type="submit" class="btn btn-success mt-3">Berlangganan Sekarang</button>
            </form>

        </div>
    </div>
</div>

<?php include("inc_footer.php"); ?>

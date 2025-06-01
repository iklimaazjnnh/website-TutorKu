<?php 
include("inc_header.php");

// Cek apakah user login sebagai tutor
if (!isset($_SESSION['id_tutor'])) {
    header("Location: login.php");
    exit();
}

$id_tutor = $_SESSION['id_tutor'];
$nama_tutor = $_SESSION['tutor_nama'];
?>

<h2 style="margin-bottom: 10px;">👋 Selamat datang, <?php echo $nama_tutor ?></h2>
<p>Berikut daftar kursus dan ruang obrolan Anda:</p>

<div class="dashboard-container">
    <?php 
    $sql = "SELECT c.id, c.judul, 
               COUNT(k.id) AS jumlah_pesan 
        FROM content c
        LEFT JOIN chat_room k 
          ON k.id_kursus = c.id 
         AND k.dibaca = '0' 
         AND k.peran_pengirim = 'members'
        WHERE c.id_tutor = ?
        GROUP BY c.id
        ORDER BY c.id DESC";


    $stmt = mysqli_prepare($koneksi, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_tutor);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <div class='card-course'>
                    <h3>{$row['judul']}</h3>
                    <p>💬 Jumlah Pesan: <strong>{$row['jumlah_pesan']}</strong></p>
                    <a href='tutor_chat_room.php?id_kursus={$row['id']}' class='btn-chat'>Buka Chat</a>
                </div>";
            }
        } else {
            echo "<p>⚠️ Belum ada kursus atau pesan.</p>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<p>❌ Gagal memproses data: " . mysqli_error($koneksi) . "</p>";
    }
    ?>
</div>

<style>
.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card-course {
    background-color: #f9f9f9;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 6px solid #007bff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card-course:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.card-course h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #333;
}

.btn-chat {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    margin-top: 10px;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn-chat:hover {
    background-color: #0056b3;
}
</style>

<?php include("inc_footer.php") ?>

<?php
session_start();
include("inc/inc_koneksi.php");
include("inc_header.php");

// Validasi ID Kursus
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

$id_kursus = intval($_GET['id']);

// Pastikan user login (members)
$id_members = $_SESSION['id_members'] ?? null;
if (!$id_members) {
    echo "<h3>Silakan login untuk mengakses kursus</h3>";
    include("inc_footer.php");
    exit;
}

// Ambil Data Kursus
$sql_kursus = "SELECT * FROM content WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql_kursus);
if (!$stmt) {
    die("Query kursus gagal: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, "i", $id_kursus);
mysqli_stmt_execute($stmt);
$result_kursus = mysqli_stmt_get_result($stmt);
$data_kursus = mysqli_fetch_assoc($result_kursus);

if (!$data_kursus) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

// Cek Langganan
$sql_cek = "SELECT id FROM langganan WHERE id_members = ? AND id_kursus = ?";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
mysqli_stmt_bind_param($stmt_cek, "ii", $id_members, $id_kursus);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);
$is_langganan = mysqli_num_rows($result_cek) > 0;
?>

<h3><?= htmlspecialchars($data_kursus['judul']); ?></h3>

<div class="materi-header">
    <h4>Materi Video</h4>

    <!-- Jika member sudah berlangganan, tampilkan icon chat -->
    <?php if ($is_langganan): ?>
        <a href="member_chat_room.php?id_kursus=<?= $id_kursus; ?>" 
           class="chat-icon" 
           id="openChatBtn" 
           data-id-kursus="<?= $id_kursus; ?>">
            💬
            <span id="unreadBadge" 
                  data-id-kursus="<?= $id_kursus; ?>">
                0
            </span>
        </a>
    <?php endif; ?>
</div>

<div class="video-grid">
<?php
$sql_materi = "SELECT * FROM materi WHERE id_kursus = ? ORDER BY tgl_upload ASC";
$stmt_materi = mysqli_prepare($koneksi, $sql_materi);
mysqli_stmt_bind_param($stmt_materi, "i", $id_kursus);
mysqli_stmt_execute($stmt_materi);
$result_materi = mysqli_stmt_get_result($stmt_materi);

$index = 0;
if (mysqli_num_rows($result_materi) > 0) {
    while ($materi = mysqli_fetch_assoc($result_materi)) {
        $index++;
        $id_materi = $materi['id'];
        $video_url = trim($materi['video']);
        $is_locked = !$is_langganan && $index > 3;

        $embed_code = '';
        if (!$is_locked) {
            if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                if (strpos($video_url, 'youtu.be') !== false) {
                    $video_id = str_replace("https://youtu.be/", "", $video_url);
                } else {
                    parse_str(parse_url($video_url, PHP_URL_QUERY), $yt_params);
                    $video_id = $yt_params['v'] ?? '';
                }
                $embed_code = $video_id ? 
                    '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . htmlspecialchars($video_id) . '" frameborder="0" allowfullscreen></iframe>' 
                    : '<p><em>Video belum tersedia</em></p>';
            } elseif ($video_url) {
                $embed_code = '<a href="' . htmlspecialchars($video_url) . '" target="_blank">Lihat Video</a>';
            } else {
                $embed_code = '<p><em>Video belum tersedia</em></p>';
            }
        } else {
            $embed_code = '<div class="locked-video">
                              🔒 <br><small>Berlangganan untuk membuka video ini</small>
                           </div>';
        }

        echo '<div class="video-card">';
        echo '<div class="video-thumbnail">' . $embed_code . '</div>';
        echo '<div class="video-content">
                <h5>' . htmlspecialchars($materi['judul']) . '</h5>
                <p>' . htmlspecialchars(substr(strip_tags($materi['isi']), 0, 70)) . '...</p>
                <div class="meta">
                    <span class="category">Kursus</span> |
                    <span class="date">' . date("d M Y", strtotime($materi['tgl_upload'])) . '</span>
                </div>';
        
        if ($is_locked) {
            echo '<div class="subscribe-box">
                    <a href="langganan.php?id=' . $id_kursus . '" class="btn btn-warning">Berlangganan</a>
                  </div>';
        }

        echo '</div>'; 
        echo '</div>'; 
    }
} else {
    echo "<p>Tidak ada materi untuk kursus ini.</p>";
}
?>
</div>

<?php include("inc_footer.php"); ?>
<script src="js/materi_chat.js"></script>

<!-- 🔔 Script Notifikasi Chat -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const badge = document.getElementById("unreadBadge");
    const idKursus = badge ? badge.dataset.idKursus : null;

    async function checkUnread() {
        if (!idKursus) return;
        try {
            const res = await fetch(`chat_unread.php?id_kursus=${encodeURIComponent(idKursus)}`, { cache: "no-cache" });
            const data = await res.json();

            if (data.unread_count && data.unread_count > 0) {
                badge.textContent = data.unread_count;
                badge.style.display = "inline-block";
            } else {
                badge.style.display = "none";
            }
        } catch (err) {
            console.error("Gagal ambil unread:", err);
        }
    }

    checkUnread();
    setInterval(checkUnread, 5000);
});
</script>

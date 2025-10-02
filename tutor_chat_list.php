<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("inc/inc_koneksi.php");

$id_tutor  = isset($_SESSION['id_tutor']) ? intval($_SESSION['id_tutor']) : 0;
$id_kursus = isset($_GET['id_kursus']) ? intval($_GET['id_kursus']) : 0;
$debug     = isset($_GET['debug']) && $_GET['debug'] === '1';

// Pastikan login sebagai tutor
if (!$id_tutor) {
    include("inc_header.php");
    echo "<div style='max-width:900px;margin:30px auto;color:#a00;'>
            Anda harus login sebagai tutor untuk melihat halaman ini.
          </div>";
    include("inc_footer.php");
    exit;
}

// Query data chat
if ($id_kursus > 0) {
    $sql = "
        SELECT 
          m.id AS member_id,
          m.nama_lengkap AS nama,
          c.id AS id_kursus,
          SUM(CASE WHEN cr.dibaca = 0 AND cr.peran_pengirim = 'members' THEN 1 ELSE 0 END) AS unread_count,
          MAX(cr.waktu) AS last_time
        FROM chat_room cr
        JOIN members m ON m.id = cr.id_pengirim
        JOIN content c ON c.id = cr.id_kursus
        WHERE c.id_tutor = ?
          AND c.id = ?
        GROUP BY m.id, c.id
        ORDER BY last_time DESC
    ";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_tutor, $id_kursus);
} else {
    $sql = "
        SELECT 
          m.id AS member_id,
          m.nama_lengkap AS nama,
          c.id AS id_kursus,
          SUM(CASE WHEN cr.dibaca = 0 AND cr.peran_pengirim = 'members' THEN 1 ELSE 0 END) AS unread_count,
          MAX(cr.waktu) AS last_time
        FROM chat_room cr
        JOIN members m ON m.id = cr.id_pengirim
        JOIN content c ON c.id = cr.id_kursus
        WHERE c.id_tutor = ?
        GROUP BY m.id, c.id
        ORDER BY last_time DESC
    ";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_tutor);
}

// Validasi prepare
if (!$stmt) {
    include("inc_header.php");
    echo "<div style='color:red; max-width:900px; margin:20px auto;'>
            Prepare gagal: " . htmlspecialchars(mysqli_error($koneksi)) . "
          </div>";
    include("inc_footer.php");
    exit;
}

// Eksekusi query
if (!mysqli_stmt_execute($stmt)) {
    include("inc_header.php");
    echo "<div style='color:red; max-width:900px; margin:20px auto;'>
            Execute gagal: " . htmlspecialchars(mysqli_stmt_error($stmt)) . "
          </div>";
    mysqli_stmt_close($stmt);
    include("inc_footer.php");
    exit;
}

$res  = mysqli_stmt_get_result($stmt);
$rows = [];
while ($r = mysqli_fetch_assoc($res)) {
    $rows[] = $r;
}
mysqli_stmt_close($stmt);

include("inc_header.php");
?>

<div class="container-chat">
    <h1 style="font-size:1.8rem; margin-bottom:18px;">💬 Pesan Masuk</h1>

    <?php if (count($rows) === 0): ?>
        <p>
            Tidak ada percakapan untuk 
            <?= $id_kursus > 0 ? "kursus ini" : "saat ini"; ?>.
        </p>
    <?php else: ?>
        <?php foreach ($rows as $r): 
            $badge = ((int)$r['unread_count'] > 0) 
                ? "<span class='badge-unread'>" . (int)$r['unread_count'] . "</span>" 
                : "<span class='small'>0</span>";
            $link = "tutor_chat_room.php?id_kursus=" . urlencode($r['id_kursus']) . 
                    "&id_member=" . urlencode($r['member_id']);
        ?>
            <a class="chat-link" href="<?= $link ?>">
                <div class="chat-link-info">
                    <div class="chat-link-name">
                        <?= htmlspecialchars($r['nama']) ?>
                    </div>
                    <div class="small">
                        Klik untuk membuka percakapan 
                        (kursus: <?= htmlspecialchars($r['id_kursus']) ?>)
                    </div>
                </div>
                <div><?= $badge ?></div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <p style="margin-top:16px;">
        <a href="tutor_dashboard.php">&larr; Kembali ke Dashboard</a>
    </p>
</div>

<?php
// Mode debug
if ($debug) {
    echo "<pre>DEBUG:\n" . htmlspecialchars(print_r($rows, true)) . "</pre>";
}
include("inc_footer.php");
?>

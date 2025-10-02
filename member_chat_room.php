<?php
session_start();
include("inc/inc_koneksi.php");
include("inc_header.php");

$id_members = $_SESSION['id_members'] ?? null;
if (!$id_members) {
    echo "<h3>Silakan login untuk mengakses chat</h3>";
    include("inc_footer.php");
    exit;
}

$id_kursus = 0;
if (isset($_GET['id_kursus'])) $id_kursus = intval($_GET['id_kursus']);
elseif (isset($_GET['id'])) $id_kursus = intval($_GET['id']);

if ($id_kursus <= 0) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

// Ambil data kursus & tutor
$sql_kursus = "SELECT id_tutor, judul FROM content WHERE id = ? LIMIT 1";
$stmt_kursus = mysqli_prepare($koneksi, $sql_kursus);
mysqli_stmt_bind_param($stmt_kursus, "i", $id_kursus);
mysqli_stmt_execute($stmt_kursus);
$res_k = mysqli_stmt_get_result($stmt_kursus);
$data_kursus = mysqli_fetch_assoc($res_k);
mysqli_stmt_close($stmt_kursus);

if (!$data_kursus) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

$id_tutor = intval($data_kursus['id_tutor']);
$judul_kursus = $data_kursus['judul'];

// Cek langganan member
$sql_check = "SELECT id FROM langganan WHERE id_members=? AND id_kursus=? LIMIT 1";
$stmt_check = mysqli_prepare($koneksi, $sql_check);
mysqli_stmt_bind_param($stmt_check, "ii", $id_members, $id_kursus);
mysqli_stmt_execute($stmt_check);
$res_check = mysqli_stmt_get_result($stmt_check);
$is_langganan = (mysqli_num_rows($res_check) > 0);
mysqli_stmt_close($stmt_check);

if (!$is_langganan) {
    echo "<h3>Anda belum berlangganan kursus ini. <a href='langganan.php?id={$id_kursus}'>Berlangganan</a></h3>";
    include("inc_footer.php");
    exit;
}

// Update last_open_chat
$sql_update_last_open = "UPDATE langganan SET last_open_chat = NOW() WHERE id_members = ? AND id_kursus = ?";
$stmt_last = mysqli_prepare($koneksi, $sql_update_last_open);
mysqli_stmt_bind_param($stmt_last, "ii", $id_members, $id_kursus);
mysqli_stmt_execute($stmt_last);
mysqli_stmt_close($stmt_last);

// Tandai pesan tutor sebagai dibaca
$sql_update = "UPDATE chat_room SET dibaca=1 WHERE id_kursus=? AND id_pengirim=? AND peran_pengirim='tutors'";
$stmt_up = mysqli_prepare($koneksi, $sql_update);
mysqli_stmt_bind_param($stmt_up, "ii", $id_kursus, $id_tutor);
mysqli_stmt_execute($stmt_up);
mysqli_stmt_close($stmt_up);
?>

<div class="member-chat-header">
  <a href="materi.php?id=<?= $id_kursus; ?>" class="chat-back-link">← Kembali ke Materi</a>
  <h3 class="chat-title">💬 Room Chat - <?= htmlspecialchars($judul_kursus); ?></h3>
  <div style="width:120px;"></div>
</div>

<div id="chat-box">
  <p class="chat-loading">Memuat pesan...</p>
</div>

<form id="chat-form" method="post">
  <input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">
  <input type="hidden" name="id_pengirim" value="<?= $id_members; ?>">
  <input type="hidden" name="peran_pengirim" value="members">
  <input type="hidden" name="id_member" value="<?= $id_members; ?>">
  <input type="hidden" name="id_penerima" value="<?= $id_tutor; ?>">
  <input type="hidden" name="peran_penerima" value="tutors">

  <textarea name="pesan" id="pesan" placeholder="Ketik pesan..." required></textarea>
  <button type="submit">Kirim</button>
</form>

<script src="js/materi_chat.js"></script>
<script>
// Scroll otomatis ke bawah setelah chat dimuat
const chatBox = document.getElementById("chat-box");
const observer = new MutationObserver(() => {
  chatBox.scrollTop = chatBox.scrollHeight;
});
observer.observe(chatBox, { childList: true });
</script>

<?php include("inc_footer.php"); ?>

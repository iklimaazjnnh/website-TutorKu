<?php
include("inc_header.php");
include("inc/inc_koneksi.php");

if (!isset($_SESSION['id_tutor'])) {
    header("Location: login.php");
    exit();
}

$id_tutor = $_SESSION['id_tutor'];
$id_kursus = isset($_GET['id_kursus']) ? intval($_GET['id_kursus']) : 0;
$id_member = isset($_GET['id_member']) ? intval($_GET['id_member']) : 0;

if ($id_kursus <= 0 || $id_member <= 0) {
    echo "<h3>❌ Kursus atau member tidak valid</h3>";
    echo "<a href='tutor_chat_list.php'>⬅ Kembali ke Daftar Chat</a>";
    include("inc_footer.php");
    exit;
}

// Cek kursus milik tutor login
$sql_cek = "SELECT id, judul FROM content WHERE id = ? AND id_tutor = ? LIMIT 1";
$stmt_cek = mysqli_prepare($koneksi, $sql_cek);
mysqli_stmt_bind_param($stmt_cek, "ii", $id_kursus, $id_tutor);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);
$kursus = mysqli_fetch_assoc($result_cek);
mysqli_stmt_close($stmt_cek);

if (!$kursus) {
    echo "<h3>❌ Kursus tidak ditemukan atau Anda bukan tutor kursus ini</h3>";
    echo "<a href='tutor_chat_list.php'>⬅ Kembali ke Daftar Chat</a>";
    include("inc_footer.php");
    exit;
}

// Tandai pesan member sebagai dibaca
$sql_update = "UPDATE chat_room SET dibaca = 1 WHERE id_kursus = ? AND id_pengirim = ? AND peran_pengirim = 'members'";
$stmt = mysqli_prepare($koneksi, $sql_update);
mysqli_stmt_bind_param($stmt, "ii", $id_kursus, $id_member);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
?>

<h3>🗨️ Ruang Obrolan Kursus - <?= htmlspecialchars($kursus['judul']); ?></h3>

<div class="tutor-chat-container">
    <div id="chat-box" class="tutor-chat-box">
        <p class="tutor-chat-loading">Memuat pesan...</p>
    </div>

    <form id="chat-form" class="tutor-chat-form">
        <input type="hidden" name="id_kursus" value="<?= $id_kursus; ?>">
        <input type="hidden" name="id_member" value="<?= $id_member; ?>">
        <input type="hidden" name="id_pengirim" value="<?= $id_tutor; ?>">
        <input type="hidden" name="peran_pengirim" value="tutors">

        <textarea name="pesan" id="pesan" placeholder="Tulis pesan..." required></textarea>
        <button type="submit">Kirim</button>
    </form>
</div>

<a href="tutor_chat_list.php?id_kursus=<?= urlencode($id_kursus) ?>" class="tutor-chat-back">
   ⬅ Kembali ke Daftar Chat
</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const chatForm = document.getElementById("chat-form");
    const pesanInput = document.getElementById("pesan");
    const idKursus = <?= json_encode($id_kursus); ?>;
    const idMember = <?= json_encode($id_member); ?>;
    const idPengirim = <?= json_encode($id_tutor); ?>;

    function escapeHTML(str) {
        return str.replace(/[&<>"']/g, match => ({
            "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;"
        }[match]));
    }

    async function loadChat() {
        try {
            const res = await fetch(`chat_ambil.php?id_kursus=${idKursus}&id_member=${idMember}`);
            const data = await res.json();
            if (data.success) {
                chatBox.innerHTML = "";
                data.messages.forEach(row => {
                    const div = document.createElement("div");
                    div.classList.add("tutor-chat-message", row.is_me ? "tutor-chat-right" : "tutor-chat-left");
                    div.innerHTML = `
                        <div class="tutor-chat-bubble">
                            <div class="tutor-chat-sender">${escapeHTML(row.nama)}</div>
                            <div class="tutor-chat-text">${escapeHTML(row.pesan)}</div>
                            <div class="tutor-chat-time">${row.waktu}</div>
                        </div>`;
                    chatBox.appendChild(div);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            } else {
                chatBox.innerHTML = `<p style="color:red;">Gagal mengambil chat: ${escapeHTML(data.error || 'Unknown')}</p>`;
            }
        } catch (err) {
            console.error("Load chat error:", err);
            chatBox.innerHTML = `<p style="color:red;">Gagal ambil chat (network/server).</p>`;
        }
    }

    chatForm.addEventListener("submit", async function(e){
        e.preventDefault();
        const formData = new FormData(chatForm);
       
        try {
            const res = await fetch("chat_kirim.php", { method: "POST", body: formData });
            const result = await res.json();
            if (result.success) {
                pesanInput.value = "";
                loadChat();
            } else {
                alert("Gagal mengirim pesan: " + (result.error || "Terjadi error"));
            }
        } catch (err) {
            console.error("Send chat error:", err);
            alert("Gagal mengirim pesan karena kesalahan jaringan.");
        }
    });

    loadChat();
    setInterval(loadChat, 4000);
});
</script>

<?php include("inc_footer.php"); ?>

<?php
include("inc/inc_koneksi.php");
include("inc_header.php");

if (!isset($_GET['id'])) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

$id_kursus = intval($_GET['id']);

// Inisialisasi session tonton_materi per-kursus
if (!isset($_SESSION['tonton_materi']) || !is_array($_SESSION['tonton_materi'])) {
    $_SESSION['tonton_materi'] = [];
}
if (!isset($_SESSION['tonton_materi'][$id_kursus]) || !is_array($_SESSION['tonton_materi'][$id_kursus])) {
    $_SESSION['tonton_materi'][$id_kursus] = [];
}

$sql_kursus = "SELECT * FROM content WHERE id = $id_kursus";
$q_kursus = mysqli_query($koneksi, $sql_kursus);
$data_kursus = mysqli_fetch_assoc($q_kursus);

if (!$data_kursus) {
    echo "<h3>Kursus tidak ditemukan</h3>";
    include("inc_footer.php");
    exit;
}

$id_members = $_SESSION['id_members'] ?? null;
$is_langganan = false;

if ($id_members) {
    $sql_cek = "SELECT * FROM langganan WHERE id_members = $id_members AND id_kursus = $id_kursus";
    $q_cek = mysqli_query($koneksi, $sql_cek);
    if (mysqli_num_rows($q_cek) > 0) {
        $is_langganan = true;
    }
}
?>

<h3><?php echo htmlspecialchars($data_kursus['judul']); ?></h3>

<div style="display: flex; justify-content: space-between; align-items: center;">
    <h4>Materi Video</h4>
    <?php if ($is_langganan): ?>
    <div style="position: relative;">
        <button id="openChatBtn" title="Buka Chat" style="background: none; border: none; font-size: 22px; cursor: pointer;">
            💬
            <span id="unreadBadge" style="
                display: none;
                position: absolute;
                top: -5px;
                right: -5px;
                background: red;
                color: white;
                border-radius: 50%;
                padding: 2px 6px;
                font-size: 12px;
            ">0</span>
        </button>
    </div>
    <?php endif; ?>
</div>

<div class="video-grid">
<?php
$sql_materi = "SELECT * FROM materi WHERE id_kursus = $id_kursus ORDER BY tgl_upload ASC";
$q_materi = mysqli_query($koneksi, $sql_materi);
if (mysqli_num_rows($q_materi) > 0) {
    while ($materi = mysqli_fetch_assoc($q_materi)) {
        $id_materi = $materi['id'];
        $video_url = $materi['video'];
        $sudah_nonton = in_array($id_materi, $_SESSION['tonton_materi'][$id_kursus]);

        $is_locked = (!$is_langganan && count($_SESSION['tonton_materi'][$id_kursus]) >= 3 && !$sudah_nonton);

        if (!$sudah_nonton && !$is_locked) {
            $_SESSION['tonton_materi'][$id_kursus][] = $id_materi;
        }

        // Cek YouTube atau bukan
        $embed_code = '';
        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
            if (strpos($video_url, 'youtu.be') !== false) {
                $video_id = str_replace("https://youtu.be/", "", $video_url);
            } else {
                parse_str(parse_url($video_url, PHP_URL_QUERY), $yt_params);
                $video_id = $yt_params['v'] ?? '';
            }
            if ($video_id) {
                $embed_code = '<iframe width="100%" height="200" src="https://www.youtube.com/embed/' . htmlspecialchars($video_id) . '" frameborder="0" allowfullscreen></iframe>';
            }
        } else {
            $embed_code = '<a href="' . htmlspecialchars($video_url) . '" target="_blank">Lihat Video</a>';
        }

        echo '<div class="video-card' . ($is_locked ? ' locked' : '') . '">';
        echo '<div class="video-thumbnail">' . $embed_code . '</div>';

        if ($is_locked) {
            echo '<div class="video-overlay">
                    <p>Materi Terkunci</p>
                    <a href="langganan.php?id=' . $id_kursus . '" class="btn-berlangganan">Berlangganan</a>
                  </div>';
        }

        echo '<div class="video-content">
                <h5>' . htmlspecialchars($materi['judul']) . '</h5>
                <p>' . substr(strip_tags($materi['isi']), 0, 70) . '...</p>
                <div class="meta">
                    <span class="category">Kursus</span> |
                    <span class="date">' . date("d M Y", strtotime($materi['tgl_upload'])) . '</span>
                </div>
              </div>';
        echo '</div>';
    }
} else {
    echo "<p>Tidak ada materi untuk kursus ini.</p>";
}
?>
</div>

<?php if ($is_langganan): ?>
<!-- Popup Chat Box -->
<div id="popupChat" class="chat-popup" style="display:none;">
    <div class="chat-header">
        <span>💬 Room Chat</span>
        <button id="closeChatBtn" style="float:right; background: none; border: none; font-size: 20px;">&times;</button>
    </div>
    <div id="chat-box" class="chat-box"></div>

    <form id="chat-form">
        <input type="hidden" name="id_kursus" value="<?php echo $id_kursus; ?>">
        <textarea name="pesan" id="pesan" placeholder="Tulis pertanyaan..." required></textarea>
        <button type="submit">Kirim</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const openChatBtn  = document.getElementById("openChatBtn");
    const closeChatBtn = document.getElementById("closeChatBtn");
    const popupChat    = document.getElementById("popupChat");
    const chatBox      = document.getElementById("chat-box");
    const chatForm     = document.getElementById("chat-form");
    const pesanInput   = document.getElementById("pesan");
    const unreadBadge  = document.getElementById("unreadBadge");
    const idKursus     = <?php echo json_encode($id_kursus); ?>;

    /* ---------- State ---------- */
    let isChatOpen = false;

    /* ---------- Helper ---------- */
    const nowISO = () =>
        new Date().toISOString().slice(0, 19).replace("T", " ");

    /* ---------- Render pesan ---------- */
    function renderMessages(messages) {
        chatBox.innerHTML = "";
        messages.forEach(row => {
            const bubble = document.createElement("div");
            bubble.classList.add("chat-message");
            bubble.innerHTML =
                `<strong>${row.nama}</strong>: ${row.pesan}<br><small>${row.waktu}</small>`;
            chatBox.appendChild(bubble);
        });
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    /* ---------- Ambil semua pesan ---------- */
    function loadChat() {
        fetch(`chat_ambil.php?id_kursus=${idKursus}`)
            .then(res => res.json())
            .then(data => renderMessages(data.messages))
            .catch(err => console.error("Gagal memuat chat:", err));
    }

    /* ---------- Tandai pesan sudah dibaca & buka popup ---------- */
    function openChat() {
        popupChat.style.display = "block";
        isChatOpen = true;

        fetch(`chat_ambil.php?id_kursus=${idKursus}`) 
            .then(res => res.json())
            .then(data => {
                renderMessages(data.messages);
                unreadBadge.style.display = "none";  
                localStorage.setItem(`lastReadTime_${idKursus}`, nowISO());
            })
            .catch(err => console.error("Gagal membuka chat:", err));
    }

    /* ---------- Tutup popup ---------- */
    function closeChat() {
        popupChat.style.display = "none";
        isChatOpen = false;
    }

    /* ---------- Periksa pesan baru ---------- */
    function checkUnreadMessages() {
        if (isChatOpen) return;  

        const lastRead = localStorage.getItem(`lastReadTime_${idKursus}`) || "";
        fetch(`chat_unread.php?id_kursus=${idKursus}&last_read=${encodeURIComponent(lastRead)}`)
            .then(res => res.json())
            .then(({ jumlah_baru }) => {
                if (jumlah_baru > 0) {
                    unreadBadge.textContent = jumlah_baru;
                    unreadBadge.style.display = "inline-block";
                } else {
                    unreadBadge.style.display = "none";
                }
            })
            .catch(err => console.error("Notif error:", err));
    }


    function sendMessage(e) {
        e.preventDefault();
        const formData = new FormData(chatForm);

        fetch("chat_kirim.php", { method: "POST", body: formData })
            .then(res => res.text())
            .then(result => {
                if (result === "ok") {
                    pesanInput.value = "";
                    loadChat();                           
                } else {
                    alert("Gagal mengirim pesan: " + result);
                }
            });
    }

    /* ---------- Event listener ---------- */
    openChatBtn?.addEventListener("click", openChat);
    closeChatBtn?.addEventListener("click", closeChat);
    chatForm.addEventListener("submit", sendMessage);

    /* ---------- Inisialisasi ---------- */
    checkUnreadMessages();
    setInterval(loadChat, 5000);
    setInterval(checkUnreadMessages, 5000);
});
</script>


<?php endif; ?>

<?php include("inc_footer.php"); ?>

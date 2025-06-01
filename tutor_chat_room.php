<?php
include("inc_header.php");

if (!isset($_SESSION['id_tutor'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_kursus'])) {
    $id_kursus = $_GET['id_kursus'];

    $sql_update = "UPDATE chat_room 
                   SET dibaca = '1' 
                   WHERE id_kursus = ? 
                     AND peran_pengirim = 'members'";
    $stmt = mysqli_prepare($koneksi, $sql_update);
    mysqli_stmt_bind_param($stmt, "i", $id_kursus);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


$id_tutor = $_SESSION['id_tutor'];
$id_kursus = isset($_GET['id_kursus']) ? intval($_GET['id_kursus']) : 0;
?>

<h3>🗨️ Ruang Obrolan Kursus</h3>
<div id="popupChat" style="border:1px solid #ccc; padding:10px; width:100%; max-width:600px;">
    <div id="chat-box" style="height:300px; overflow-y:auto; border:1px solid #ddd; padding:10px; background:#f9f9f9; margin-bottom:10px;"></div>

    <form id="chat-form">
        <input type="hidden" name="id_kursus" value="<?php echo $id_kursus; ?>">
        <textarea name="pesan" id="pesan" placeholder="Tulis pesan..." required style="width:100%; height:80px;"></textarea>
        <button type="submit">Kirim</button>
    </form>
</div>

<a href="tutor_dashboard.php" style="display:inline-block; margin-top:10px;">⬅ Kembali ke Dashboard</a>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const chatForm = document.getElementById("chat-form");
    const pesanInput = document.getElementById("pesan");
    const idKursus = <?php echo json_encode($id_kursus); ?>;

    function loadChat() {
        fetch("chat_ambil.php?id_kursus=" + idKursus)
            .then(response => response.json())
            .then(data => {
                chatBox.innerHTML = "";
                data.messages.forEach(row => {
                const div = document.createElement("div");
                div.classList.add("chat-message");
                div.innerHTML = `<strong>${row.nama}</strong>: ${row.pesan} <br><small>${row.waktu}</small>`;
                chatBox.appendChild(div);
            });
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    chatForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(chatForm);

        fetch("chat_kirim.php", {
            method: "POST",
            body: formData
        }).then(res => res.text())
        .then(result => {
            if (result === "ok") {
                pesanInput.value = "";
                loadChat();
            } else {
                alert("Gagal mengirim pesan: " + result);
            }
        });
    });

    loadChat();
    setInterval(loadChat, 5000);
});
</script>

<?php include("inc_footer.php"); ?>

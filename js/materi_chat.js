document.addEventListener("DOMContentLoaded", () => {
  const badge = document.getElementById("unreadBadge");
  const chatBox = document.getElementById("chat-box");

  // Ambil id_kursus & id_member dari hidden input di chat form
  const chatForm = document.getElementById("chat-form");
  const idKursus = chatForm.querySelector("input[name='id_kursus']").value;
  const idMember = chatForm.querySelector("input[name='id_member']").value;

  async function loadUnread() {
    if (!badge) return;
    try {
      const res = await fetch(`chat_unread.php?id_kursus=${encodeURIComponent(idKursus)}`);
      const data = await res.json();

      if (data.unread_count > 0) {
        badge.style.display = "inline-block";
        badge.textContent = data.unread_count;
      } else {
        badge.style.display = "none";
      }
    } catch (err) {
      console.error("Gagal ambil unread count:", err);
      badge.style.display = "none";
    }
  }

  async function loadChat() {
    try {
      const res = await fetch(`chat_ambil.php?id_kursus=${encodeURIComponent(idKursus)}&id_member=${encodeURIComponent(idMember)}`);
      const data = await res.json();

      if (!data.success) {
        chatBox.innerHTML = `<p style="text-align:center;color:red;">${data.error}</p>`;
        return;
      }

      if (data.messages.length === 0) {
        chatBox.innerHTML = `<p style="text-align:center;color:#888;">Belum ada pesan</p>`;
        return;
      }

      chatBox.innerHTML = data.messages.map(msg => `
        <div class="chat-message ${msg.is_me ? "chat-right" : "chat-left"}">
          <div class="chat-bubble">
            <strong>${msg.nama}</strong><br>
            ${msg.pesan}
            <div style="font-size:11px;color:#777;text-align:right;">${msg.waktu}</div>
          </div>
        </div>
      `).join("");

      // scroll otomatis
      chatBox.scrollTop = chatBox.scrollHeight;

    } catch (err) {
      chatBox.innerHTML = `<p style="text-align:center;color:red;">Gagal memuat chat</p>`;
      console.error("Gagal load chat:", err);
    }
  }

  // load pertama kali
  loadUnread();
  loadChat();

  // refresh setiap 5 detik
  setInterval(loadUnread, 5000);
  setInterval(loadChat, 3000);

  // submit form kirim chat
  chatForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(chatForm);
    try {
      const res = await fetch("chat_kirim.php", {
        method: "POST",
        body: formData
      });
      const result = await res.json();
      if (result.success) {
        chatForm.reset();
        loadChat(); // reload setelah kirim pesan
      } else {
        alert("Gagal kirim pesan: " + result.error);
      }
    } catch (err) {
      alert("Terjadi kesalahan saat mengirim pesan");
      console.error(err);
    }
  });
});

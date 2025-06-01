<?php
include("inc/inc_koneksi.php");
session_start();
header('Content-Type: application/json');

// Validasi input
if (!isset($_GET['id_kursus'])) {
    echo json_encode(["error" => "id_kursus tidak dikirim"]);
    exit;
}

$id_kursus = intval($_GET['id_kursus']);
$id_members = $_SESSION['id_members'] ?? 0;

$data = [];
$new_count = 0;
$last_seen = null;

// 1. Ambil waktu terakhir buka chat
if ($id_members) {
    $sql_seen = "SELECT last_open_chat FROM langganan WHERE id_members = $id_members AND id_kursus = $id_kursus";
    $res_seen = mysqli_query($koneksi, $sql_seen);
    if ($row_seen = mysqli_fetch_assoc($res_seen)) {
        $last_seen = $row_seen['last_open_chat'];
    }
}

// 2. Ambil semua isi chat (tidak difilter oleh waktu)
$sql = "SELECT c.*, 
        COALESCE(m.nama_lengkap, t.nama, 'Anonim') AS nama_pengirim
        FROM chat_room c
        LEFT JOIN members m ON c.id_pengirim = m.id AND c.peran_pengirim = 'members'
        LEFT JOIN tutors t ON c.id_pengirim = t.id AND c.peran_pengirim = 'tutors'
        WHERE c.id_kursus = $id_kursus
        ORDER BY c.waktu ASC LIMIT 100";

$q = mysqli_query($koneksi, $sql);
if (!$q) {
    echo json_encode(["error" => mysqli_error($koneksi)]);
    exit;
}

while ($row = mysqli_fetch_assoc($q)) {
    $data[] = [
        'nama' => $row['nama_pengirim'],
        'pesan' => $row['pesan'],
        'waktu' => date('H:i d/m/Y', strtotime($row['waktu']))
    ];
}

// 3. Hitung pesan baru berdasarkan waktu terakhir buka chat
if ($last_seen) {
    $sql_new = "SELECT COUNT(*) as total FROM chat_room 
                WHERE id_kursus = $id_kursus 
                AND waktu > '$last_seen'";
    $res_new = mysqli_query($koneksi, $sql_new);
    if ($row_new = mysqli_fetch_assoc($res_new)) {
        $new_count = $row_new['total'] ?? 0;
    }
}

// 4. Setelah semua data diambil, baru update waktu buka chat
if ($id_members) {
    $now = date('Y-m-d H:i:s');
    $sql_update = "UPDATE langganan SET last_open_chat = '$now' 
                   WHERE id_members = $id_members AND id_kursus = $id_kursus";
    mysqli_query($koneksi, $sql_update);
}

// 5. Kirim data
echo json_encode([
    "messages" => $data,
    "new_count" => $new_count
]);

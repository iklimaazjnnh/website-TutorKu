<?php
session_start();
include("inc/inc_koneksi.php");
header('Content-Type: application/json');

$id_members = $_SESSION['id_members'] ?? null;
$id_kursus = $_GET['id_kursus'] ?? null;
$last_read = $_GET['last_read'] ?? null;

if (!$id_members || !$id_kursus) {
    echo json_encode(["jumlah_baru" => 0]);
    exit;
}

// Pakai last_read dari localStorage jika ada, fallback ke DB
if ($last_read && strtotime($last_read)) {
    $last_open_chat = mysqli_real_escape_string($koneksi, $last_read);
} else {
    $sql = "SELECT last_open_chat FROM langganan 
            WHERE id_members = $id_members AND id_kursus = $id_kursus";
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);
    $last_open_chat = $row ? $row['last_open_chat'] : '2000-01-01 00:00:00';
}

$sql_pesan = "SELECT COUNT(*) as total 
              FROM chat_room 
              WHERE id_kursus = $id_kursus 
              AND peran_pengirim = 'tutors' 
              AND waktu > '$last_open_chat'";

$res_pesan = mysqli_query($koneksi, $sql_pesan);
$jumlah_baru = mysqli_fetch_assoc($res_pesan)['total'] ?? 0;

echo json_encode(["jumlah_baru" => $jumlah_baru]);

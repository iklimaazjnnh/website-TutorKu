<?php
session_start();
include("inc/inc_koneksi.php");
header('Content-Type: application/json');
$koneksi->set_charset("utf8");

$id_members = $_SESSION['id_members'] ?? null;
$id_kursus  = isset($_GET['id_kursus']) ? intval($_GET['id_kursus']) : 0;

if (!$id_members || !$id_kursus) {
    echo json_encode(['unread_count' => 0]);
    exit;
}

// Ambil last_open_chat dari tabel langganan
$sql = "SELECT last_open_chat FROM langganan WHERE id_members = ? AND id_kursus = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id_members, $id_kursus);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
$last_open_chat = $row['last_open_chat'] ?? '2000-01-01 00:00:00';
mysqli_stmt_close($stmt);

// Hitung pesan tutor setelah last_open_chat khusus untuk member ini
$sql2 = "SELECT COUNT(*) AS total 
         FROM chat_room 
         WHERE id_kursus = ? 
         AND id_penerima = ? 
         AND peran_pengirim = 'tutors'
         AND waktu > ?";
$stmt2 = mysqli_prepare($koneksi, $sql2);
mysqli_stmt_bind_param($stmt2, "iis", $id_kursus, $id_members, $last_open_chat);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
$row2 = mysqli_fetch_assoc($res2);
$unread = intval($row2['total'] ?? 0);
mysqli_stmt_close($stmt2);

echo json_encode(['unread_count' => $unread]);

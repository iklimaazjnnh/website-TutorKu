<?php
include("inc/inc_koneksi.php");
session_start();

$role = $_SESSION['role'] ?? null;
$peran = null;

if ($role === 'member') {
    $id_pengirim = $_SESSION['id_members'] ?? null;
    $peran = 'members';
} elseif ($role === 'tutor') {
    $id_pengirim = $_SESSION['id_tutor'] ?? null;
    $peran = 'tutors';
} else {
    $id_pengirim = null;
}

$id_kursus = intval($_POST['id_kursus']);
$pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

// Validasi login
if (!$id_pengirim || !$peran) {
    http_response_code(401);
    echo json_encode(['status' => 'unauthorized']);
    exit;
}


// Simpan chat
$sql = "INSERT INTO chat_room (id_kursus, id_pengirim, peran_pengirim, pesan) 
        VALUES ($id_kursus, $id_pengirim, '$peran', '$pesan')";

echo (mysqli_query($koneksi, $sql)) ? "ok" : "error";


?>

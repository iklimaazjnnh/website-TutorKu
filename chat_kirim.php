<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include("inc/inc_koneksi.php");
header('Content-Type: application/json');

// Ambil session
$id_member_sess = $_SESSION['id_members'] ?? null;
$id_tutor_sess  = $_SESSION['id_tutor'] ?? null;

// Ambil input POST
$id_kursus    = intval($_POST['id_kursus'] ?? 0);
$pesan        = trim($_POST['pesan'] ?? '');
$id_member_post = intval($_POST['id_member'] ?? 0); // dipakai tutor untuk target member

if (!$id_kursus || $pesan === '') {
    echo json_encode(["success" => false, "error" => "Data tidak lengkap"]);
    exit;
}

// Tentukan pengirim & penerima
if ($id_member_sess) {
    // Member kirim
    $id_pengirim     = $id_member_sess;
    $peran_pengirim  = "members";
    $id_penerima     = 0;
    $peran_penerima  = "tutors";

    // Cari tutor pemilik kursus
    $resTutor = mysqli_query($koneksi, "SELECT id_tutor FROM content WHERE id = $id_kursus LIMIT 1");
    $rowTutor = mysqli_fetch_assoc($resTutor);
    $id_penerima = $rowTutor ? intval($rowTutor['id_tutor']) : 0;

} elseif ($id_tutor_sess) {
    // Tutor kirim
    $id_pengirim     = $id_tutor_sess;
    $peran_pengirim  = "tutors";
    $id_penerima     = $id_member_post;
    $peran_penerima  = "members";
} else {
    echo json_encode(["success" => false, "error" => "Anda belum login"]);
    exit;
}

// Simpan chat
$sql = "INSERT INTO chat_room 
        (id_kursus, id_pengirim, peran_pengirim, id_penerima, peran_penerima, pesan, waktu)
        VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = mysqli_prepare($koneksi, $sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Query gagal disiapkan: " . mysqli_error($koneksi)]);
    exit;
}

// Bind param dan eksekusi
mysqli_stmt_bind_param($stmt, "iissss", $id_kursus, $id_pengirim, $peran_pengirim, $id_penerima, $peran_penerima, $pesan);
$sukses = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($sukses) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($koneksi)]);
}
?>

<?php
header('Content-Type: application/json');
include("inc/inc_koneksi.php");
session_start();

// Sessions
$id_tutor_sess  = isset($_SESSION['id_tutor']) ? intval($_SESSION['id_tutor']) : 0;
$id_member_sess = isset($_SESSION['id_members']) ? intval($_SESSION['id_members']) : 0;

// GET
$id_kursus   = isset($_GET['id_kursus']) ? intval($_GET['id_kursus']) : 0;
$id_member_g = isset($_GET['id_member']) ? intval($_GET['id_member']) : 0; // digunakan oleh tutor

if ($id_kursus <= 0) {
    echo json_encode(["success"=>false,"messages"=>[],"error"=>"ID kursus tidak valid"]);
    exit;
}

// Determine mode: tutor view (tutor session + id_member provided) OR member view (member session)
if ($id_tutor_sess && $id_member_g > 0) {
    // TUTOR view: logged-in tutor wants chat with a specific member
    $mode = "tutor";
    $logged_id = $id_tutor_sess;
    $member_id = $id_member_g;
    $tutor_id  = $id_tutor_sess;
} elseif ($id_member_sess) {
    // MEMBER view: logged-in member sees chat with the tutor of the course
    $mode = "member";
    $logged_id = $id_member_sess;
    // find tutor for this course
    $q = mysqli_prepare($koneksi, "SELECT id_tutor FROM content WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($q, "i", $id_kursus);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($q);

    if (!$row || intval($row['id_tutor']) <= 0) {
        echo json_encode(["success"=>false,"messages"=>[],"error"=>"Tutor kursus tidak ditemukan"]);
        exit;
    }
    $member_id = $id_member_sess;
    $tutor_id  = intval($row['id_tutor']);
} else {
    echo json_encode(["success"=>false,"messages"=>[],"error"=>"Tidak ada session login yang valid atau member tidak dipilih"]);
    exit;
}

// Pada tahap ini variabel sudah terisi:
// - $member_id = ID member
// - $tutor_id  = ID tutor
// - $logged_id = ID pengguna yang sedang melihat chat
//
// Query pesan antara tutor dan member untuk kursus ini
$sql = "SELECT c.*,
        CASE 
          WHEN c.peran_pengirim='tutors' THEN t.nama
          WHEN c.peran_pengirim='members' THEN m.nama_lengkap
          ELSE 'Unknown'
        END AS nama,
        CASE
          WHEN c.peran_pengirim='tutors' AND c.id_pengirim = ? THEN 1
          WHEN c.peran_pengirim='members' AND c.id_pengirim = ? THEN 1
          ELSE 0
        END AS is_me
    FROM chat_room c
    LEFT JOIN tutors t ON t.id = c.id_pengirim AND c.peran_pengirim='tutors'
    LEFT JOIN members m ON m.id = c.id_pengirim AND c.peran_pengirim='members'
    WHERE c.id_kursus = ?
      AND (
           (c.peran_pengirim='members' AND c.id_pengirim = ? AND c.id_penerima = ?)
           OR
           (c.peran_pengirim='tutors'  AND c.id_pengirim = ? AND c.id_penerima = ?)
      )
    ORDER BY c.waktu ASC";

// Prepare
$stmt = mysqli_prepare($koneksi, $sql);
if (!$stmt) {
    echo json_encode(["success"=>false,"messages"=>[],"error"=>"Prepare statement gagal: ".mysqli_error($koneksi)]);
    exit;
}

// Bind in order:
// 1) logged_id for tutors is_me check
// 2) logged_id for members is_me check
// 3) id_kursus
// 4) member_id (members sent) -> id_pengirim
// 5) tutor_id   (members -> id_penerima)
// 6) tutor_id   (tutors sent) -> id_pengirim
// 7) member_id  (tutors -> id_penerima)
mysqli_stmt_bind_param($stmt, "iiiiiii", 
    $logged_id,   // is_me: tutors
    $logged_id,   // is_me: members
    $id_kursus,
    $member_id,
    $tutor_id,
    $tutor_id,
    $member_id
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = [
        "nama"  => $row['nama'],
        "pesan" => $row['pesan'],
        "waktu" => date("H:i", strtotime($row['waktu'])),
        "is_me" => (bool)$row['is_me']
    ];
}

mysqli_stmt_close($stmt);
echo json_encode(["success"=>true,"messages"=>$messages]);

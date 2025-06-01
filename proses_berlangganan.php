<?php
include("inc/inc_koneksi.php");
session_start();

if (!isset($_SESSION['id_members'])) {
    header("location:login.php");
    exit;
}

$id_members = $_SESSION['id_members'];
$id_kursus = $_GET['id'] ?? null;

if (!$id_kursus) {
    echo "ID kursus tidak ditemukan.";
    exit;
}

// Cek apakah sudah berlangganan
$sql_cek = "SELECT * FROM langganan WHERE id_members = $id_members AND id_kursus = $id_kursus";
$q_cek = mysqli_query($koneksi, $sql_cek);

if (mysqli_num_rows($q_cek) == 0) {
    // Tambahkan langganan
    $sql = "INSERT INTO langganan (id_members, id_kursus) VALUES ($id_members, $id_kursus)";
    mysqli_query($koneksi, $sql);
}

// Redirect kembali ke halaman materi kursus
header("Location: materi.php?id=$id_kursus");
exit;
?>


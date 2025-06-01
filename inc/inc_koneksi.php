<?php 
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "tutorku";

// menyimpan fungsi koneksi antara php dan mysql
$koneksi    = mysqli_connect($host,$user,$pass,$db);
if(!$koneksi){
    die("Gagal terkoneksi");
}
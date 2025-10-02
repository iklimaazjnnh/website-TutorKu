<?php 
// Mulai session hanya jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include file lain
include_once("inc/inc_koneksi.php");
include_once("inc/inc_fungsi.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorku</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@300&family=Nunito:ital,wght@0,200;0,300;0,400;0,600;1,200;1,300&family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&family=Roboto&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <link rel="stylesheet" href="<?php echo url_dasar()?>/css/style.css">
    <script src="js/script.js" defer></script>
</head>
<body>
    <nav>
        <div class="wrapper">
            <div class="logo"><a href='<?php echo url_dasar()?>'>TutorKu.</a></div>
            <div class="menu">
                <ul>
                    <li><a href="<?php echo url_dasar()?>#home">Home</a></li>
                    <li><a href="<?php echo url_dasar()?>#courses">About</a></li>
                    <li><a href="<?php echo url_dasar()?>#tutors">Tutors</a></li>
                    <li><a href="<?php echo url_dasar()?>#partners">Partners</a></li>
                    <li><a href="<?php echo url_dasar()?>#contact">Contact</a></li>
                    <?php 
                    if (isset($_SESSION['members_nama_lengkap'])) { ?>
                        <li><a href="<?php echo url_dasar()?>/content.php">My Course</a></li>
                        <li><a href="<?php echo url_dasar()?>/ganti_profile.php"><?php echo $_SESSION['members_nama_lengkap']; ?></a></li>
                        <li><a href="<?php echo url_dasar()?>/logout.php">Logout</a></li>
                    <?php 
                    } elseif (isset($_SESSION['tutor_nama'])) { ?>
                        <li><a href="<?php echo url_dasar()?>/tutor_dashboard.php">Dashboard Tutor</a></li>
                        <li><a href="<?php echo url_dasar()?>/logout.php">Logout</a></li>
                    <?php 
                    } else { ?>
                        <li><a href="pendaftaran.php" class="tbl-biru">Sign Up</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">

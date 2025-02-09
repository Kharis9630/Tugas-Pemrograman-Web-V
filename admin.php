<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Admin Panel</title>
    <!-- Tambahkan link ke CSS admin -->
    <link rel="stylesheet" href="style/admin.css">
</head>
<body>
    <div class="container">
        <h2>Admin Panel - Kelola Data</h2>

        <!-- Menu Navigasi -->
        <nav>
            <ul>
                <li><a href="data_donasi_masuk.php">Dana Masuk</a></li>
                <li><a href="data_laporan.php">Laporan</a></li>
                <li><a href="data_lembaga_sosial.php">Data Lembaga Sosial</a></li>
                <li><a href="data_donatur.php">Data Donatur</a></li>
                <li><a href="donasi_pending.php">Donasi Pending</a></li>
            </ul>
        </nav>

        <br>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>


<?php
session_start();
include 'db.php';

if (!isset($_GET['donasi_id'])) {
    die("Donasi ID tidak ditemukan.");
}

$donasi_id = $_GET['donasi_id'];

$query = mysqli_query($conn, "
    SELECT donasi.*, lembaga_sosial.nama_lembaga
    FROM donasi
    LEFT JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.id = '$donasi_id'
");

$donasi = mysqli_fetch_assoc($query);

if (!$donasi) {
    die("Data donasi tidak ditemukan.");
}

$donatur_id = $_SESSION['user_id'];
$donatur_query = mysqli_query($conn, "SELECT nama FROM users WHERE id = '$donatur_id'");
$donatur_data = mysqli_fetch_assoc($donatur_query);
$nama_donatur = $donatur_data['nama'] ?? 'Anonim';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Nota Pembayaran</title>
    <link rel="stylesheet" href="style/nota_pembayaran.css">
</head>
<body>
    <div class="container">
        <h2>Nota Pembayaran</h2>

        <p><strong>Nama Donatur:</strong> <?= $nama_donatur; ?></p>
        <p><strong>Lembaga Sosial:</strong> <?= $donasi['nama_lembaga']; ?></p>
        <p><strong>Bank Tujuan:</strong> <?= $donasi['bank_tujuan']; ?></p>
        <p><strong>No Rekening:</strong> <?= $donasi['no_rekening']; ?></p>
        <p><strong>Nominal:</strong> Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></p>
        <p><strong>Status:</strong> <?= $donasi['status']; ?></p>

        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>



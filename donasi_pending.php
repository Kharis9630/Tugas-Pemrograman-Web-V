<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$donasi_group_result = mysqli_query($conn, "
    SELECT donasi.lembaga_id, lembaga_sosial.nama_lembaga, donasi.kategori, SUM(donasi.nominal) AS total_nominal
    FROM donasi
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.status = 'pending'  -- Pastikan hanya donasi yang belum terverifikasi yang ditampilkan
    GROUP BY donasi.lembaga_id, donasi.kategori
");

$donasi_detail_result = mysqli_query($conn, "
    SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
    FROM donasi
    JOIN users ON donasi.donatur_id = users.id
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.status = 'pending'  -- Pastikan hanya donasi yang belum terverifikasi yang ditampilkan
");

if (isset($_GET['verifikasi_id'])) {
    $donasi_id = $_GET['verifikasi_id'];
    
    $update_query = "UPDATE donasi SET status = 'terima' WHERE id = '$donasi_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: data_donasi_masuk.php"); 
        exit();
    } else {
        $error = "Gagal memperbarui status donasi: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dana Masuk (Pending)</title>
    <link rel="stylesheet" href="style/donasi_pending.css">

</head>
<body>
    <h2>Donasi yang Belum Diverifikasi</h2>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <?php while ($group = mysqli_fetch_assoc($donasi_group_result)) { ?>
        <h3>Donasi untuk Lembaga: <?= $group['nama_lembaga']; ?> (Kategori: <?= ucfirst($group['kategori']); ?>)</h3>
        
        <table border="1">
            <tr>
                <th>Donatur</th>
                <th>Lembaga Sosial</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Verifikasi</th>
            </tr>

            <?php
            $total_nominal = 0;
            $donasi_result = mysqli_query($conn, "
                SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
                FROM donasi
                JOIN users ON donasi.donatur_id = users.id
                JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
                WHERE donasi.lembaga_id = " . $group['lembaga_id'] . " 
                AND donasi.kategori = '" . $group['kategori'] . "' 
                AND donasi.status = 'pending'
            ");

            while ($donasi = mysqli_fetch_assoc($donasi_result)) {
                $total_nominal += $donasi['nominal'];
            ?>
            <tr>
                <td><?= $donasi['donatur']; ?></td>
                <td><?= $donasi['nama_lembaga']; ?></td>
                <td><?= ucfirst($donasi['kategori']); ?></td>
                <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
                <td><?= ucfirst($donasi['status']); ?></td>
                <td><?= $donasi['created_at']; ?></td>
                <td><a href="donasi_pending.php?verifikasi_id=<?= $donasi['id']; ?>">Verifikasi</a></td>
            </tr>
            <?php } ?>

            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total Nominal:</strong></td>
                <td><strong>Rp <?= number_format($total_nominal, 2, ',', '.'); ?></strong></td>
                <td colspan="3"></td>
            </tr>
        </table>

        <br><br>
    <?php } ?>

    <br><br>
    <a href="admin.php">Kembali ke Admin Panel</a>
    <?php include 'footer.php'; ?>
</body>
</html>

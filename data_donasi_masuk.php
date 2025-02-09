<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "AND (users.nama LIKE '%$search%' 
                        OR lembaga_sosial.nama_lembaga LIKE '%$search%' 
                        OR donasi.kategori LIKE '%$search%')";
}

$donasi_group_result = mysqli_query($conn, "
    SELECT donasi.lembaga_id, lembaga_sosial.nama_lembaga, donasi.kategori, SUM(donasi.nominal) AS total_nominal
    FROM donasi
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    JOIN users ON donasi.donatur_id = users.id
    WHERE donasi.status = 'terima' $search_query
    GROUP BY donasi.lembaga_id, donasi.kategori
");

if (!$donasi_group_result) {
    die('Error pada query group: ' . mysqli_error($conn));
}

$donasi_all_result = mysqli_query($conn, "
    SELECT donasi.*, users.nama AS donatur, lembaga_sosial.nama_lembaga 
    FROM donasi
    JOIN users ON donasi.donatur_id = users.id
    JOIN lembaga_sosial ON donasi.lembaga_id = lembaga_sosial.id
    WHERE donasi.status = 'terima' $search_query
");

if (!$donasi_all_result) {
    die('Error pada query all: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dana Masuk</title>
    <link rel="stylesheet" href="style/data_donasi_masuk.css">
</head>
<body>
    <h2>Dana Masuk</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Cari donatur, lembaga, atau kategori" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if (mysqli_num_rows($donasi_group_result) > 0) { ?>
        <?php while ($group = mysqli_fetch_assoc($donasi_group_result)) { ?>
            <h3>Donasi untuk Lembaga: <?= $group['nama_lembaga']; ?> (Kategori: <?= ucfirst($group['kategori']); ?>)</h3>
            
            <table border="1">
                <tr>
                    <th>Donatur</th>
                    <th>Lembaga Sosial</th>
                    <th>Kategori</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
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
                    AND donasi.status = 'terima' $search_query
                ");

                if (!$donasi_result) {
                    die('Error pada query donasi per lembaga: ' . mysqli_error($conn));
                }

                while ($donasi = mysqli_fetch_assoc($donasi_result)) {
                    $total_nominal += $donasi['nominal'];
                ?>
                <tr>
                    <td><?= $donasi['donatur']; ?></td>
                    <td><?= $donasi['nama_lembaga']; ?></td>
                    <td><?= ucfirst($donasi['kategori']); ?></td>
                    <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
                    <td><?= $donasi['created_at']; ?></td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Nominal:</strong></td>
                    <td><strong>Rp <?= number_format($total_nominal, 2, ',', '.'); ?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </table>

            <br><br>
        <?php } ?>
    <?php } else { ?>
        <p><strong>Tidak ada data yang cocok dengan pencarian.</strong></p>
    <?php } ?>

    <h3>Semua Donasi Masuk</h3>
    <table border="1">
        <tr>
            <th>Donatur</th>
            <th>Lembaga Sosial</th>
            <th>Kategori</th>
            <th>Nominal</th>
            <th>Tanggal</th>
        </tr>

        <?php
        $total_all_nominal = 0;
        if (mysqli_num_rows($donasi_all_result) > 0) {
            while ($donasi = mysqli_fetch_assoc($donasi_all_result)) {
                $total_all_nominal += $donasi['nominal'];
        ?>
        <tr>
            <td><?= $donasi['donatur']; ?></td>
            <td><?= $donasi['nama_lembaga']; ?></td>
            <td><?= ucfirst($donasi['kategori']); ?></td>
            <td>Rp <?= number_format($donasi['nominal'], 2, ',', '.'); ?></td>
            <td><?= $donasi['created_at']; ?></td>
        </tr>
        <?php } ?>

        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total Nominal Semua Donasi:</strong></td>
            <td><strong>Rp <?= number_format($total_all_nominal, 2, ',', '.'); ?></strong></td>
            <td colspan="2"></td>
        </tr>
        <?php } else { ?>
        <tr>
            <td colspan="5" style="text-align: center;"><strong>Tidak ada data yang cocok dengan pencarian.</strong></td>
        </tr>
        <?php } ?>
    </table>

    <br><br>
    <a href="admin.php">Kembali ke Admin Panel</a>
    <?php include 'footer.php'; ?>
</body>
</html>

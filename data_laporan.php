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
    $search_query = "WHERE (lembaga_sosial.email LIKE '%$search%' 
                        OR lembaga_sosial.nama_lembaga LIKE '%$search%' 
                        OR laporan.deskripsi LIKE '%$search%')";
}

$laporan_result = mysqli_query($conn, "
    SELECT laporan.*, lembaga_sosial.email, lembaga_sosial.nama_lembaga 
    FROM laporan
    LEFT JOIN lembaga_sosial ON laporan.lembaga_id = lembaga_sosial.id
    $search_query
    ORDER BY lembaga_sosial.email ASC
");

if (!$laporan_result) {
    die('Error pada query laporan: ' . mysqli_error($conn));
}

$laporan_by_email = [];
while ($laporan = mysqli_fetch_assoc($laporan_result)) {
    $laporan_by_email[$laporan['email']][] = $laporan;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Donasi</title>
    <link rel="stylesheet" href="style/data_laporan.css">
</head>
<body>
    <h2>Laporan Donasi</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Cari email, lembaga, atau deskripsi" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if (!empty($laporan_by_email)) { ?>
        <?php foreach ($laporan_by_email as $email => $laporan_list) { ?>
            <h3>Laporan oleh: <?= $laporan_list[0]['nama_lembaga'] ?> (<?= $email; ?>)</h3>

            <table border="1">
                <tr>
                    <th>ID Laporan</th>
                    <th>Tanggal Laporan</th>
                    <th>Nominal Total</th>
                    <th>Deskripsi</th>
                </tr>

                <?php
                $total_nominal = 0;
                foreach ($laporan_list as $laporan) {
                    $total_nominal += $laporan['nominal'];
                ?>
                <tr>
                    <td><?= $laporan['id']; ?></td>
                    <td><?= $laporan['created_at']; ?></td>
                    <td>Rp <?= number_format($laporan['nominal'], 2, ',', '.'); ?></td>
                    <td><?= $laporan['deskripsi']; ?></td>
                </tr>
                <?php } ?>

                <tr>
                    <td colspan="2" style="text-align: right;"><strong>Total Nominal:</strong></td>
                    <td><strong>Rp <?= number_format($total_nominal, 2, ',', '.'); ?></strong></td>
                    <td></td>
                </tr>
            </table>

            <br><br>
        <?php } ?>
    <?php } else { ?>
        <p><strong>Tidak ada data yang cocok dengan pencarian.</strong></p>
    <?php } ?>

    <a href="admin.php">Kembali ke Admin Panel</a>
    <?php include 'footer.php'; ?>
</body>
</html>

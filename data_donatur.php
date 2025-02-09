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
    $search_query = "AND (nama LIKE '%$search%' OR email LIKE '%$search%')";
}

$donatur_result = mysqli_query($conn, "SELECT * FROM users WHERE role = 'donatur' $search_query ORDER BY nama ASC");
if (!$donatur_result) {
    die('Error pada query: ' . mysqli_error($conn));
}

if (isset($_GET['hapus_id'])) {
    $hapus_id = $_GET['hapus_id'];
    $hapus_query = "DELETE FROM users WHERE id = '$hapus_id' AND role = 'donatur'";

    if (mysqli_query($conn, $hapus_query)) {
        header("Location: data_donatur.php");
        exit();
    } else {
        $error = "Gagal menghapus donatur: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Donatur</title>
    <link rel="stylesheet" href="style/data_donatur.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus donatur ini?")) {
                window.location.href = "data_donatur.php?hapus_id=" + id;
            }
        }
    </script>
</head>
<body>
    <h2>Data Donatur</h2>

    <!-- Tombol untuk Menambah Donatur -->
    <a href="tambah_donatur.php" class="add-btn">Tambah Donatur</a>

    <form method="GET">
        <input type="text" name="search" placeholder="Cari nama atau email" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <button type="submit">Cari</button>
    </form>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    <?php if (isset($success_message)) echo "<p style='color: green;'>$success_message</p>"; ?>

    <table border="1">
        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php if (mysqli_num_rows($donatur_result) > 0) { ?>
            <?php while ($donatur = mysqli_fetch_assoc($donatur_result)) { ?>
            <tr>
                <td><?= $donatur['nama']; ?></td>
                <td><?= $donatur['email']; ?></td>
                <td><?= ucfirst($donatur['role']); ?></td>
                <td>
                    <a href="data_donatur.php?edit_id=<?= $donatur['id']; ?>">Edit</a> | 
                    <a href="#" onclick="confirmDelete(<?= $donatur['id']; ?>)" style="color: red;">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4" style="text-align: center; color: red;">Tidak ada data yang cocok dengan pencarian.</td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="admin.php">Kembali ke Admin Panel</a>
    <?php include 'footer.php'; ?>
</body>
</html>

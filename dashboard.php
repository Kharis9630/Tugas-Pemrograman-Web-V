<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style/dashboard.css">
</head>
<body>
    <h2>Dashboard</h2>
    <p>Selamat datang, <?php echo $role; ?>!</p>

    <?php if ($role == "donatur") { ?>
        <a href="donasi.php">Buat Donasi</a>
    <?php } elseif ($role == "lembaga") { ?>
        <a href="laporan.php">Buat Laporan Dana</a>
    <?php } elseif ($role == "admin") { ?>
        <a href="admin.php">Panel Admin</a>
    <?php } ?>

    <br><br>
    <a href="logout.php">Logout</a>
    <?php include 'footer.php'; ?>
</body>
</html>


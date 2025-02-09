<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: data_donatur.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id' AND role = 'donatur'");
$donatur = mysqli_fetch_assoc($result);

if (!$donatur) {
    echo "Donatur tidak ditemukan!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE users SET nama = '$nama', email = '$email' WHERE id = '$id' AND role = 'donatur'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: data_donatur.php");
        exit();
    } else {
        $error = "Gagal memperbarui donatur: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Donatur</title>
    <link rel="stylesheet" href="style/data_donatur.css">

</head>
<body>
    <h2>Edit Donatur</h2>
    
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $donatur['nama']; ?>" required>
        <br>
        <label>Email:</label>
        <input type="email" name="email" value="<?= $donatur['email']; ?>" required>
        <br>
        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="data_donatur.php">Kembali</a>
</body>
</html>

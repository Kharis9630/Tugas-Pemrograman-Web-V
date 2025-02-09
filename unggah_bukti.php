<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['donasi_id'])) {
    header("Location: dashboard.php");
    exit();
}

$donasi_id = $_GET['donasi_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["bukti"]["name"]);
    
    if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
        $query = "UPDATE donasi SET bukti_transfer='$target_file' WHERE id='$donasi_id'";
        if (mysqli_query($conn, $query)) {
            header("Location: nota_pembayaran.php?donasi_id=$donasi_id");
            exit();
        } else {
            $error = "Gagal mengunggah bukti: " . mysqli_error($conn);
        }
    } else {
        $error = "Gagal mengunggah file.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Upload Bukti Transfer</title>
    <link rel="stylesheet" href="style/unggah_bukti.css">
</head>
<body>
    <div class="container">
        <h2>Upload Bukti Transfer</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Unggah Bukti Transfer:</label>
            <input type="file" name="bukti" required><br>
            <button type="submit">Kirim Bukti</button>
        </form>

        <br>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>


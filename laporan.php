<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'lembaga') {
    header("Location: login.php");
    exit();
}

$lembaga_id = $_SESSION['user_id'];

$query_lembaga = "SELECT nama FROM users WHERE id = '$lembaga_id'";
$result = mysqli_query($conn, $query_lembaga);
$data = mysqli_fetch_assoc($result);
$nama_lembaga = $data['nama'];

$query_lembaga_id = "SELECT id FROM lembaga_sosial WHERE nama_lembaga = '$nama_lembaga'";
$result_lembaga_id = mysqli_query($conn, $query_lembaga_id);
$data_lembaga_id = mysqli_fetch_assoc($result_lembaga_id);
$lembaga_id_sosial = $data_lembaga_id['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deskripsi = $_POST['deskripsi'];
    $nominal = $_POST['nominal'];

    $query = "INSERT INTO laporan (lembaga_id, nama_lembaga, deskripsi, nominal) 
              VALUES ('$lembaga_id_sosial', '$nama_lembaga', '$deskripsi', '$nominal')";

    if (mysqli_query($conn, $query)) {
        $success = "Laporan berhasil dikirim.";
    } else {
        $error = "Gagal mengirim laporan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Penggunaan Dana</title>
    <link rel="stylesheet" href="style/laporan.css">
</head>
<body>
    <div class="container">
        <h2>Laporan Penggunaan Dana</h2>

        <?php if (isset($success)) echo "<p>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        
        <form method="POST">
            <label>Deskripsi Penggunaan Dana:</label>
            <textarea name="deskripsi" required></textarea><br>

            <label>Nominal Dana yang Digunakan (Rp):</label>
            <input type="number" name="nominal" required><br>

            <button type="submit">Kirim Laporan</button>
        </form>

        <br>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['bank'])) {
    header("Location: pilih_bank.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$bank = $_POST['bank'];

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["bukti_transfer"]["name"]);

if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $target_file)) {
    $query = "INSERT INTO bukti_transfer (donatur_id, bank_tujuan, bukti) VALUES ('$user_id', '$bank', '$target_file')";
    mysqli_query($conn, $query);
    header("Location: nota_pembayaran.php?id=" . mysqli_insert_id($conn));
} else {
    echo "Maaf, terjadi kesalahan saat mengunggah file.";
}
?>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'donatur') {
    header("Location: login.php");
    exit();
}

$donatur_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lembaga_id = $_POST['lembaga_id'];
    $kategori = $_POST['kategori'];
    $nominal = $_POST['nominal'];
    $bank_tujuan = $_POST['bank_tujuan'];
    $no_rekening = $_POST['no_rekening'];

    $query = "INSERT INTO donasi (donatur_id, lembaga_id, kategori, nominal, status, bank_tujuan, no_rekening) 
              VALUES (?, ?, ?, ?, 'pending', ?, ?)";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'iissss', $donatur_id, $lembaga_id, $kategori, $nominal, $bank_tujuan, $no_rekening);

        if (mysqli_stmt_execute($stmt)) {
            $donasi_id = mysqli_insert_id($conn);
            header("Location: unggah_bukti.php?donasi_id=$donasi_id");
            exit();
        } else {
            $error = "Gagal melakukan donasi: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = "Gagal mempersiapkan query: " . mysqli_error($conn);
    }
}

$lembaga_result = mysqli_query($conn, "SELECT * FROM lembaga_sosial");

if (!$lembaga_result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Formulir Donasi</title>
    <link rel="stylesheet" href="style/donasi.css">
    <script>
        function updateRekening() {
            var bank = document.getElementById("bank_tujuan").value;
            var rekening = {
                "BCA": "123-456-7890",
                "Mandiri": "987-654-3210",
                "BRI": "555-666-777",
                "BNI": "222-333-444"
            };
            document.getElementById("no_rekening").value = rekening[bank];
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Formulir Donasi</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <label for="lembaga_id">Pilih Lembaga Sosial:</label>
            <select name="lembaga_id" id="lembaga_id" required>
                <?php if (mysqli_num_rows($lembaga_result) > 0) { ?>
                    <?php while ($lembaga = mysqli_fetch_assoc($lembaga_result)) { ?>
                        <option value="<?= $lembaga['id']; ?>"><?= $lembaga['nama_lembaga']; ?></option>
                    <?php } ?>
                <?php } else { ?>
                    <option value="">Belum ada lembaga sosial tersedia</option>
                <?php } ?>
            </select><br>

            <label for="kategori">Kategori Donasi:</label>
            <select name="kategori" id="kategori" required>
                <option value="pendidikan">Pendidikan</option>
                <option value="kesehatan">Kesehatan</option>
                <option value="bencana">Bencana</option>
                <option value="lainnya">Lainnya</option>
            </select><br>

            <label for="nominal">Nominal (Rp):</label>
            <input type="number" name="nominal" id="nominal" required><br>

            <label for="bank_tujuan">Pilih Bank Tujuan:</label>
            <select name="bank_tujuan" id="bank_tujuan" required onchange="updateRekening()">
                <option value="BCA">BCA</option>
                <option value="Mandiri">Mandiri</option>
                <option value="BRI">BRI</option>
                <option value="BNI">BNI</option>
            </select><br>

            <label for="no_rekening">Nomor Rekening:</label>
            <input type="text" id="no_rekening" name="no_rekening" readonly><br>

            <button type="submit">Lanjutkan Pembayaran</button>
        </form>

        <br>
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

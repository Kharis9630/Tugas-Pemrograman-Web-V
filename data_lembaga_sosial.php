<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Ambil data lembaga sosial
$lembaga_sosial_result = mysqli_query($conn, "
    SELECT lembaga_sosial.*, users.email 
    FROM lembaga_sosial
    LEFT JOIN users ON lembaga_sosial.nama_lembaga = users.nama AND users.role = 'lembaga'
");

// Proses edit lembaga sosial
if (isset($_POST['edit'])) {
    $lembaga_id = $_POST['lembaga_id'];
    $nama_lembaga = $_POST['nama_lembaga'];
    $email = $_POST['email'];

    $update_query = "UPDATE lembaga_sosial SET nama_lembaga = '$nama_lembaga' WHERE id = '$lembaga_id'";
    if (mysqli_query($conn, $update_query)) {
        $update_user_query = "UPDATE users SET email = '$email' WHERE nama = '$nama_lembaga' AND role = 'lembaga'";
        mysqli_query($conn, $update_user_query);
        header("Location: data_lembaga_sosial.php");
        exit();
    } else {
        $error = "Gagal memperbarui data lembaga sosial: " . mysqli_error($conn);
    }
}

// Proses hapus lembaga sosial
if (isset($_POST['delete'])) {
    $lembaga_id = $_POST['lembaga_id'];

    $delete_query = "DELETE FROM lembaga_sosial WHERE id = '$lembaga_id'";
    if (mysqli_query($conn, $delete_query)) {
        header("Location: data_lembaga_sosial.php");
        exit();
    } else {
        $error = "Gagal menghapus lembaga sosial: " . mysqli_error($conn);
    }
}

// Proses tambah lembaga sosial
if (isset($_POST['tambah'])) {
    $nama_lembaga = $_POST['nama_lembaga'];
    $email = $_POST['email'];

    // Insert lembaga sosial baru ke database
    $insert_query = "INSERT INTO lembaga_sosial (nama_lembaga) VALUES ('$nama_lembaga')";
    if (mysqli_query($conn, $insert_query)) {
        // Insert user baru dengan role lembaga
        $user_insert_query = "INSERT INTO users (nama, email, role) VALUES ('$nama_lembaga', '$email', 'lembaga')";
        mysqli_query($conn, $user_insert_query);
        header("Location: data_lembaga_sosial.php");
        exit();
    } else {
        $error = "Gagal menambah lembaga sosial: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Lembaga Sosial</title>
    <link rel="stylesheet" href="style/data_lembaga_sosial.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Apakah Anda yakin ingin menghapus lembaga sosial ini?")) {
                document.getElementById("delete-form-" + id).submit();
            }
        }
    </script>
</head>
<body>
    <h2>Data Lembaga Sosial</h2>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <!-- Tombol Tambah Lembaga Sosial -->
    <a href="data_lembaga_sosial.php?show_add_form=true" class="add-btn">Tambah Lembaga Sosial</a>

    <?php
    // Tampilkan form tambah lembaga sosial jika parameter 'show_add_form' ada
    if (isset($_GET['show_add_form'])) { ?>
        <h3>Tambah Lembaga Sosial</h3>
        <form method="post" action="data_lembaga_sosial.php">
            <label for="nama_lembaga">Nama Lembaga:</label>
            <input type="text" id="nama_lembaga" name="nama_lembaga" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <button type="submit" name="tambah">Tambah Lembaga Sosial</button>
        </form>
    <?php } ?>

    <table border="1">
        <tr>
            <th>Nama Lembaga</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
        <?php while ($lembaga_sosial = mysqli_fetch_assoc($lembaga_sosial_result)) { ?>
        <tr>
            <td><?= $lembaga_sosial['nama_lembaga']; ?></td>
            <td><?= $lembaga_sosial['email']; ?></td>
            <td class="action-buttons">
                <a href="data_lembaga_sosial.php?edit_id=<?= $lembaga_sosial['id']; ?>" class="edit-btn">Edit</a>
                
                <form id="delete-form-<?= $lembaga_sosial['id']; ?>" method="post" style="display:inline;">
                    <input type="hidden" name="lembaga_id" value="<?= $lembaga_sosial['id']; ?>">
                    <button type="button" class="delete-btn" onclick="confirmDelete(<?= $lembaga_sosial['id']; ?>)">Hapus</button>
                    <input type="hidden" name="delete">
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $edit_query = mysqli_query($conn, "SELECT * FROM lembaga_sosial WHERE id = '$edit_id'");
        $edit_data = mysqli_fetch_assoc($edit_query);
    ?>
    <h3>Edit Lembaga Sosial</h3>
    <form method="post" action="data_lembaga_sosial.php">
        <input type="hidden" name="lembaga_id" value="<?= $edit_data['id']; ?>">
        <label for="nama_lembaga">Nama Lembaga:</label>
        <input type="text" id="nama_lembaga" name="nama_lembaga" value="<?= $edit_data['nama_lembaga']; ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= $edit_data['email']; ?>" required><br><br>
        
        <button type="submit" name="edit">Simpan Perubahan</button>
    </form>
    <?php } ?>

    <br>
    <a href="admin.php">Kembali ke Admin Panel</a>
    <?php include 'footer.php'; ?>
</body>
</html>

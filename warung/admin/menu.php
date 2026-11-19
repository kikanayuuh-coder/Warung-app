<?php
// =============================================
// ADMIN - KELOLA MENU (CRUD)
// admin/menu.php
// =============================================
session_start();
require_once '../config.php';

// Cek login dan role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$pesan   = "";
$edit_data = null; // Untuk menyimpan data yang akan diedit

// =============================================
// CREATE - Tambah Menu Baru
// =============================================
if (isset($_POST['tambah'])) {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $query = "INSERT INTO menu (nama_menu, kategori, harga, deskripsi) 
              VALUES ('$nama', '$kategori', '$harga', '$deskripsi')";
    
    if (mysqli_query($conn, $query)) {
        $pesan = '<div class="alert alert-success">✅ Menu berhasil ditambahkan!</div>';
    } else {
        $pesan = '<div class="alert alert-danger">❌ Gagal menambahkan menu!</div>';
    }
}

// =============================================
// UPDATE - Simpan Perubahan Menu
// =============================================
if (isset($_POST['update'])) {
    $id        = $_POST['id'];
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $kategori  = $_POST['kategori'];
    $harga     = $_POST['harga'];
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tersedia  = isset($_POST['tersedia']) ? 1 : 0;

    $query = "UPDATE menu SET 
                nama_menu  = '$nama',
                kategori   = '$kategori',
                harga      = '$harga',
                deskripsi  = '$deskripsi',
                tersedia   = '$tersedia'
              WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $pesan = '<div class="alert alert-success">✅ Menu berhasil diperbarui!</div>';
    } else {
        $pesan = '<div class="alert alert-danger">❌ Gagal memperbarui menu!</div>';
    }
}

// =============================================
// DELETE - Hapus Menu
// =============================================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM menu WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $pesan = '<div class="alert alert-success">✅ Menu berhasil dihapus!</div>';
    } else {
        $pesan = '<div class="alert alert-danger">❌ Gagal menghapus! Menu mungkin masih ada di pesanan.</div>';
    }
}

// =============================================
// READ (EDIT) - Ambil Data untuk Diedit
// =============================================
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM menu WHERE id = '$id'");
    $edit_data = mysqli_fetch_assoc($result);
}

// =============================================
// READ - Ambil Semua Data Menu
// =============================================
$semua_menu = mysqli_query($conn, "SELECT * FROM menu ORDER BY kategori, nama_menu");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>🍽️ Warung App - Panel Admin</h1>
    <a href="../logout.php">Logout</a>
</div>

<div class="container">

    <!-- TAB NAVIGASI -->
    <div class="tab-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="menu.php" class="active">🍜 Kelola Menu</a>
        <a href="pesanan.php">📋 Pesanan</a>
    </div>

    <?= $pesan ?>

    <!-- FORM TAMBAH / EDIT -->
    <div class="card">
        <h3><?= $edit_data ? '✏️ Edit Menu' : '➕ Tambah Menu Baru' ?></h3>

        <form method="POST">
            <?php if ($edit_data): ?>
                <!-- Jika edit, kirim ID -->
                <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" 
                       value="<?= $edit_data ? $edit_data['nama_menu'] : '' ?>" 
                       placeholder="Contoh: Nasi Goreng Spesial" required>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="makanan" <?= ($edit_data && $edit_data['kategori']=='makanan') ? 'selected' : '' ?>>🍜 Makanan</option>
                    <option value="minuman" <?= ($edit_data && $edit_data['kategori']=='minuman') ? 'selected' : '' ?>>🥤 Minuman</option>
                </select>
            </div>

            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" 
                       value="<?= $edit_data ? $edit_data['harga'] : '' ?>" 
                       placeholder="Contoh: 15000" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat menu..."><?= $edit_data ? $edit_data['deskripsi'] : '' ?></textarea>
            </div>

            <?php if ($edit_data): ?>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="tersedia" <?= $edit_data['tersedia'] ? 'checked' : '' ?>>
                        Menu Tersedia
                    </label>
                </div>
                <button type="submit" name="update" class="btn btn-warning">💾 Simpan Perubahan</button>
                <a href="menu.php" class="btn btn-danger">✖ Batal</a>
            <?php else: ?>
                <button type="submit" name="tambah" class="btn btn-success">➕ Tambah Menu</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- TABEL DATA MENU -->
    <div class="card">
        <h3>📋 Daftar Menu</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($menu = mysqli_fetch_assoc($semua_menu)): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><b><?= $menu['nama_menu'] ?></b></td>
                    <td><span class="badge badge-<?= $menu['kategori'] ?>"><?= ucfirst($menu['kategori']) ?></span></td>
                    <td>Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                    <td><?= $menu['deskripsi'] ?: '-' ?></td>
                    <td><?= $menu['tersedia'] ? '✅ Ada' : '❌ Habis' ?></td>
                    <td>
                        <!-- Tombol Edit -->
                        <a href="menu.php?edit=<?= $menu['id'] ?>" class="btn btn-warning" style="padding:5px 10px; font-size:12px;">✏️ Edit</a>
                        
                        <!-- Tombol Hapus dengan konfirmasi -->
                        <a href="menu.php?hapus=<?= $menu['id'] ?>" 
                           class="btn btn-danger" 
                           style="padding:5px 10px; font-size:12px;"
                           onclick="return confirm('Yakin mau hapus menu ini?')">🗑️ Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

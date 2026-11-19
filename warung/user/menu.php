<?php
// =============================================
// USER - LIHAT MENU & PESAN
// user/menu.php
// =============================================
session_start();
require_once '../config.php';

// Cek login dan role user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}

$pesan = "";

// =============================================
// PROSES PESAN MENU
// =============================================
if (isset($_POST['pesan'])) {
    $user_id  = $_SESSION['user_id'];
    $menu_id  = $_POST['menu_id'];
    $jumlah   = (int)$_POST['jumlah'];

    // Ambil harga menu
    $result = mysqli_query($conn, "SELECT harga FROM menu WHERE id='$menu_id'");
    $menu   = mysqli_fetch_assoc($result);
    $total  = $menu['harga'] * $jumlah;

    $query = "INSERT INTO pesanan (user_id, menu_id, jumlah, total_harga) 
              VALUES ('$user_id', '$menu_id', '$jumlah', '$total')";

    if (mysqli_query($conn, $query)) {
        $pesan = '<div class="alert alert-success">✅ Pesanan berhasil! Silakan tunggu ya.</div>';
    } else {
        $pesan = '<div class="alert alert-danger">❌ Gagal memesan, coba lagi.</div>';
    }
}

// Ambil semua menu yang tersedia, dikelompokkan per kategori
$makanan = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='makanan' AND tersedia=1 ORDER BY nama_menu");
$minuman = mysqli_query($conn, "SELECT * FROM menu WHERE kategori='minuman' AND tersedia=1 ORDER BY nama_menu");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Menu - Warung App</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>🍽️ Warung App</h1>
    <div>
        <a href="pesanan_saya.php" style="background:#27ae60; margin-right:8px;">📋 Pesanan Saya</a>
        <a href="../logout.php">Logout (<?= $_SESSION['nama'] ?>)</a>
    </div>
</div>

<div class="container">

    <?= $pesan ?>

    <div style="margin-bottom: 20px;">
        <h2>Halo, <?= $_SESSION['nama'] ?>! 👋</h2>
        <p style="color:#888;">Pilih menu yang kamu inginkan di bawah ini.</p>
    </div>

    <!-- ===================== MAKANAN ===================== -->
    <div class="card">
        <h3>🍜 Makanan</h3>
        <div class="menu-grid">
            <?php if (mysqli_num_rows($makanan) == 0): ?>
                <p style="color:#888;">Belum ada menu makanan.</p>
            <?php else: while ($m = mysqli_fetch_assoc($makanan)): ?>

            <div class="menu-card">
                <h4><?= $m['nama_menu'] ?></h4>
                <div class="harga">Rp <?= number_format($m['harga'], 0, ',', '.') ?></div>
                <p class="deskripsi"><?= $m['deskripsi'] ?: '-' ?></p>

                <!-- Form Pesan -->
                <form method="POST">
                    <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                    <div style="display:flex; gap:5px; align-items:center; justify-content:center; margin-bottom:8px;">
                        <label style="font-size:13px;">Jml:</label>
                        <input type="number" name="jumlah" value="1" min="1" max="10" 
                               style="width:55px; padding:5px; text-align:center; border:1px solid #ddd; border-radius:4px;">
                    </div>
                    <button type="submit" name="pesan" class="btn btn-primary" style="width:100%;">🛒 Pesan</button>
                </form>
            </div>

            <?php endwhile; endif; ?>
        </div>
    </div>

    <!-- ===================== MINUMAN ===================== -->
    <div class="card">
        <h3>🥤 Minuman</h3>
        <div class="menu-grid">
            <?php if (mysqli_num_rows($minuman) == 0): ?>
                <p style="color:#888;">Belum ada menu minuman.</p>
            <?php else: while ($m = mysqli_fetch_assoc($minuman)): ?>

            <div class="menu-card">
                <h4><?= $m['nama_menu'] ?></h4>
                <div class="harga">Rp <?= number_format($m['harga'], 0, ',', '.') ?></div>
                <p class="deskripsi"><?= $m['deskripsi'] ?: '-' ?></p>

                <form method="POST">
                    <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                    <div style="display:flex; gap:5px; align-items:center; justify-content:center; margin-bottom:8px;">
                        <label style="font-size:13px;">Jml:</label>
                        <input type="number" name="jumlah" value="1" min="1" max="10" 
                               style="width:55px; padding:5px; text-align:center; border:1px solid #ddd; border-radius:4px;">
                    </div>
                    <button type="submit" name="pesan" class="btn btn-primary" style="width:100%;">🛒 Pesan</button>
                </form>
            </div>

            <?php endwhile; endif; ?>
        </div>
    </div>

</div>
</body>
</html>

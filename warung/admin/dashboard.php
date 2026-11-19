<?php
// =============================================
// ADMIN DASHBOARD - admin/dashboard.php
// =============================================
session_start();
require_once '../config.php';

// Cek apakah sudah login dan role-nya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Ambil data statistik
$total_menu     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM menu"));
$total_pesanan  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM pesanan"));
$total_user     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE role='user'"));

// Ambil pesanan terbaru
$query_pesanan = "SELECT p.*, u.nama as nama_user, m.nama_menu 
                  FROM pesanan p 
                  JOIN users u ON p.user_id = u.id 
                  JOIN menu m ON p.menu_id = m.id 
                  ORDER BY p.created_at DESC LIMIT 10";
$pesanan = mysqli_query($conn, $query_pesanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Warung App</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <h1>🍽️ Warung App - Panel Admin</h1>
    <a href="../logout.php">Logout (<?= $_SESSION['nama'] ?>)</a>
</div>

<div class="container">

    <!-- TAB NAVIGASI -->
    <div class="tab-nav">
        <a href="dashboard.php" class="active">📊 Dashboard</a>
        <a href="menu.php">🍜 Kelola Menu</a>
        <a href="pesanan.php">📋 Pesanan</a>
    </div>

    <!-- STATISTIK -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="number"><?= $total_menu ?></div>
            <div class="label">Total Menu</div>
        </div>
        <div class="stat-box">
            <div class="number"><?= $total_pesanan ?></div>
            <div class="label">Total Pesanan</div>
        </div>
        <div class="stat-box">
            <div class="number"><?= $total_user ?></div>
            <div class="label">Total Pelanggan</div>
        </div>
    </div>

    <!-- PESANAN TERBARU -->
    <div class="card">
        <h3>📋 Pesanan Terbaru</h3>
        <table>
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Menu</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($pesanan) == 0): ?>
                    <tr><td colspan="6" style="text-align:center; color:#888;">Belum ada pesanan</td></tr>
                <?php else: ?>
                    <?php while ($p = mysqli_fetch_assoc($pesanan)): ?>
                    <tr>
                        <td><?= $p['nama_user'] ?></td>
                        <td><?= $p['nama_menu'] ?></td>
                        <td><?= $p['jumlah'] ?></td>
                        <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                        <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="pesanan.php" class="btn btn-primary">Lihat Semua Pesanan →</a>
    </div>

</div>
</body>
</html>

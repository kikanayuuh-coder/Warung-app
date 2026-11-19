<?php
// =============================================
// USER - PESANAN SAYA
// user/pesanan_saya.php
// =============================================
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}

// Ambil pesanan milik user yang sedang login
$user_id = $_SESSION['user_id'];
$query = "SELECT p.*, m.nama_menu, m.kategori, m.harga
          FROM pesanan p 
          JOIN menu m ON p.menu_id = m.id 
          WHERE p.user_id = '$user_id'
          ORDER BY p.created_at DESC";
$pesanan_saya = mysqli_query($conn, $query);

// Hitung total belanja
$total_belanja = 0;
$rows = mysqli_fetch_all($pesanan_saya, MYSQLI_ASSOC);
foreach ($rows as $p) {
    $total_belanja += $p['total_harga'];
}
// Reset pointer (karena sudah di-fetch semua)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya - Warung App</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>🍽️ Warung App</h1>
    <div>
        <a href="menu.php" style="background:#27ae60; margin-right:8px;">🍜 Lihat Menu</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="page-title">
        <h2>📋 Pesanan Saya</h2>
        <span style="color:#888;">Total: Rp <?= number_format($total_belanja, 0, ',', '.') ?></span>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Menu</th>
                    <th>Kategori</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu Pesan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="8" style="text-align:center; color:#888; padding:30px;">
                            Kamu belum pesan apa-apa. <a href="menu.php">Lihat menu →</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($rows as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><b><?= $p['nama_menu'] ?></b></td>
                        <td><span class="badge badge-<?= $p['kategori'] ?>"><?= ucfirst($p['kategori']) ?></span></td>
                        <td>Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                        <td><?= $p['jumlah'] ?>x</td>
                        <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge badge-<?= $p['status'] ?>">
                                <?php
                                if ($p['status'] == 'pending')   echo '⏳ Menunggu';
                                if ($p['status'] == 'diproses')  echo '🔄 Diproses';
                                if ($p['status'] == 'selesai')   echo '✅ Selesai';
                                ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($rows)): ?>
    <div class="alert alert-info">
        💡 <b>Keterangan Status:</b> ⏳ Menunggu = pesanan diterima | 🔄 Diproses = sedang dibuat | ✅ Selesai = siap diambil
    </div>
    <?php endif; ?>

</div>
</body>
</html>

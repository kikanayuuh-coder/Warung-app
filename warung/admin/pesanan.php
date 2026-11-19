<?php
// =============================================
// ADMIN - KELOLA PESANAN
// admin/pesanan.php
// =============================================
session_start();
require_once '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$pesan = "";

// Update Status Pesanan
if (isset($_POST['update_status'])) {
    $id     = $_POST['id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE pesanan SET status='$status' WHERE id='$id'");
    $pesan = '<div class="alert alert-success">✅ Status pesanan diperbarui!</div>';
}

// Hapus Pesanan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM pesanan WHERE id='$id'");
    $pesan = '<div class="alert alert-success">✅ Pesanan dihapus!</div>';
}

// Ambil semua pesanan
$query = "SELECT p.*, u.nama as nama_user, m.nama_menu, m.kategori
          FROM pesanan p 
          JOIN users u ON p.user_id = u.id 
          JOIN menu m ON p.menu_id = m.id 
          ORDER BY p.created_at DESC";
$semua_pesanan = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="navbar">
    <h1>🍽️ Warung App - Panel Admin</h1>
    <a href="../logout.php">Logout</a>
</div>

<div class="container">

    <div class="tab-nav">
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="menu.php">🍜 Kelola Menu</a>
        <a href="pesanan.php" class="active">📋 Pesanan</a>
    </div>

    <?= $pesan ?>

    <div class="card">
        <h3>📋 Semua Pesanan</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Menu</th>
                    <th>Jml</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (mysqli_num_rows($semua_pesanan) == 0): ?>
                    <tr><td colspan="8" style="text-align:center; color:#888; padding:20px;">Belum ada pesanan masuk</td></tr>
                <?php else: while ($p = mysqli_fetch_assoc($semua_pesanan)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $p['nama_user'] ?></td>
                    <td>
                        <?= $p['nama_menu'] ?>
                        <br><small class="badge badge-<?= $p['kategori'] ?>"><?= $p['kategori'] ?></small>
                    </td>
                    <td><?= $p['jumlah'] ?>x</td>
                    <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                    <td>
                        <!-- Form update status langsung di tabel -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding:4px; border-radius:4px; border:1px solid #ddd; font-size:12px;">
                                <option value="pending"   <?= $p['status']=='pending'   ? 'selected':'' ?>>⏳ Pending</option>
                                <option value="diproses"  <?= $p['status']=='diproses'  ? 'selected':'' ?>>🔄 Diproses</option>
                                <option value="selesai"   <?= $p['status']=='selesai'   ? 'selected':'' ?>>✅ Selesai</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td><?= date('d/m H:i', strtotime($p['created_at'])) ?></td>
                    <td>
                        <a href="pesanan.php?hapus=<?= $p['id'] ?>" 
                           class="btn btn-danger" 
                           style="padding:4px 8px; font-size:12px;"
                           onclick="return confirm('Hapus pesanan ini?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

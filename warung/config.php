<?php
// =============================================
// KONFIGURASI DATABASE
// Sesuaikan dengan pengaturan MySQL kamu
// =============================================

$host     = "localhost";   // Host database
$user     = "root";        // Username MySQL (default: root)
$password = "";            // Password MySQL (default: kosong di XAMPP)
$database = "warung_db";   // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset UTF-8 agar karakter Indonesia tampil dengan benar
mysqli_set_charset($conn, "utf8");
?>

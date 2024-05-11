<?php
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

// Sambungkan ke database
include 'konekke_local.php';

// Tangkap data yang dikirimkan dari form
$userid = $_SESSION['userid'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];
$alasan = $_POST['alasan'];
$status = 'Pending'; // Set status awal cuti menjadi Pending

// Query untuk menyimpan data cuti ke database
$query = "INSERT INTO cuti (userid, tanggal_mulai, tanggal_selesai, alasan, status_approval) VALUES ('$userid', '$tanggal_mulai', '$tanggal_selesai', '$alasan', '$status')";

// Jalankan query
if ($koneklocalhost->query($query) === TRUE) {
    // Jika query berhasil dijalankan, kembalikan ke halaman izin.php dengan pesan sukses
    $_SESSION['success_message'] = 'Cuti berhasil diajukan.';
    header('Location: izin.php');
    exit;
} else {
    // Jika terjadi kesalahan dalam menjalankan query, kembalikan ke halaman izin.php dengan pesan error
    $_SESSION['error_message'] = 'Terjadi kesalahan. Cuti gagal diajukan.';
    header('Location: izin.php');
    exit;
}

// Tutup koneksi ke database
$koneklocalhost->close();
?>
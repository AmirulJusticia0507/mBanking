<?php
include 'konekke_local.php';

// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}

// Tentukan zona waktu menjadi Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Tangkap data dari form inputan
$userid = $_SESSION['userid']; // Menggunakan userid dari session
$tanggal = date('Y-m-d'); // Ambil tanggal dalam format YYYY-MM-DD
$jam = date('H:i:s'); // Ambil jam dalam format HH:ii:ss
$keterangan = $_POST['keterangan'];
$tempat = isset($_POST['tempat']) ? $_POST['tempat'] : ''; // Periksa apakah 'tempat' ada di $_POST
$selfiephoto_name = $_FILES['selfiephoto']['name'];
$selfiephoto_tmp = $_FILES['selfiephoto']['tmp_name'];

// Tentukan lokasi penyimpanan foto
$target_dir = "uploads/photoabsensi/";
$selfiephoto_path = $target_dir . basename($selfiephoto_name);

// Periksa ukuran foto (maksimum 10MB)
$max_file_size = 10 * 1024 * 1024; // 10MB dalam bytes
if ($_FILES['selfiephoto']['size'] > $max_file_size) {
    // Ukuran foto melebihi batas maksimum, kirim notifikasi ke klien
    echo "Ukuran file melebihi batas maksimum (10MB).";
    exit;
}

// Pindahkan foto ke lokasi penyimpanan yang ditentukan
move_uploaded_file($selfiephoto_tmp, $selfiephoto_path);

// Ambil tanggal dan waktu saat ini dalam format YYYY-MM-DD HH:ii:ss
$absensi_created_date = date('Y-m-d H:i:s');

// Query untuk menyimpan data ke tabel absensi
$query = "INSERT INTO db_bmt_beningsuci.absensidigital (userid, tanggal, jam, keterangan, tempat, selfiephoto, absensi_created_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $koneklocalhost->prepare($query);
$stmt->bind_param("issssss", $userid, $tanggal, $jam, $keterangan, $tempat, $selfiephoto_path, $absensi_created_date);
$stmt->execute();
$stmt->close();

// Setelah semua operasi dieksekusi, redirect ke halaman absensi
header('Location: absensi.php?page=absensi');
exit; // Pastikan kode berhenti di sini setelah redirect
?>

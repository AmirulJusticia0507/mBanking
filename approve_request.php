<?php
// Lakukan koneksi ke database
include 'konekke_local.php';

// Tangkap ID dari permintaan AJAX
$id = $_POST['id'];

// Tangkap jenis permintaan dari permintaan AJAX
$type = $_POST['type']; // 'izin' atau 'cuti'

// Tentukan nama tabel berdasarkan jenis permintaan
$table = ($type == 'izin') ? 'izin' : 'cuti';

// Query untuk mengupdate status_approval menjadi 'approved' berdasarkan ID
$sql = "UPDATE $table SET status_approval = 'approved' WHERE id = '$id'";

// Lakukan query update
if ($koneklocalhost->query($sql) === TRUE) {
    // Jika berhasil, kirim respon 'success' ke JavaScript
    echo 'success';
} else {
    // Jika gagal, kirim respon 'error' ke JavaScript
    echo 'error';
}

// Tutup koneksi database
$koneklocalhost->close();
?>

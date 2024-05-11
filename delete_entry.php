<?php
// Lakukan koneksi ke database
include 'konekke_local.php';

// Tangkap ID dari permintaan AJAX
$id = $_POST['id'];

// Tentukan nama tabel berdasarkan jenis permintaan dari permintaan AJAX
$type = $_POST['type']; // 'izin' atau 'cuti'
$table = ($type == 'izin') ? 'izin' : 'cuti';

// Query untuk menghapus entri dari tabel berdasarkan ID
$sql = "DELETE FROM $table WHERE id = '$id'";

// Lakukan query penghapusan
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

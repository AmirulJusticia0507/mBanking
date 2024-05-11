<?php
date_default_timezone_set('Asia/Jakarta'); // Set zona waktu menjadi Asia/Jakarta

include 'konekke_local.php';

// Tangkap data dari formulir
$userid = $_POST['userid'];
$tanggal = $_POST['tanggal'];
$jam = $_POST['jam']; // Jam akan otomatis menggunakan waktu zona waktu yang telah ditetapkan

// Ubah format jam menjadi format yang diinginkan
$jam = date("H:i:s", strtotime($jam));

$keterangan = $_POST['keterangan'];
$info_lokasi = $_POST['info_lokasi'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Query untuk menyimpan data ke tabel absensi
$query = "INSERT INTO db_bmt_beningsuci.absensi (userid, tanggal, jam, keterangan, info_lokasi, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $koneklocalhost->prepare($query);
$stmt->bind_param("issssss", $userid, $tanggal, $jam, $keterangan, $info_lokasi, $latitude, $longitude);

// Lakukan eksekusi query
if ($stmt->execute()) {
    // Jika penyimpanan berhasil, kembalikan ke halaman sebelumnya dengan parameter success
    header("Location: {$_SERVER['HTTP_REFERER']}?success=true");
} else {
    // Jika terjadi kesalahan, kembalikan ke halaman sebelumnya dengan parameter error
    header("Location: {$_SERVER['HTTP_REFERER']}?error=true");
}

$stmt->close();
?>

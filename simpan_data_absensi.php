<?php
include 'konekke_local.php'; // Sesuaikan dengan nama file koneksi database Anda

// Tangkap data dari permintaan AJAX
$userid = $_POST['userid'];
$tanggal = $_POST['tanggal'];
$jam_masuk = $_POST['jam_masuk'];
$jam_pulang = $_POST['jam_pulang'];
$jam_istirahat = $_POST['jam_istirahat'];
$info_lokasi = $_POST['info_lokasi'];
$foto = $_POST['foto'];

// Simpan foto ke server (opsional)
$nama_file_foto = 'foto_' . time() . '.jpeg'; // Nama file foto berdasarkan timestamp
$lokasi_simpan_foto = 'uploads/photoabsensi/' . $nama_file_foto; // Sesuaikan dengan path folder tempat menyimpan foto
file_put_contents($lokasi_simpan_foto, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto))); // Simpan foto dalam format base64 ke file

// Logika untuk menentukan status absen
$status = ''; // Inisialisasi status absen
$jam_absen = strtotime($jam_masuk); // Konversi jam masuk ke format timestamp

// Periksa jam masuk
if ($jam_absen >= strtotime('08:00:00') && $jam_absen <= strtotime('17:00:00')) {
    $status = 'Masuk'; // Jika masuk di antara jam 08:00:00 dan 17:00:00
} elseif ($jam_absen < strtotime('12:00:00')) {
    $status = 'Terlambat'; // Jika masuk sebelum jam 12:00:00, dianggap terlambat
}

// Periksa jam istirahat
if ($jam_istirahat != null) {
    $durasi_istirahat = strtotime($jam_istirahat) - strtotime('12:00:00'); // Hitung durasi istirahat
    if ($durasi_istirahat < 3600) { // Jika durasi istirahat kurang dari 1 jam (3600 detik)
        $status = 'Terlambat Istirahat';
    }
}

// Periksa jam pulang
if ($jam_pulang != null) {
    if ($jam_absen > strtotime('17:00:00')) {
        $status = 'Overtime'; // Jika pulang setelah jam 17:00:00
    }
}

// Query untuk menyimpan data ke tabel absensi
$query = "INSERT INTO db_bmt_beningsuci.absensi (userid, tanggal, jam_masuk, jam_pulang, jam_istirahat, info_lokasi, status, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $koneklocalhost->prepare($query);
$stmt->bind_param("isssssss", $userid, $tanggal, $jam_masuk, $jam_pulang, $jam_istirahat, $info_lokasi, $status, $nama_file_foto);
$stmt->execute();
$stmt->close();

// Redirect kembali ke absensi.php?page=absensi setelah berhasil menyimpan data
header("Location: absensi.php?page=absensi&success=true");
exit();
?>

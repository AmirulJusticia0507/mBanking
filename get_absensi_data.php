<?php
// Sertakan file koneksi ke database
include 'konekke_local.php';

// Ambil data pencarian dari parameter POST
$namaKaryawan = $_POST['namaKaryawan'];
$tahun = $_POST['tahun'];
$tanggal = $_POST['tanggal'];

// Query untuk mendapatkan data absensi berdasarkan parameter pencarian
$query = "SELECT nama_karyawan, tanggal, jam, foto_absensi, keterangan 
          FROM data_absensi 
          WHERE nama_karyawan = '$namaKaryawan' 
          AND YEAR(tanggal) = '$tahun' 
          AND DATE(tanggal) = '$tanggal'";

// Eksekusi query
$result = $koneklocalhost->query($query);

// Inisialisasi array untuk menyimpan hasil pencarian
$data = array();

// Loop melalui hasil query dan tambahkan ke array
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Kembalikan hasil dalam format JSON
echo json_encode($data);
?>

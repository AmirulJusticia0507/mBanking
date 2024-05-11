<?php
// Sertakan file koneksi ke database
include 'konekke_local.php';

// Ambil data pencarian dari parameter GET atau POST
$searchTerm = $_GET['q'];

// Query untuk mencari karyawan berdasarkan nama
$query = "SELECT userid AS id, fullname AS text FROM db_bmt_beningsuci.users WHERE fullname LIKE '%$searchTerm%'";

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
